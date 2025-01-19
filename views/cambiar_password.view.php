<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <script src="<?= APP_ROOT ?>js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6">Cambiar Contraseña</h2>
            
            <!-- Información de ayuda -->
            <div class="mb-6 p-4 bg-blue-50 rounded-md">
                <p class="text-sm text-blue-600">
                    Complete el formulario con sus datos personales. Los campos marcados con * son obligatorios.
                </p>
            </div>

            <form id="formCambiarPassword" method="POST" class="space-y-4">
                <div>
                    <label for="password_actual" class="block text-sm font-medium text-gray-700">Contraseña Actual: <span class="text-red-500">*</span></label>
                    <input type="password" id="password_actual" name="password_actual" placeholder="Ingrese su contraseña actual" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="nuevo_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña: <span class="text-red-500">*</span></label>
                    <input type="password" id="nuevo_password" name="nuevo_password" placeholder="Ingrese su nueva contraseña" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="confirmar_password" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña: <span class="text-red-500">*</span></label>
                    <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Confirme su nueva contraseña" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cambiar Contraseña</button>
            </form>
            <div id="resultado" class="mt-4"></div>
        </div>
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.mensaje,
                    });
                }
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error de conexión: ' + error,
                });
            });
        });
    });
    </script>

</body>
</html>
