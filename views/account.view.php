<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php require APP_PATH . "html_parts/menu.php"; ?>
    
    <div class="flex flex-col items-center justify-center mt-10"> <!-- Changed from mt-20 to mt-10 -->
        <div class="bg-white p-8 rounded shadow-md w-full max-w-xl">
            <h1 class="text-2xl font-bold mb-4 text-center">Opciones de tu Cuenta</h1>
            <ul class="account-options flex flex-col space-y-6 items-center mt-4">
                <li>
                    <a href="#" id="help-link" class="w-full text-center px-4 py-2 border border-yellow-500 text-yellow-500 rounded hover:bg-yellow-500 hover:text-white transition duration-200">
                        <i class="fas fa-info-circle mr-2"></i> Ayuda
                    </a> 
                </li>
                <li>
                    <a href="<?= APP_ROOT ?>actualizar_perfil.php" class="w-full text-center px-4 py-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-500 hover:text-white transition duration-200">
                        <i class="fas fa-user-edit mr-2"></i> Actualizar Perfil
                    </a>
                </li>
                <li>
                    <a href="<?= APP_ROOT ?>cambiar_password.php" class="w-full text-center px-4 py-2 border border-green-500 text-green-500 rounded hover:bg-green-500 hover:text-white transition duration-200">
                        <i class="fas fa-key mr-2"></i> Actualizar Contraseña
                    </a>
                </li>
                <li>
                    <a href="<?= APP_ROOT ?>logout.php" id="logout-link" class="w-full text-center px-4 py-2 border border-red-500 text-red-500 rounded hover:bg-red-500 hover:text-white transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('help-link').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Ayuda',
            text: 'Para contactar a los desarrolladores, por favor envíe un correo a soporte@ejemplo.com o llame al 123-456-7890.',
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    });

    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Confirmar Cierre de Sesión',
            text: '¿Estás seguro de que deseas cerrar sesión?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cerrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= APP_ROOT ?>logout.php';
            }
        });
    });
</script>
</html>
