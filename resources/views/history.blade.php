<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Contraseñas</title>
    @vite('resources/css/app.css')
</head>

<body class="h-full">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-indigo-300 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cerrar sesión
                    </button>
                </form>
            </div>
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Historial de Contraseñas Generadas
                </h2>
            </div>
            <div class="mt-8 space-y-6">
                @if($passwords->count() > 0)
                <ul class="bg-white shadow overflow-hidden sm:rounded-md">
                    @foreach($passwords as $password)
                    <li class="px-4 py-4 sm:px-6 flex justify-between items-center">
                        <div class="text-sm font-medium text-indigo-600 truncate">{{ $password->value }}</div>
                        <form action="{{ route('password.delete', $password->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2">Eliminar</button>
                        </form>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-center text-gray-500">No hay contraseñas generadas aún.</p>
                @endif
                <div>
                    <a href="{{ route('password.form') }}" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Generar otra contraseña
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>