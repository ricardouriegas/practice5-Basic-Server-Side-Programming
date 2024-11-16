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
        <h1>Actualizar Perfil</h1>
    </div>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>Modificar Información Personal</h2>
                <form id="formActualizarPerfil" method="POST">
                    <label for="nombre">Nombre:</label><br>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($userData['nombre']) ?>"><br><br>

                    <label for="apellidos">Apellidos:</label><br>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($userData['apellidos']) ?>"><br><br>

                    <label for="genero">Género:</label><br>
                    <select id="genero" name="genero">
                        <option value="">Seleccione su género</option>
                        <option value="M" <?= $userData['genero'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $userData['genero'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="O" <?= $userData['genero'] == 'O' ? 'selected' : '' ?>>Otro</option>
                    </select><br><br>

                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($userData['fecha_nacimiento']) ?>"><br><br>

                    <button type="submit">Actualizar</button>
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
    document.getElementById('formActualizarPerfil').addEventListener('submit', function(e) {
        e.preventDefault();

        var resultado = document.getElementById('resultado');
        resultado.innerHTML = ''; // Limpiar mensajes anteriores

        var formData = new FormData(this);

        fetch('<?= APP_ROOT ?>do_actualizar_perfil.php', {
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
</script>

</body>
</html>