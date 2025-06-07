<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'POS')</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .active-tab {
            background-color: white;
            border-radius: 4px 4px 0 0;
            padding: 8px 16px;
        }
    </style>
    @vite('resources/css/app.css')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/pages/login.js'])
    @else
    <!-- You can add fallback assets here if needed -->
    @endif
</head>
<body class="flex h-screen">
    <div class="flex-1 flex flex-col">
        <nav class="flex space-x-4 bg-gray-100 pl-4 pt-4">
            <a id="link-ventas" href="{{ route('ventas') }}" class="{{ request()->routeIs('ventas') ? 'active-tab text-blue-500' : 'text-blue-500 hover:underline' }}">Ventas</a>
            <a id="link-productos" href="{{ route('productos') }}" class="{{ request()->routeIs('productos') ? 'active-tab text-blue-500' : 'text-blue-500 hover:underline' }}">Productos</a>
            <a id="link-reportes" href="{{ route('reportes') }}" class="{{ request()->routeIs('reportes') ? 'active-tab text-blue-500' : 'text-blue-500 hover:underline' }}">Reportes</a>

            <button id="logout-button" class="text-blue-500 hover:underline ml-auto mr-4">Cerrar sesión</button>

        </nav>
        <main class="flex-1 p-4">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rol = localStorage.getItem('rol');
            // Si es empleado, ocultar productos y reportes
            if (rol === 'empleado') {
                document.getElementById('link-productos').style.display = 'none';
                document.getElementById('link-reportes').style.display = 'none';
            }
        });

        document.getElementById('logout-button').addEventListener('click', () => {
            localStorage.removeItem('token');
            localStorage.removeItem('rol');
            window.location.href = "{{ route('login') }}";
        });
        
    </script>
</body>
</html>