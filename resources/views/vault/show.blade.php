@extends('layouts.app')
@section('title', $entry->title ?: 'Entrada')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('vault.index') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:underline">← Volver</a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <a href="{{ route('vault.edit', $entry) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Editar</a>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold">{{ $entry->title ?: 'Sin título' }}</h1>
                @if($entry->site)<p class="text-sm text-slate-500 dark:text-slate-400">{{ $entry->site }}</p>@endif
            </div>
            @if($entry->category)
            <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $entry->category->color }}-100 dark:bg-{{ $entry->category->color }}-900/30 text-{{ $entry->category->color }}-700 dark:text-{{ $entry->category->color }}-300">{{ $entry->category->name }}</span>
            @endif
        </div>

        @if($entry->username)
        <dl class="mt-4 grid grid-cols-3 gap-2 text-sm">
            <dt class="text-slate-500 dark:text-slate-400">Usuario</dt>
            <dd class="col-span-2 font-mono">{{ $entry->username }}</dd>
        </dl>
        @endif

        <div class="mt-4">
            <div class="text-xs uppercase tracking-wide text-slate-400 mb-1">Contraseña</div>
            <div class="flex items-center gap-2">
                <code id="password-text" class="flex-1 rounded-lg bg-slate-100 dark:bg-slate-800 px-3 py-2.5 font-mono text-sm break-all select-all">{{ $password }}</code>
                <button type="button" id="copy-btn" data-target="password-text" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2.5 text-sm font-medium">Copiar</button>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-slate-500 dark:text-slate-400">Fortaleza</span>
                <span class="font-mono">{{ $analysis['entropy'] }} bits</span>
            </div>
            @php
                $barColor = match($analysis['rating']) {
                    'very_strong' => 'bg-emerald-500',
                    'strong'      => 'bg-sky-500',
                    'fair'        => 'bg-amber-400',
                    'weak'        => 'bg-rose-400',
                    default       => 'bg-rose-500',
                };
            @endphp
            <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                <div class="h-full {{ $barColor }}" style="width:{{ min(100, ($analysis['entropy'] / 128) * 100) }}%"></div>
            </div>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Tiempo estimado de cracking offline: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $analysis['crack_time'] }}</span>
            </p>
        </div>

        @if($entry->tags->isNotEmpty())
        <div class="mt-6 flex flex-wrap gap-2 text-xs">
            @foreach($entry->tags as $t)<span class="rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-1">#{{ $t->name }}</span>@endforeach
        </div>
        @endif

        @if($entry->notes)
        <div class="mt-6">
            <div class="text-xs uppercase tracking-wide text-slate-400 mb-1">Notas</div>
            <p class="text-sm whitespace-pre-line">{{ $entry->notes }}</p>
        </div>
        @endif

        <form action="{{ route('vault.destroy', $entry) }}" method="POST" class="mt-6" onsubmit="return confirm('¿Eliminar esta entrada de forma permanente?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-rose-600 dark:text-rose-400 hover:underline">Eliminar entrada</button>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
        <h2 class="font-semibold mb-4">Compartir de forma segura</h2>
        <form action="{{ route('share.store', $entry) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
            @csrf
            <div>
                <label class="block mb-1">Expira en (min)</label>
                <input name="ttl_minutes" type="number" min="1" max="10080" value="15" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
            </div>
            <div>
                <label class="block mb-1">Máx. vistas</label>
                <input name="max_views" type="number" min="1" max="100" value="1" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
            </div>
            <div>
                <label class="block mb-1">Contraseña del receptor (opcional)</label>
                <input name="recipient_password" type="text" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2">
            </div>
            <button type="submit" class="sm:col-span-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2">Crear link auto-destructivo</button>
        </form>

        @if($shareLinks->isNotEmpty())
        <ul class="mt-4 space-y-2 text-sm">
            @foreach($shareLinks as $link)
            <li class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-200 dark:border-slate-800 px-3 py-2">
                <code class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ route('share.show', $link->token) }}</code>
                <div class="flex items-center gap-2 text-xs">
                    @if($link->isConsumed())<span class="text-rose-500">expirado</span>@else<span class="text-emerald-500">{{ $link->views }}/{{ $link->max_views }} vistas · expira {{ $link->expires_at->diffForHumans() }}</span>@endif
                    <form action="{{ route('share.destroy', [$entry, $link]) }}" method="POST">@csrf @method('DELETE')<button class="text-rose-500 hover:underline">revocar</button></form>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('copy-btn')?.addEventListener('click', async (e) => {
    const target = document.getElementById(e.target.dataset.target);
    await navigator.clipboard.writeText(target.textContent);
    const original = e.target.textContent;
    e.target.textContent = '✓ Copiado';
    e.target.classList.add('bg-emerald-600');
    setTimeout(() => { e.target.textContent = original; e.target.classList.remove('bg-emerald-600'); }, 2000);
});
</script>
@endpush
@endsection