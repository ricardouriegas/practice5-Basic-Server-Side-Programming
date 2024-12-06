<?php
function esFavorito($archivo_id, $usuario_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM favoritos WHERE archivo_id = ? AND usuario_id = ?");
    $stmt->execute([$archivo_id, $usuario_id]);
    return $stmt->fetch() ? true : false;
}
?>

<link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
<?php require APP_PATH . "html_parts/info_usuario.php"; ?>
<?php require APP_PATH . "html_parts/menu.php"; ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Archivos de <?= htmlspecialchars($usuario['nombre']) ?></h1>
    <form method="GET" class="mb-4">
        <input type="hidden" name="id" value="<?= $usuario_id ?>">
        <div class="flex space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Año:</label>
                <input type="number" name="year" value="<?= $year ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mes:</label>
                <input type="number" name="month" value="<?= $month ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Subido</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Favorito</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($archivos as $archivo): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap"><a href="archivo.php?id=<?= $archivo['id'] ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($archivo['nombre_archivo']) ?></a></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($archivo['descripcion']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= number_format($archivo['tamaño'] / 1024, 2) ?> KB</td>
                <td class="px-6 py-4 whitespace-nowrap"><?= $archivo['fecha_subido'] ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button onclick="toggleFavorite(<?= $archivo['id'] ?>)" class="px-4 py-2 bg-yellow-500 text-white rounded-md shadow-sm">
                        <?= esFavorito($archivo['id'], $USUARIO_ID) ? 'Quitar Favorito' : 'Marcar Favorito' ?>
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