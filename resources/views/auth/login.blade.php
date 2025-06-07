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
                <form id="login-form" class="space-y-5">

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-[#5D4037]">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#c67c4e] focus:ring-2 focus:ring-[#c67c4e]/20 outline-none transition-all bg-white"
                                placeholder="ejemplo@dominio.com" required />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-[#5D4037]">
                            Contraseña
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#c67c4e] focus:ring-2 focus:ring-[#c67c4e]/20 outline-none transition-all bg-white"
                                placeholder="••••••••" required />
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-[#582200] hover:bg-[#b16c4e] text-white font-medium rounded-lg shadow-md hover:shadow-lg transform transition-all focus:outline-none focus:ring-2 focus:ring-[#c67c4e]/50">
                        Iniciar sesión
                    </button>

                </form>
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
