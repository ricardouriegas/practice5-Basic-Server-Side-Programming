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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Archivos de <?= htmlspecialchars($usuario['nombre']) ?></h1>
    <form method="GET" class="mb-4">
        <input type="hidden" name="id" value="<?= $usuario_id ?>">
        <div class="flex space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Año:</label>
                <select name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $year ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mes:</label>
                <select name="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
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
        <tbody class="bg-white divide-y divide-gray-200 text-center">
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
        Swal.fire({
            icon: data.success ? 'success' : 'error',
            title: data.message,
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            location.reload();
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al procesar tu solicitud.',
            showConfirmButton: true
        });
        console.error('Error:', error);
    });
}
</script>