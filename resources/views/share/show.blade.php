@extends('layouts.app')
@section('title', 'Contraseña compartida')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-6">
        <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-white text-2xl mb-3">📩</div>
        <h1 class="text-2xl font-bold">Tienes una contraseña</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Este link se autodestruirá al ser abierto.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
        <p class="text-sm mb-4">Expira en <span class="font-medium">{{ $link->expires_at->diffForHumans() }}</span> · Le quedan <span class="font-medium">{{ $link->max_views - $link->views }}</span> apertura(s).</p>

        @if($link->recipient_hash !== null)
        <form action="{{ route('share.reveal', $token) }}" method="POST" class="space-y-3">
            @csrf
            <label for="recipient_password" class="block text-sm">Contraseña acordada</label>
            <input id="recipient_password" name="recipient_password" type="password" required autofocus
                class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm">Revelar</button>
        </form>
        @else
        <form action="{{ route('share.reveal', $token) }}" method="POST">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm">Revelar contraseña</button>
        </form>
        @endif
    </div>
</div>
@endsection