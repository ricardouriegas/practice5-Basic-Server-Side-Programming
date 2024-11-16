<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>
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
                    <button onclick="deleteUser(${user.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function toggleAdmin(userId) {
        fetch('<?= APP_ROOT ?>admin/toggle_admin.php?id=' + userId)
            .then(response => response.json())
            .then(data => searchUsers());
    }

    function resetPassword(userId) {
        if(confirm('¿Está seguro de restablecer la contraseña?')) {
            fetch('<?= APP_ROOT ?>admin/reset_password.php?id=' + userId)
                .then(response => response.json())
                .then(data => searchUsers());
        }
    }

    function toggleActive(userId) {
        fetch('<?= APP_ROOT ?>admin/toggle_active.php?id=' + userId)
            .then(response => response.json())
            .then(data => searchUsers());
    }

    function deleteUser(userId) {
        if(confirm('¿Está seguro que desea eliminar este usuario?')) {
            fetch('<?= APP_ROOT ?>admin/delete_user.php?id=' + userId)
                .then(response => response.json())
                .then(data => searchUsers());
        }
    }

    // Cargar usuarios al iniciar
    window.onload = () => searchUsers();
    </script>
</body>
</html>