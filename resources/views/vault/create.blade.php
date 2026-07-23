@extends('layouts.app')
@section('title', 'Generar contraseña')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Generar contraseña</h1>

    <form action="{{ route('vault.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
            <div class="grid grid-cols-3 gap-2 mb-6">
                @foreach([
                    'random' => ['🎲', 'Aleatoria'],
                    'passphrase' => ['📝', 'Passphrase'],
                    'custom' => ['✍️', 'Personalizada'],
                ] as $type => [$icon, $label])
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" @checked(old('type','random')==$type) required>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-3 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 transition">
                        <div class="text-xl">{{ $icon }}</div>
                        <div class="text-xs mt-1 font-medium">{{ $label }}</div>
                    </div>
                </label>
                @endforeach
            </div>

            <div id="random-options" class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <label for="length">Longitud</label>
                        <span id="length-value" class="font-mono text-indigo-600 dark:text-indigo-400">16</span>
                    </div>
                    <input id="length" name="length" type="range" min="4" max="128" value="{{ old('length', 16) }}" class="w-full accent-indigo-600">
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    @foreach(['lowercase' => 'Minúsculas a-z', 'uppercase' => 'Mayúsculas A-Z', 'numbers' => 'Números 0-9', 'special' => 'Símbolos !@#'] as $name => $label)
                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-slate-800 px-3 py-2">
                        <input type="checkbox" name="include_{{ $name }}" value="1" @checked(old("include_$name", true)) class="rounded text-indigo-600 focus:ring-indigo-500">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div id="passphrase-options" class="hidden space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <label for="words">Número de palabras</label>
                        <span id="words-value" class="font-mono text-indigo-600 dark:text-indigo-400">4</span>
                    </div>
                    <input id="words" name="words" type="range" min="2" max="8" value="{{ old('words', 4) }}" class="w-full accent-indigo-600">
                </div>
                <div>
                    <label for="separator" class="block text-sm mb-1">Separador</label>
                    <input id="separator" name="separator" type="text" value="{{ old('separator', '-') }}" maxlength="5" class="w-24 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                </div>
            </div>

            <div id="custom-options" class="hidden">
                <label for="custom_value" class="block text-sm mb-1">Tu contraseña</label>
                <input id="custom_value" name="custom_value" type="text" value="{{ old('custom_value') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-mono">
            </div>

            <div class="mt-6">
                <button type="button" id="preview-btn" class="rounded-lg bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 px-4 py-2 text-sm font-medium">Vista previa</button>
                <div id="preview" class="mt-4 hidden">
                    <div class="rounded-lg bg-slate-100 dark:bg-slate-800 p-4 font-mono text-lg break-all select-all">{{ '' }}</div>
                    <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        <span id="preview-entropy"></span> · <span id="preview-crack"></span>
                    </div>
                    <div class="mt-2 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                        <div id="preview-bar" class="h-full transition-all" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <details class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
            <summary class="font-medium cursor-pointer">Metadatos y organización</summary>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <label for="title" class="block mb-1">Título</label>
                    <input id="title" name="title" value="{{ old('title') }}" placeholder="p. ej. Gmail" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
                </div>
                <div>
                    <label for="site" class="block mb-1">Sitio web</label>
                    <input id="site" name="site" value="{{ old('site') }}" placeholder="gmail.com" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
                </div>
                <div>
                    <label for="username" class="block mb-1">Usuario / email</label>
                    <input id="username" name="username" value="{{ old('username') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
                </div>
                <div>
                    <label for="category_id" class="block mb-1">Categoría</label>
                    <select id="category_id" name="category_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
                        <option value="">— Sin categoría —</option>
                        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="tags" class="block mb-1">Etiquetas (separadas por comas)</label>
                    <input id="tags" name="tags" value="{{ old('tags') }}" placeholder="trabajo, banking" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
                </div>
                <div class="sm:col-span-2">
                    <label for="notes" class="block mb-1">Notas</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </details>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 text-sm">Guardar en bóveda</button>
    </form>
</div>

@push('scripts')
<script>
const typeRadios = document.querySelectorAll('input[name="type"]');
const randomOpts = document.getElementById('random-options');
const passOpts = document.getElementById('passphrase-options');
const customOpts = document.getElementById('custom-options');
const lengthInput = document.getElementById('length');
const lengthValue = document.getElementById('length-value');
const wordsInput = document.getElementById('words');
const wordsValue = document.getElementById('words-value');

function toggleOptions() {
    const t = document.querySelector('input[name="type"]:checked').value;
    randomOpts.classList.toggle('hidden', t !== 'random');
    passOpts.classList.toggle('hidden', t !== 'passphrase');
    customOpts.classList.toggle('hidden', t !== 'custom');
}
typeRadios.forEach(r => r.addEventListener('change', toggleOptions));
toggleOptions();
lengthInput.addEventListener('input', () => lengthValue.textContent = lengthInput.value);
wordsInput.addEventListener('input', () => wordsValue.textContent = wordsInput.value);

async function preview() {
    const form = document.querySelector('form');
    const data = new FormData(form);
    const res = await fetch('{{ route("vault.create") }}'.replace('/vault/create','/vault/preview'), {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? document.querySelector('input[name="_token"]').value},
        body: data,
    });
    if (!res.ok) return;
    const j = await res.json();
    const box = document.getElementById('preview');
    box.classList.remove('hidden');
    box.querySelector('.font-mono').textContent = j.password;
    document.getElementById('preview-entropy').textContent = j.entropy + ' bits';
    document.getElementById('preview-crack').textContent = 'crack: ' + j.crack_time;
    const bar = document.getElementById('preview-bar');
    bar.style.width = Math.min(100, (j.entropy / 128) * 100) + '%';
    const colors = {very_weak:'bg-rose-500',weak:'bg-rose-400',fair:'bg-amber-400',strong:'bg-sky-500',very_strong:'bg-emerald-500'};
    bar.className = 'h-full transition-all ' + (colors[j.rating] ?? 'bg-slate-400');
}
document.getElementById('preview-btn')?.addEventListener('click', preview);
</script>
@endpush
@endsection