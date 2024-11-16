<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?=APP_ROOT?>css/style.css" rel="stylesheet" type="text/css">
    <script src="<?=APP_ROOT?>js/config.js"></script>
</head>
<body>

    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <div class="header">
        <h1>Práctica 05</h1>
        <h2>Registro de Usuario</h2>
    </div>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>Registro</h2>
                <form id="formRegistro" method="POST">
                    <label for="username">Usuario:</label><br>
                    <input type="text" id="username" name="username" required><br><br>

                    <label for="nombre">Nombre:</label><br>
                    <input type="text" id="nombre" name="nombre" required><br><br>

                    <label for="apellidos">Apellidos:</label><br>
                    <input type="text" id="apellidos" name="apellidos"><br><br>

                    <label for="genero">Género:</label><br>
                    <select id="genero" name="genero" required>
                        <option value="">Seleccione su género</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select><br><br>

                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required><br><br>

                    <label for="password">Contraseña:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <button type="submit">Registrarse</button>
                </form>
                <div id="resultado"></div>
            </div>
        </div>

        <?php require APP_PATH . "html_parts/page_right_column.php"; ?>

    </div>

    <div class="footer">
        <h2>ITI - Programación Web</h2>
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
                resultado.innerHTML = '<p style="color:red;">' + errores.join('<br>') + '</p>';
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
                    resultado.innerHTML = '<p style="color:red;">' + data.error + '</p>';
                } else {
                    resultado.innerHTML = '<p style="color:green;">' + data.mensaje + '</p>';
                    // Opcional: Redireccionar o limpiar el formulario
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
        });
    </script>

</body>
</html>