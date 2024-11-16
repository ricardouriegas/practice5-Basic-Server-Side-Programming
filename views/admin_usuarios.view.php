<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>

    <div class="header">
        <h1>Práctica 05</h1>
        <h2>Basic Server Side Programming</h2>
        <h4>Bienvenido <?=$USUARIO_NOMBRE_COMPLETO?></h4>
        <h5>Cantidad Visitas: <?=$cantidadVisitas?></h5>

        <!-- boton para actualizar usuario -->
        <?php if (isset($_SESSION['username'])) { ?>
            <!-- actualizar info del perfil -->
            <a href="<?= APP_ROOT ?>actualizar_perfil.php" class="btn">Actualizar Perfil</a>
            <!-- actualizar contrasena -->
            <a href="<?= APP_ROOT ?>cambiar_password.php" class="btn">Actualizar Contraseña</a>
        <?php } ?>
    </div>

    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <h2>Gestión de Usuarios</h2>
                
                <div class="search-section">
                    <input type="text" id="searchTerm" placeholder="Buscar por username o nombre">
                    <button onclick="searchUsers()">Buscar</button>
                </div>

                <table id="usersTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Admin</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Datos dinámicos -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function searchUsers() {
        const searchTerm = document.getElementById('searchTerm').value;
        fetch('<?= APP_ROOT ?>admin/do_buscar_usuarios.php?search=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => updateUsersTable(data));
    }

    function updateUsersTable(users) {
        const tbody = document.querySelector('#usersTable tbody');
        tbody.innerHTML = '';
        
        users.forEach(user => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${user.username}</td>
                <td>${user.nombre}</td>
                <td>${user.apellidos}</td>
                <td>${user.es_admin ? 'Sí' : 'No'}</td>
                <td>${user.activo ? 'Sí' : 'No'}</td>
                <td>
                    <button onclick="toggleAdmin(${user.id})">${user.es_admin ? 'Quitar Admin' : 'Hacer Admin'}</button>
                    <button onclick="resetPassword(${user.id})">Reset Password</button>
                    <button onclick="toggleActive(${user.id})">${user.activo ? 'Desactivar' : 'Activar'}</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Cargar usuarios al iniciar
    window.onload = () => searchUsers();
    </script>
</body>
</html>