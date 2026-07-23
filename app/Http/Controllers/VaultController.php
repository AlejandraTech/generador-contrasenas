<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\VaultEntry;
use App\Services\PasswordGenerator;
use App\Services\StrengthAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaultController extends Controller
{
    public function __construct(
        private readonly PasswordGenerator $generator,
        private readonly StrengthAnalyzer $analyzer,
    ) {}

    public function index(Request $request)
    {
        $query = Auth::user()->vaultEntries()->with(['category', 'tags'])->latest();

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('site', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->integer('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($tagId = $request->integer('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        return view('vault.index', [
            'entries' => $query->paginate(12)->withQueryString(),
            'categories' => Auth::user()->categories()->orderBy('name')->get(),
            'tags' => Auth::user()->tags()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('vault.create', [
            'categories' => Auth::user()->categories()->orderBy('name')->get(),
            'tags' => Auth::user()->tags()->orderBy('name')->get(),
        ]);
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:password,passphrase,custom,random'],
            'length' => ['nullable', 'integer', 'min:1', 'max:512'],
            'words' => ['nullable', 'integer', 'min:2', 'max:12'],
            'separator' => ['nullable', 'string', 'max:5'],
            'include_special' => ['nullable', 'boolean'],
            'include_numbers' => ['nullable', 'boolean'],
            'include_uppercase' => ['nullable', 'boolean'],
            'include_lowercase' => ['nullable', 'boolean'],
            'custom_value' => ['nullable', 'string', 'max:2048'],
        ]);

        $plain = $this->buildPassword($validated);
        $analysis = $this->analyzer->analyze($plain);

        return response()->json([
            'password' => $plain,
            'entropy' => $analysis['entropy'],
            'crack_time' => $analysis['crack_time'],
            'rating' => $analysis['rating'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:random,passphrase,custom'],
            'length' => ['nullable', 'integer', 'min:1', 'max:512'],
            'words' => ['nullable', 'integer', 'min:2', 'max:12'],
            'separator' => ['nullable', 'string', 'max:5'],
            'include_special' => ['nullable', 'boolean'],
            'include_numbers' => ['nullable', 'boolean'],
            'include_uppercase' => ['nullable', 'boolean'],
            'include_lowercase' => ['nullable', 'boolean'],
            'custom_value' => ['nullable', 'string', 'max:2048'],
            'title' => ['nullable', 'string', 'max:120'],
            'site' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id,user_id,'.Auth::id()],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:40'],
        ]);

        $plain = $this->buildPassword($validated);
        $analysis = $this->analyzer->analyze($plain);

        $entry = Auth::user()->vaultEntries()->create([
            'category_id' => $validated['category_id'] ?? null,
            'value' => encrypt($plain),
            'title' => $validated['title'] ?? null,
            'site' => $validated['site'] ?? null,
            'username' => $validated['username'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'type' => $validated['type'],
            'length' => $validated['type'] === 'passphrase' ? ($validated['words'] ?? 4) : ($validated['length'] ?? 16),
            'include_special' => $request->boolean('include_special', true),
            'include_numbers' => $request->boolean('include_numbers', true),
            'include_uppercase' => $request->boolean('include_uppercase', true),
            'include_lowercase' => $request->boolean('include_lowercase', true),
            'entropy_bits' => (int) round($analysis['entropy']),
        ]);

        if (! empty($validated['tags'])) {
            $this->syncTags($entry, $validated['tags']);
        }

        return redirect()->route('vault.show', $entry)->with('status', 'Contraseña generada y guardada en la bóveda.');
    }

    public function show(VaultEntry $entry)
    {
        $this->authorizeOwnership($entry);

        return view('vault.show', [
            'entry' => $entry,
            'password' => $entry->getDecryptedValue(),
            'analysis' => $this->analyzer->analyze($entry->getDecryptedValue()),
            'shareLinks' => $entry->shareLinks()->latest()->get(),
        ]);
    }

    public function edit(VaultEntry $entry)
    {
        $this->authorizeOwnership($entry);

        return view('vault.edit', [
            'entry' => $entry,
            'categories' => Auth::user()->categories()->orderBy('name')->get(),
            'tags' => Auth::user()->tags()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, VaultEntry $entry)
    {
        $this->authorizeOwnership($entry);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'site' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id,user_id,'.Auth::id()],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:40'],
        ]);

        $entry->update([
            'title' => $validated['title'] ?? null,
            'site' => $validated['site'] ?? null,
            'username' => $validated['username'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
        ]);

        $this->syncTags($entry, $validated['tags'] ?? []);

        return redirect()->route('vault.show', $entry)->with('status', 'Entrada actualizada.');
    }

    public function destroy(VaultEntry $entry)
    {
        $this->authorizeOwnership($entry);
        $entry->delete();

        return redirect()->route('vault.index')->with('status', 'Entrada eliminada de la bóveda.');
    }

    private function buildPassword(array $validated): string
    {
        return match ($validated['type']) {
            'passphrase' => $this->generator->generatePassphrase(
                words: $validated['words'] ?? 4,
                separator: $validated['separator'] ?? '-',
            ),
            'custom' => $validated['custom_value'] ?? '',
            default => $this->generator->generateRandom(
                length: $validated['length'] ?? 16,
                special: (bool) ($validated['include_special'] ?? true),
                numbers: (bool) ($validated['include_numbers'] ?? true),
                uppercase: (bool) ($validated['include_uppercase'] ?? true),
                lowercase: (bool) ($validated['include_lowercase'] ?? true),
            ),
        };
    }

    private function syncTags(VaultEntry $entry, array $tags): void
    {
        $tagIds = [];
        foreach ($tags as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $tagIds[] = Auth::user()->tags()->firstOrCreate(['name' => $name])->id;
        }
        $entry->tags()->sync($tagIds);
    }

    private function authorizeOwnership(VaultEntry $entry): void
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }
    }
}