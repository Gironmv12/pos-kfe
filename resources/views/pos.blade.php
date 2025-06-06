<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite([
      'resources/css/app.css',
      'resources/js/app.js',
      'resources/js/pages/login.js'    // <-- AÑADE ESTA LÍNEA
    ])
  @endif
</head>
<body class="p-6 font-sans">

    <h1 class="text-2xl font-bold mb-4">
      Bienvenido al sistema de la cafetería
    </h1> 

    <div id="user-info" class="mb-6 text-lg text-[#5D4037]"></div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      console.log('localStorage nombre:', localStorage.getItem('nombre'));
      console.log('localStorage rol:',    localStorage.getItem('rol'));
      const nombre = localStorage.getItem('nombre');
      const rol    = localStorage.getItem('rol');
      if (nombre && rol) {
        document.getElementById('user-info')
                .textContent = `Usuario: ${nombre} | Rol: ${rol}`;
      }
    });
  </script>

</body>
</html>