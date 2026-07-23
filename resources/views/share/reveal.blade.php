@extends('layouts.app')
@section('title', 'Contraseña revelada')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-6">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-white text-2xl mb-3">🔓</div>
        <h1 class="text-2xl font-bold">Aquí tienes</h1>
        <p class="text-sm text-rose-500 dark:text-rose-400 mt-1 font-medium">Cópiala ahora: este mensaje no volverá a mostrarse.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
        <div class="flex items-center gap-2">
            <code id="password-text" class="flex-1 rounded-lg bg-slate-100 dark:bg-slate-800 px-3 py-2.5 font-mono text-sm break-all select-all">{{ $password }}</code>
            <button type="button" id="copy-btn" data-target="password-text" class="rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2.5 text-sm font-medium">Copiar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('copy-btn')?.addEventListener('click', async (e) => {
    const target = document.getElementById(e.target.dataset.target);
    await navigator.clipboard.writeText(target.textContent);
    e.target.textContent = '✓';
    setTimeout(() => e.target.textContent = 'Copiar', 1500);
});
</script>
@endpush
@endsection