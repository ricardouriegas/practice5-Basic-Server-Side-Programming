<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?=APP_ROOT?>css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                    <i class="fas fa-info-circle"></i> Complete el formulario con sus datos personales. Los campos marcados con * son obligatorios.
                </p>
            </div>

            <form id="formRegistro" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Usuario: *</label>
                    <div class="relative mt-1">
                        <input type="text" id="username" name="username" required maxlength="128" placeholder="Ingrese su nombre de usuario" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre: *</label>
                    <div class="relative mt-1">
                        <input type="text" id="nombre" name="nombre" required maxlength="512" placeholder="Ingrese su nombre" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos: *</label>
                    <div class="relative mt-1">
                        <input type="text" id="apellidos" name="apellidos" required maxlength="512" placeholder="Ingrese sus apellidos" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-id-card-alt"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género: *</label>
                    <div class="relative mt-1">
                        <select id="genero" name="genero" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                            <option value="">Seleccione su género</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-venus-mars"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento: *</label>
                    <div class="relative mt-1">
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña: *</label>
                    <div class="relative mt-1">
                        <input type="password" id="password" name="password" required maxlength="128" placeholder="Ingrese su contraseña" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pl-10">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <!-- Barra de fuerza de contraseña -->
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded">
                            <div id="password-strength-bar" class="h-2 bg-red-500 rounded" style="width: 0%;"></div>
                        </div>
                        <p id="password-strength-text" class="text-sm mt-1 text-gray-600"></p>
                    </div>
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
            var nombre = document.getElementById('nombre').value.trim();
            var apellidos = document.getElementById('apellidos').value.trim();
            var password = document.getElementById('password').value;
            var errores = [];

            // Validación del username
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                errores.push('El nombre de usuario solo puede contener letras, números y guiones bajos');
            }
            if (username.length > 128) {
                errores.push('El nombre de usuario no puede exceder 128 caracteres');
            }

            // Validación del nombre
            if (nombre.length > 512) {
                errores.push('El nombre no puede exceder 512 caracteres');
            }

            // Validación de los apellidos
            if (apellidos.length > 512) {
                errores.push('Los apellidos no pueden exceder 512 caracteres');
            }

            // Validación de la contraseña
            if (password.length < 8) {
                errores.push('La contraseña debe tener al menos 8 caracteres');
            }
            if (password.length > 128) {
                errores.push('La contraseña no puede exceder 128 caracteres');
            }
            if (!(/[A-Za-z]/.test(password) && /[0-9]/.test(password))) {
                errores.push('La contraseña debe contener letras y números');
            }

            // Validación adicional: fuerza de la contraseña
            var strength = 0;
            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^A-Za-z0-9]/)) strength += 1;
            var strengthPercent = (strength / 5) * 100;
            if (strengthPercent < 40) {
                errores.push('La contraseña debe ser al menos de fuerza Media');
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
                        html: data.error
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro exitoso',
                        text: data.mensaje
                    }).then(function() {
                        window.location.href = '<?=APP_ROOT?>login.php';
                    });
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

        document.getElementById('password').addEventListener('input', function() {
            var strengthBar = document.getElementById('password-strength-bar');
            var strengthText = document.getElementById('password-strength-text');
            var password = this.value;
            var strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^A-Za-z0-9]/)) strength += 1;
            
            var strengthPercent = (strength / 5) * 100;
            strengthBar.style.width = strengthPercent + '%';
            
            if (strengthPercent < 40) {
                strengthBar.className = 'h-2 bg-red-500 rounded';
                strengthText.textContent = 'Baja';
            } else if (strengthPercent < 80) {
                strengthBar.className = 'h-2 bg-yellow-500 rounded';
                strengthText.textContent = 'Media';
            } else {
                strengthBar.className = 'h-2 bg-green-500 rounded';
                strengthText.textContent = 'Alta';
            }
        });
    </script>

</body>
</html>