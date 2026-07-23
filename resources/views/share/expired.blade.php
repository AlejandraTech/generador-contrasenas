@extends('layouts.app')
@section('title', 'Link expirado')

@section('content')
<div class="max-w-md mx-auto text-center py-16">
    <div class="text-5xl mb-4">🗑️</div>
    <h1 class="text-2xl font-bold mb-2">Este link ya no existe</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400">La contraseña fue vista el máximo de veces o el tiempo expiró. Por seguridad, ha sido autodestruida.</p>
</div>
@endsection