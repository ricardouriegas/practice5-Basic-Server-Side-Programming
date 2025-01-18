<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?=APP_ROOT?>css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?=APP_ROOT?>js/config.js"></script>
</head>
<body class="bg-gray-100">

    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <div class="flex justify-center mt-10">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6">Registro</h2>
            
            <!-- Información de ayuda -->
            <div class="mb-6 p-4 bg-blue-50 rounded-md">
                <p class="text-sm text-blue-600">
                    Complete el formulario con sus datos personales. Los campos marcados con * son obligatorios.
                </p>
            </div>

            <form id="formRegistro" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Usuario: *</label>
                    <input type="text" id="username" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre: *</label>
                    <input type="text" id="nombre" name="nombre" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género: *</label>
                    <select id="genero" name="genero" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Seleccione su género</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>

                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento: *</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña: *</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit" class="w-full px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-center inline-block">Registrarse</button>
                </div>
            </form>
            <div id="resultado" class="mt-4"></div>
        </div>
    </div>

    <script>
        document.getElementById('formRegistro').addEventListener('submit', function(e) {
            e.preventDefault();

            var resultado = document.getElementById('resultado');
            resultado.innerHTML = ''; // Limpiar mensajes anteriores

            var username = document.getElementById('username').value.trim();
            var password = document.getElementById('password').value;
            var errores = [];

            // Validación del username
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                errores.push('El nombre de usuario solo puede contener letras, números y guiones bajos');
            }

            // Validación de la contraseña
            if (password.length < 8) {
                errores.push('La contraseña debe tener al menos 8 caracteres');
            }
            if (!(/[A-Za-z]/.test(password) && /[0-9]/.test(password))) {
                errores.push('La contraseña debe contener letras y números');
            }

            if (errores.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Errores en el formulario',
                    html: errores.join('<br>')
                });
                return;
            }

            var formData = new FormData(this);

            fetch('<?=APP_ROOT?>do_register.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro exitoso',
                        text: data.mensaje
                    });
                    // Opcional: Redireccionar o limpiar el formulario
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud'
                });
            });
        });
    </script>

</body>
</html>