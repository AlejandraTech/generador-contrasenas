@extends('layouts.app')
@section('title', 'Verificación 2FA')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-6">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-white text-2xl mb-3">🛡️</div>
        <h1 class="text-2xl font-bold">Verificación en dos pasos</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Introduce el código de tu app autenticadora</p>
    </div>

    <form action="{{ route('twofactor.verify') }}" method="POST" class="space-y-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        @csrf
        <input name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-3 text-lg font-mono text-center tracking-[0.5em] focus:ring-2 focus:ring-indigo-500 outline-none">
        <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm">Verificar</button>
    </form>
</div>
@endsection