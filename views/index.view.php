<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>
    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($tituloPagina) ?></h2>

        <!-- Formulario de subida de archivos -->
        <form id="uploadForm" enctype="multipart/form-data" class="mb-4 p-4 bg-white shadow-md rounded">
            <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.gif" class="mb-2 p-2 border rounded" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Subir Archivo</button>
        </form>

        <!-- Filtros de año y mes -->
        <form method="get" action="" class="mb-4 p-4 bg-white shadow-md rounded">
            <label for="year" class="block mb-2">Año:</label>
            <select name="year" id="year" class="mb-2 p-2 border rounded">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>

            <label for="month" class="block mb-2">Mes:</label>
            <select name="month" id="month" class="mb-2 p-2 border rounded">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $m ?></option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrar</button>
        </form>

        <!-- Tabla de archivos -->
        <table class="min-w-full bg-white shadow-md rounded">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nombre del Archivo</th>
                    <th class="py-2 px-4 border-b">Fecha de Subida</th>
                    <th class="py-2 px-4 border-b">Tamaño (KB)</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $archivo): ?>
                <tr>
                    <td class="py-2 px-4 border-b">
                        <a href="archivo.php?id=<?= $archivo['id'] ?>" target="_blank" class="text-blue-500">
                            <?= htmlspecialchars($archivo['nombre_archivo']) ?>
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= htmlspecialchars($archivo['fecha_subido']) ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= number_format($archivo['tamaño'] / 1024, 2) ?> KB
                    </td>
                    <td class="py-2 px-4 border-b">
                        <button onclick="togglePublic(<?= $archivo['id'] ?>)" class="bg-green-500 text-white px-2 py-1 rounded">
                            <?= $archivo['es_publico'] ? 'Hacer Privado' : 'Hacer Público' ?>
                        </button>
                        <button onclick="deleteFile(<?= $archivo['id'] ?>)" class="bg-red-500 text-white px-2 py-1 rounded">
                            Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    // Script para subir archivos vía AJAX
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('fileInput');
        if (fileInput.files.length === 0) {
            alert('Seleccione un archivo para subir.');
            return;
        }
        var formData = new FormData();
        formData.append('file', fileInput.files[0]);
        fetch('do_upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Función para hacer público/privado un archivo
    function togglePublic(id) {
        fetch('toggle_public.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
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

    // Función para eliminar un archivo
    function deleteFile(id) {
        if (!confirm('¿Está seguro de que desea eliminar este archivo?')) return;
        fetch('delete_file.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
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
</body>
</html>