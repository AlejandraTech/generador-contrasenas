<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'color' => ['nullable', 'string', 'in:indigo,emerald,rose,amber,sky,violet,slate'],
        ]);

        $category = Auth::user()->categories()->create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? 'indigo',
        ]);

        return redirect()->back()->with('status', "Categoría «{$category->name}» creada.");
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        $name = $category->name;
        $category->delete();

        return redirect()->route('vault.index')->with('status', "Categoría «{$name}» eliminada.");
    }
}