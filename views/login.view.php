<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?=APP_ROOT?>css/style.css" rel="stylesheet" type="text/css" /> 
    <title>Manejador de Archivos</title>
    <script src="<?=APP_ROOT?>js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

    <?php require APP_PATH . "html_parts/info_usuario.php" ?>
      
    <div class="container mx-auto mt-10">

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-2xl">
            <div class="md:flex">
                <div class="w-full p-4">
                    <h2 class="text-2xl font-bold text-gray-900">Iniciar Sesión</h2>
                    <h5 class="text-gray-600">Inicio de sesión para el Manejador de Archivos</h5>
                    <p class="mt-2 text-gray-600">Debe iniciar sesión para poder ingresar a la aplicación</p>
                    <form action="<?=APP_ROOT?>do_login.php" method="POST" class="mt-4" onsubmit="return validateForm()">
                        <div class="mb-4">
                            <label for="txt-username" class="block text-gray-700">Username:</label>
                            <input type="text" name="username" id="txt-username" placeholder="Ingrese su nombre de usuario" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
                        </div>
                        <div class="mb-4">
                            <label for="txt-password" class="block text-gray-700">Password:</label>
                            <input type="password" name="password" id="txt-password" placeholder="Ingrese su contraseña" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
                        </div>
                        <div class="mb-4">
                            <!-- <input type="submit" value="Entrar" class="w-full px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" /> -->
                            <button type="submit" class="w-full px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-2xl mt-6">
            <div class="md:flex">
            <div class="w-full p-4">
                <h2 class="text-2xl font-bold text-gray-900">Registro</h2>
                <h5 class="text-gray-600">Registro de nuevos usuarios</h5>
                <p class="mt-2 text-gray-600">
                Para poder acceder a los servicios de esta aplicación, debe tener una cuenta. 
                Si usted todavía no tiene una cuenta, haga click en el siguiente botón para registrarse.
                </p>
                <div class="mt-4">
                    <a href="registro.php" class="w-full px-3 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 text-center inline-block">Registrarme</a>
                </div>
            </div>
            </div>
        </div>

    </div>  <!-- End container -->

    <script>
        function validateForm() {
            const username = document.getElementById('txt-username').value;
            const password = document.getElementById('txt-password').value;

            if (username === '' || password === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Todos los campos son obligatorios.',
                });
                return false;
            }

            return true;
        }
    </script>

</body>
</html>
