<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    <!-- You can add fallback assets here if needed -->
    @endif
</head>
<body class="font-sans">
    <div class="flex min-h-screen">
        <!-- Lado izquierdo -->
        <div class="relative w-1/2 bg-cover bg-center"
     style="background-image: url('{{ asset('img/coffee-819362_1920.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="relative flex flex-col items-center justify-center h-full text-white p-8">
            <img src="{{ asset('img/logo-kfe.svg') }}" alt="Logo" class="mb-4 w-64 h-auto" />
            <p className="text-xl text-amber-50 font-serif italic max-w-md mx-auto">
            "La vida comienza después del café"
          </p>
        </div>
    </div>
        <!-- Lado derecho -->
        <div class="w-1/2 bg-[#f9f9f9] flex items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-xl shadow-xl p-8">
                <h1 class="text-2xl font-bold mb-6 text-center">Iniciar sesión</h1>
                <p class="text-sm text-gray-600 mb-6 text-center">
                    Bienvenido a KFE, por favor ingresa tus credenciales para continuar.
                </p>
                <form id="login-form" class="space-y-5">

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-[#5D4037]">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                            <input type="email" id="email" name="email"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:border-[#c67c4e] focus:ring-2 focus:ring-[#c67c4e]/20 outline-none transition-all bg-white"
                                placeholder="ejemplo@dominio.com" required />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-[#5D4037]">
                            Contraseña
                        </label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-icon lucide-lock w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" id="password" name="password"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:border-[#c67c4e] focus:ring-2 focus:ring-[#c67c4e]/20 outline-none transition-all bg-white"
                                placeholder="••••••••" required />
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-[#582200] hover:bg-[#b16c4e] text-white font-medium rounded-lg shadow-md hover:shadow-lg transform transition-all focus:outline-none focus:ring-2 focus:ring-[#c67c4e]/50">
                        Iniciar sesión
                    </button>

                </form>
                <div class="mt-8 text-center">
              <div class="text-sm text-gray-500">
                <p>Credenciales de prueba:</p>
                <p class="font-mono text-xs mt-1">carlos.04@ejemplo.com / carlos123</p>
                <p class="font-mono text-xs">fran.04@ejemplo.com / GironDev12</p>
              </div>
            </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;
    
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const LOGIN_URL = "{{ route('api.login') }}";
            const response = await axios.post(LOGIN_URL, {
                email: email,
                password: password
            });
            // Se espera que la respuesta incluya token, rol y nombre
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('rol', response.data.rol);
            localStorage.setItem('nombre', response.data.nombre);
            localStorage.setItem('usuario_id', response.data.usuario_id);
            // Redirigir al dashboard o POS
            window.location.href = '/pos';
        } catch (error) {
            console.error(error);
            alert('Error en la autenticación');
        }
    });
});
    </script>
</body>
</html>
