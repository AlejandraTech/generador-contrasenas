@extends('layouts.app')
@section('title', 'Activar 2FA')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-2">Activar verificación en dos pasos</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Escanea este QR con Google Authenticator, Authy o 1Password.</p>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 text-center">
        <img src="{{ $qr }}" alt="QR 2FA" class="mx-auto w-56 h-56">
        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">O introduce manualmente:</p>
        <code class="block mt-1 font-mono text-sm break-all bg-slate-100 dark:bg-slate-800 p-2 rounded">{{ $secret }}</code>
    </div>

    <form action="{{ route('twofactor.confirm') }}" method="POST" class="mt-6 space-y-3">
        @csrf
        <label for="code" class="block text-sm">Código de 6 dígitos</label>
        <input id="code" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm font-mono text-center tracking-[0.5em]">
        <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm">Confirmar</button>
    </form>
</div>
@endsection