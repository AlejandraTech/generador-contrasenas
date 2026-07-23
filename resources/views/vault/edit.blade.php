@extends('layouts.app')
@section('title', 'Editar entrada')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar entrada</h1>

    <form action="{{ route('vault.update', $entry) }}" method="POST" class="space-y-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        @csrf @method('PUT')
        <div>
            <label for="title" class="block text-sm mb-1">Título</label>
            <input id="title" name="title" value="{{ old('title', $entry->title) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="site" class="block text-sm mb-1">Sitio</label>
            <input id="site" name="site" value="{{ old('site', $entry->site) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="username" class="block text-sm mb-1">Usuario</label>
            <input id="username" name="username" value="{{ old('username', $entry->username) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="category_id" class="block text-sm mb-1">Categoría</label>
            <select id="category_id" name="category_id" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
                <option value="">—</option>
                @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id',$entry->category_id)==$c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="tags" class="block text-sm mb-1">Etiquetas (comas)</label>
            <input id="tags" name="tags" value="{{ old('tags', $entry->tags->pluck('name')->implode(',')) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        </div>
        <div>
            <label for="notes" class="block text-sm mb-1">Notas</label>
            <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">{{ old('notes', $entry->notes) }}</textarea>
        </div>
        <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm">Guardar cambios</button>
    </form>
</div>
@endsection