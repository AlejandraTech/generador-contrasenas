<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bóveda') — Cripta</title>
    <script>
        (function() {
            const stored = localStorage.getItem('theme');
            const prefers = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefers)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 transition-colors">
    <div class="min-h-full flex flex-col">
        @auth
        <header class="border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('vault.index') }}" class="flex items-center gap-2 font-bold text-lg">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white">🔐</span>
                    <span>Cripta</span>
                </a>
                <nav class="flex items-center gap-1 sm:gap-2 text-sm">
                    <a href="{{ route('vault.create') }}" class="px-3 py-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Generar</a>
                    <a href="{{ route('vault.index') }}" class="px-3 py-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Bóveda</a>
                    @if(Auth::user()->hasTwoFactorEnabled())
                    <span class="px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 text-xs font-medium" title="2FA activo">2FA</span>
                    <form action="{{ route('twofactor.disable') }}" method="POST">@csrf<button class="px-3 py-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800" title="Desactivar 2FA">Quitar 2FA</button></form>
                    @else
                    <a href="{{ route('twofactor.enable') }}" class="px-3 py-2 rounded-md text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20" title="Activar 2FA">＋ 2FA</a>
                    @endif
                    <button type="button" id="theme-toggle" class="ml-1 p-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Cambiar tema">
                        <span class="dark:hidden">🌙</span><span class="hidden dark:inline">☀️</span>
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-md text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">Salir</button>
                    </form>
                </nav>
            </div>
        </header>
        @endauth

        <main class="flex-1 @auth max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 @endauth">
            @if(session('status'))
            <div class="mb-6 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
            @endif
            @if($errors->any())
            <div class="mb-6 rounded-lg bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif
            @yield('content')
        </main>

        <footer class="py-6 text-center text-xs text-slate-400 dark:text-slate-600">
            Cripta · Generador de contraseñas y bóveda · Laravel {{ app()->version() }}
        </footer>
    </div>
    <script>
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
    @stack('scripts')
</body>
</html>