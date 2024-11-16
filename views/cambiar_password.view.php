<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <script src="<?= APP_ROOT ?>js/config.js"></script>
</head>
<body>

    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <div class="header">
        <h1>Cambiar Contraseña</h1>
    </div>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>Cambiar Contraseña</h2>
                <form id="formCambiarPassword" method="POST">
                    <label for="password_actual">Contraseña Actual:</label><br>
                    <input type="password" id="password_actual" name="password_actual" required><br><br>

                    <label for="nuevo_password">Nueva Contraseña:</label><br>
                    <input type="password" id="nuevo_password" name="nuevo_password" required><br><br>

                    <label for="confirmar_password">Confirmar Nueva Contraseña:</label><br>
                    <input type="password" id="confirmar_password" name="confirmar_password" required><br><br>

                    <button type="submit">Cambiar Contraseña</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('formCambiarPassword').addEventListener('submit', function(e) {
            e.preventDefault();

            var resultado = document.getElementById('resultado');
            resultado.innerHTML = ''; // Limpiar mensajes anteriores

            var formData = new FormData(this);

            fetch('<?= APP_ROOT ?>do_cambiar_password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    resultado.innerHTML = '<p style="color:red;">' + data.error + '</p>';
                } else {
                    resultado.innerHTML = '<p style="color:green;">' + data.mensaje + '</p>';
                }
            }).catch(error => {
                resultado.innerHTML = '<p style="color:red;">Error de conexión: ' + error + '</p>';
            });
        });
    });
    </script>

</body>
</html>