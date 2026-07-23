@extends('layouts.app')
@section('title', 'Bóveda')

@section('content')
@if(!Auth::user()->hasTwoFactorEnabled())
<div class="mb-6 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-900/20 p-4 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <span class="text-2xl">🛡️</span>
        <div>
            <p class="font-medium text-amber-800 dark:text-amber-200">Protege tu bóveda con 2FA</p>
            <p class="text-sm text-amber-700 dark:text-amber-300">Una capa extra de seguridad para tus contraseñas.</p>
        </div>
    </div>
    <a href="{{ route('twofactor.enable') }}" class="rounded-lg bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 text-sm font-medium">Activar</a>
</div>
@endif

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Mi bóveda</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $entries->total() }} entradas guardadas</p>
    </div>
    <a href="{{ route('vault.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium">
        ＋ Generar nueva
    </a>
</div>

<form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-2">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título, sitio o usuario…"
        class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
    <select name="category" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        <option value="">Categoría</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category')==$c->id)>{{ $c->name }}</option>@endforeach
    </select>
    <select name="tag" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
        <option value="">Etiqueta</option>
        @foreach($tags as $t)<option value="{{ $t->id }}" @selected(request('tag')==$t->id)>#{{ $t->name }}</option>@endforeach
    </select>
    <button type="submit" class="rounded-lg bg-slate-200 dark:bg-slate-800 px-4 py-2 text-sm font-medium hover:bg-slate-300 dark:hover:bg-slate-700 sm:col-span-3">Filtrar</button>
</form>

<div class="mb-6 flex flex-wrap items-center gap-2">
    <span class="text-xs uppercase tracking-wide text-slate-400">Categorías:</span>
    @foreach($categories as $c)
    <span class="inline-flex items-center gap-1 rounded-full bg-{{ $c->color }}-100 dark:bg-{{ $c->color }}-900/30 text-{{ $c->color }}-700 dark:text-{{ $c->color }}-300 text-xs px-2 py-1">
        {{ $c->name }}
        <form action="{{ route('categories.destroy', $c) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="hover:text-rose-500" title="Eliminar">×</button>
        </form>
    </span>
    @endforeach
    <button type="button" id="new-category-btn" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">＋ nueva</button>
</div>

<form id="new-category-form" method="POST" action="{{ route('categories.store') }}" class="hidden mb-6 flex flex-wrap gap-2 items-center">
    @csrf
    <input name="name" placeholder="Nombre" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-sm">
    <select name="color" class="rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-sm">
        @foreach(['indigo','emerald','rose','amber','sky','violet','slate'] as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
    </select>
    <button type="submit" class="rounded-lg bg-indigo-600 text-white px-3 py-1.5 text-sm">Crear</button>
</form>

@if($entries->isEmpty())
<div class="text-center py-16 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
    <div class="text-4xl mb-3">🗂️</div>
    <p class="text-slate-500 dark:text-slate-400">Tu bóveda está vacía.</p>
    <a href="{{ route('vault.create') }}" class="mt-4 inline-block rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium">Generar primera contraseña</a>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($entries as $entry)
    <a href="{{ route('vault.show', $entry) }}" class="group block rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <h3 class="font-semibold truncate">{{ $entry->title ?: ($entry->site ?: 'Sin título') }}</h3>
                @if($entry->site)<p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $entry->site }}</p>@endif
            </div>
            @if($entry->category)
            <span class="shrink-0 text-xs px-2 py-0.5 rounded-full bg-{{ $entry->category->color }}-100 dark:bg-{{ $entry->category->color }}-900/30 text-{{ $entry->category->color }}-700 dark:text-{{ $entry->category->color }}-300">{{ $entry->category->name }}</span>
            @endif
        </div>
        <div class="mt-3 font-mono text-sm text-slate-400 dark:text-slate-500 truncate">••••••••••••••</div>
        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            @if($entry->entropy_bits)
            <span class="inline-flex items-center gap-1">
                @php
                    $rating = match(true) {
                        $entry->entropy_bits >= 128 => ['label' => 'muy fuerte', 'cls' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'],
                        $entry->entropy_bits >= 60  => ['label' => 'fuerte',     'cls' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300'],
                        $entry->entropy_bits >= 36  => ['label' => 'aceptable',  'cls' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300'],
                        default                     => ['label' => 'débil',      'cls' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300'],
                    };
                @endphp
                <span class="px-1.5 py-0.5 rounded {{ $rating['cls'] }}">{{ $entry->entropy_bits }} bits · {{ $rating['label'] }}</span>
            </span>
            @endif
            @foreach($entry->tags as $t)<span class="text-indigo-500 dark:text-indigo-400">#{{ $t->name }}</span>@endforeach
        </div>
    </a>
    @endforeach
</div>

<div class="mt-8">{{ $entries->links() }}</div>
@endif

@push('scripts')
<script>document.getElementById('new-category-btn')?.addEventListener('click', () => document.getElementById('new-category-form').classList.toggle('hidden'));</script>
@endpush
@endsection