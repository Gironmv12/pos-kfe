<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'POS')</title>
    @vite('resources/css/app.css')
</head>
<body class="flex h-screen">
    <div class="flex-1 flex flex-col">
        <nav class="flex space-x-4 bg-gray-100 p-4">
            <a href="{{ route('ventas') }}" class="text-blue-500 hover:underline">Ventas</a>
            <a href="{{ route('productos') }}" class="text-blue-500 hover:underline">Productos</a>
            <a href="{{ route('reportes') }}" class="text-blue-500 hover:underline">Reportes</a>
        </nav>
        <main class="flex-1 p-4">
            @yield('content')
        </main>
    </div>
</body>
</html>