@extends('layouts.app')
@section('title', 'Registro')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-white text-2xl mb-3">🔐</div>
            <h1 class="text-2xl font-bold">Crear cuenta</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Empieza a generar contraseñas seguras</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium mb-1">Nombre</label>
                <input id="name" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Repite la contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 text-sm transition-colors">
                Registrarme
            </button>
        </form>
        <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-6">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Inicia sesión</a>
        </p>
    </div>
</div>
@endsection