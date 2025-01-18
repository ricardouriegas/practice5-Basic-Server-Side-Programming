<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <script src="<?= APP_ROOT ?>js/config.js"></script>
</head>
<body class="bg-gray-100">

    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-5 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-5">Modificar Información Personal</h2>
            <form id="formActualizarPerfil" method="POST" class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($userData['nombre']) ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($userData['apellidos']) ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género:</label>
                    <select id="genero" name="genero" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Seleccione su género</option>
                        <option value="M" <?= $userData['genero'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $userData['genero'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="O" <?= $userData['genero'] == 'O' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($userData['fecha_nacimiento']) ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Actualizar</button>
                </div>
            </form>
            <div id="resultado" class="mt-4"></div>
        </div>
    </div>

    <script>
    document.getElementById('formActualizarPerfil').addEventListener('submit', function(e) {
        e.preventDefault();

        var resultado = document.getElementById('resultado');
        resultado.innerHTML = ''; // Limpiar mensajes anteriores

        var nombre = document.getElementById('nombre').value.trim();
        var apellidos = document.getElementById('apellidos').value.trim();
        var genero = document.getElementById('genero').value.trim();
        var fechaNacimiento = document.getElementById('fecha_nacimiento').value.trim();

        if (!nombre && !apellidos && !genero && !fechaNacimiento) {
            resultado.innerHTML = '<p class="text-red-500">Por favor, complete al menos un campo.</p>';
            return;
        }

        var formData = new FormData(this);

        fetch('<?= APP_ROOT ?>do_actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                resultado.innerHTML = '<p class="text-red-500">' + data.error + '</p>';
            } else {
                resultado.innerHTML = '<p class="text-green-500">' + data.mensaje + '</p>';
            }
        }).catch(error => {
            resultado.innerHTML = '<p class="text-red-500">Error de conexión: ' + error + '</p>';
        });
    });
    </script>

</body>
</html>