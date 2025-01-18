<link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
<?php require APP_PATH . "html_parts/info_usuario.php"; ?>
<?php require APP_PATH . "html_parts/menu.php"; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Archivos Favoritos</h1>
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nombre</th>
                <th class="py-2 px-4 border-b">Descripción</th>
                <th class="py-2 px-4 border-b">Usuario</th>
                <th class="py-2 px-4 border-b">Tamaño</th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($archivos as $archivo): ?>
            <tr class="hover:bg-gray-100">
                <td class="py-2 px-4 border-b">
                    <a href="archivo.php?id=<?= $archivo['id'] ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($archivo['nombre_archivo']) ?>
                    </a>
                </td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($archivo['descripcion']) ?></td>
                <td class="py-2 px-4 border-b">
                    <a href="archivos_usuario.php?id=<?= $archivo['usuario_subio_id'] ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($archivo['username']) ?> (<?= htmlspecialchars($archivo['nombre'] . ' ' . $archivo['apellidos']) ?>)
                    </a>
                </td>
                <td class="py-2 px-4 border-b"><?= number_format($archivo['tamaño'] / 1024, 2) ?> KB</td>
                <td class="py-2 px-4 border-b">
                    <button onclick="toggleFavorite(<?= $archivo['id'] ?>)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                        Quitar Favorito
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleFavorite(id) {
    fetch('toggle_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>