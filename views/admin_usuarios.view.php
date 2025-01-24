<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 pb-20"> <!-- Agregado 'pb-20' -->
    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Gestión de Usuarios</h2>
            
            <div class="mb-4">
                <input type="text" id="searchTerm" placeholder="Buscar por username o nombre" class="border border-gray-300 rounded-lg p-2 mr-2">
                <button onclick="searchUsers()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>

            <div id="statusMessage" class="mb-4 text-center text-red-500"></div>

            <table id="usersTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 border-b">Username</th>
                        <th class="py-2 px-4 border-b">Nombre</th>
                        <th class="py-2 px-4 border-b">Apellidos</th>
                        <th class="py-2 px-4 border-b">Admin</th>
                        <th class="py-2 px-4 border-b">Activo</th>
                        <th class="py-2 px-4 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-center">
                    <!-- Datos dinámicos -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function searchUsers() {
        const searchTerm = document.getElementById('searchTerm').value;
        document.getElementById('statusMessage').textContent = 'Buscando usuarios...';
        fetch('<?= APP_ROOT ?>admin/do_buscar_usuarios.php?search=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                updateUsersTable(data);
                document.getElementById('statusMessage').textContent = '';
            })
            .catch(error => {
                Swal.fire('Error', 'Error al buscar usuarios.', 'error');
            });
    }

    function updateUsersTable(users) {
        const tbody = document.querySelector('#usersTable tbody');
        tbody.innerHTML = '';
        
        users.forEach(user => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2 px-4 border-b">${user.username}</td>
                <td class="py-2 px-4 border-b">${user.nombre}</td>
                <td class="py-2 px-4 border-b">${user.apellidos}</td>
                <td class="py-2 px-4 border-b">${user.es_admin ? 'Sí' : 'No'}</td>
                <td class="py-2 px-4 border-b">${user.activo ? 'Sí' : 'No'}</td>
                <td class="py-2 px-4 border-b">
                    <button onclick="toggleAdmin(${user.id})" class="bg-yellow-500 text-white px-2 py-1 rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-user-shield mr-2"></i>${user.es_admin ? 'Quitar Admin' : 'Hacer Admin'}
                    </button>
                    <button onclick="resetPassword(${user.id})" class="bg-green-500 text-white px-2 py-1 rounded-lg hover:bg-green-600">
                        <i class="fas fa-key mr-2"></i>Reset Password
                    </button>
                    <button onclick="toggleActive(${user.id})" class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600">
                        <i class="${user.activo ? 'fas fa-toggle-on' : 'fas fa-toggle-off'} mr-2"></i>${user.activo ? 'Desactivar' : 'Activar'}
                    </button>
                    <button onclick="deleteUser(${user.id})" class="bg-red-500 text-white px-2 py-1 rounded-lg hover:bg-red-600">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function toggleAdmin(userId) {
        fetch(`<?= APP_ROOT ?>admin/do_admin_action.php?action=toggleAdmin&id=${userId}`)
            .then(response => response.json())
            .then(data => {
                Swal.fire(data.mensaje, '', 'info');
                searchUsers();
            })
            .catch(error => {
                Swal.fire('Error', 'Error al cambiar el estado de admin.', 'error');
            });
    }

    function resetPassword(userId) {
        Swal.fire({
            title: '¿Está seguro de restablecer la contraseña?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, restablecer',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= APP_ROOT ?>admin/do_admin_action.php?action=resetPassword&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(data.mensaje, '', 'info');
                        searchUsers();
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Error al restablecer la contraseña.', 'error');
                    });
            }
        });
    }

    function toggleActive(userId) {
        fetch(`<?= APP_ROOT ?>admin/do_admin_action.php?action=toggleActive&id=${userId}`)
            .then(response => response.json())
            .then(data => {
                Swal.fire(data.mensaje, '', 'info');
                searchUsers();
            })
            .catch(error => {
                Swal.fire('Error', 'Error al cambiar el estado de actividad.', 'error');
            });
    }

    function deleteUser(userId) {
        Swal.fire({
            title: '¿Está seguro que desea eliminar este usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= APP_ROOT ?>admin/do_admin_action.php?action=delete&id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(data.mensaje, '', 'info');
                        searchUsers();
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Error al eliminar el usuario.', 'error');
                    });
            }
        });
    }

    // Cargar usuarios al iniciar
    window.onload = () => searchUsers();
    </script>
</body>
</html>
