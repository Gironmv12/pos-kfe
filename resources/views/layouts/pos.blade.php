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
<body class="flex flex-col h-screen">
    <header class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-200 px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-8">
          <div class="flex items-center space-x-3">
            <div class="">
              <img src="{{ asset('img/logo_negro.svg') }}" alt="Logo" class="w-9">
            </div>
          </div>
          <nav class="flex space-x-1">
            <a id="link-ventas" href="{{ route('ventas') }}"
              class="px-6 py-3 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('ventas') ? 'bg-white text-amber-800 shadow-md border border-amber-200' : 'text-gray-600 hover:text-amber-800 hover:bg-white/50' }}">
              Ventas
            </a>
            <a id="link-productos" href="{{ route('productos') }}"
              class="px-6 py-3 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('productos') ? 'bg-white text-amber-800 shadow-md border border-amber-200' : 'text-gray-600 hover:text-amber-800 hover:bg-white/50' }}">
              Productos
            </a>
            <a id="link-reportes" href="{{ route('reportes') }}"
              class="px-6 py-3 rounded-lg font-medium transition-all duration-200 {{ request()->routeIs('reportes') ? 'bg-white text-amber-800 shadow-md border border-amber-200' : 'text-gray-600 hover:text-amber-800 hover:bg-white/50' }}">
              Reportes
            </a>
          </nav>
        </div>
        <div class="flex items-center space-x-3 bg-white rounded-xl px-4 py-2 shadow-sm border border-amber-200 relative">
          <button id="user-menu-button" type="button" class="flex items-center space-x-3 focus:outline-none">
            <div class="">
              <img src="{{ asset('img/usuario.png') }}" alt="User" class="w-9">
            </div>
            <div class="text-right">
              <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->nombre }}</p>
              <p class="text-xs text-gray-500">{{ auth()->user()->rol }}</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-chevron-down w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <polyline points="6 9 12 15 18 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-40 bg-white rounded-md shadow-lg z-50">
            <button id="logout-button" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-log-out w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M9 16l-4-4m0 0l4-4m-4 4h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M17 16v1a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Cerrar sesión
            </button>
          </div>
        </div>
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const userMenuBtn = document.getElementById('user-menu-button');
          const userDropdown = document.getElementById('user-dropdown');
          userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
          });
          document.addEventListener('click', () => {
            if (!userDropdown.classList.contains('hidden')) {
              userDropdown.classList.add('hidden');
            }
          });
          userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
          });

          // Conservamos la funcionalidad de cerrar sesión
          document.getElementById('logout-button').addEventListener('click', () => {
            localStorage.removeItem('token');
            localStorage.removeItem('rol');
            window.location.href = "{{ route('login') }}";
          });
        });
      </script>
    </header>
    <div class="flex-1 flex flex-col">
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
    </script>
    <!-- ...existing code... -->
</body>
</html>