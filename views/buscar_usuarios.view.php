<title><?= htmlspecialchars($tituloPagina) ?></title>
<link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
<?php require APP_PATH . "html_parts/info_usuario.php"; ?>
<?php require APP_PATH . "html_parts/menu.php"; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex flex-col items-center justify-center mt-20">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Buscar Usuarios</h1>
        <form id="form-buscar" class="flex flex-col space-y-4">
            <input type="text" id="input-buscar" placeholder="Username o nombre" class="p-2 border border-gray-300 rounded" aria-label="Buscar usuario">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Buscar</button>
        </form>
        <div id="resultados" class="mt-4"></div>
    </div>
</div>

<script>
document.getElementById('form-buscar').addEventListener('submit', function(e) {
    e.preventDefault();
    const search = document.getElementById('input-buscar').value.trim();
    
    if (!search) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo vacío',
            text: 'Por favor, ingrese un término de búsqueda.',
        });
        return;
    }

    fetch(`do_buscar_usuarios.php?search=${encodeURIComponent(search)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            const resultadosDiv = document.getElementById('resultados');
            resultadosDiv.innerHTML = '';
            if (data.length === 0) {
                resultadosDiv.innerHTML = '<p class="text-center text-gray-500">No se encontraron usuarios.</p>';
                return;
            }
            data.forEach(usuario => {
                resultadosDiv.innerHTML += `
                    <div class="p-4 border-b border-gray-200">
                        <p class="font-bold">${usuario.username}</p>
                        <p>${usuario.nombre} ${usuario.apellidos}</p>
                        <a href="archivos_usuario.php?id=${usuario.id}" class="text-blue-500 hover:underline">Ver archivos públicos</a>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al realizar la búsqueda. Por favor, inténtelo de nuevo más tarde.',
            });
        });
});
</script>