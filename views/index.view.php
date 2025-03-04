<?php
function esFavorito($archivo_id, $usuario_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM favoritos WHERE archivo_id = ? AND usuario_id = ?");
    $stmt->execute([$archivo_id, $usuario_id]);
    return $stmt->fetch() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link href="<?= APP_ROOT ?>css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    
    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($tituloPagina) ?></h2>

        <h3 class="text-2xl font-bold mb-4">Subir Archivo</h3>
        <!-- Actualización del formulario de subida de archivos -->
        <form id="uploadForm" enctype="multipart/form-data" class="mb-4 p-4 bg-white shadow-md rounded space-y-4">
            <div>
                <label for="fileInput" class="block mb-1 font-semibold">Archivo:</label>
                <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.gif" class="w-full p-2 border rounded" required />
            </div>
            <div>
                <label for="descripcionInput" class="block mb-1 font-semibold">Descripción (opcional):</label>
                <textarea name="descripcion" id="descripcionInput" placeholder="Máximo 1024 caracteres" class="w-full p-2 border rounded h-40"></textarea>
                <label id="charCount" class="block mt-1 text-right text-sm text-gray-600">0/1024</label>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center">
                <i class="fas fa-upload mr-2"></i>Subir Archivo
            </button>
        </form>

        <!-- Filtros de año y mes -->
        <h3 class="text-2xl font-bold mb-4">Filtrar por Fecha</h3>
        <form method="get" action="" class="mb-4 p-4 bg-white shadow-md rounded flex items-center space-x-4">

            <div class="flex items-center">
                <label for="year" class="mr-2 font-semibold">Año:</label>
                <select name="year" id="year" class="p-2 border rounded">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="flex items-center">
                <label for="month" class="mr-2 font-semibold">Mes:</label>
                <select name="month" id="month" class="p-2 border rounded">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                <i class="fas fa-search mr-2"></i>
                Filtrar
            </button>
        </form>

        <!-- Tabla de archivos -->
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-2 px-4 border-b">Nombre del Archivo</th>
                    <th class="py-2 px-4 border-b">Descripción</th>
                    <th class="py-2 px-4 border-b">Fecha de Subida</th>
                    <th class="py-2 px-4 border-b">Tamaño (KB)</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <!-- center -->
            <tbody class="bg-white divide-y divide-gray-200 text-center">
                <?php foreach ($archivos as $archivo): ?>
                <?php
                    $estaBorrado = !is_null($archivo['fecha_borrado']);
                    $claseFila = $estaBorrado ? 'bg-gray-300 text-gray-500' : '';
                    $descripcion = htmlspecialchars($archivo['descripcion'] ?? '');
                    $maxLength = 100;
                    $isLong = strlen($descripcion) > $maxLength;
                    $shortDesc = $isLong ? substr($descripcion, 0, $maxLength) . '...' : $descripcion;
                ?>
                <tr class="<?= $claseFila ?>">
                    <td class="py-2 px-4 border-b">
                        <?php if (!$estaBorrado): ?>
                            <a href="archivo.php?id=<?= $archivo['id'] ?>" target="_blank" class="text-blue-500">
                                <?= htmlspecialchars($archivo['nombre_archivo']) ?>
                            </a>
                        <?php else: ?>
                            <?= htmlspecialchars($archivo['nombre_archivo']) ?>
                        <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= $shortDesc ?>
                        <?php if ($isLong): ?>
                            <a href="#" onclick="mostrarDescripcion('<?= addslashes($descripcion) ?>'); return false;" class="text-blue-500 hover:underline ml-2">más</a>
                        <?php endif; ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= htmlspecialchars($archivo['fecha_subido']) ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= number_format($archivo['tamaño'] / 1024, 2) ?> KB
                    </td>
                    <td class="py-2 px-4 border-b">
                        <!-- Agrupar botones de acción en un contenedor flexible -->
                        <div class="flex justify-center space-x-2">
                            <button onclick="togglePublic(<?= $archivo['id'] ?>)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-700 <?= $estaBorrado ? 'disabled' : '' ?>">
                                <i class="fas fa-globe mr-2"></i><?= $archivo['es_publico'] ? 'Hacer Privado' : 'Hacer Público' ?>
                            </button>
                            <button onclick="toggleFavorite(<?= $archivo['id'] ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-700 <?= $estaBorrado ? 'disabled' : '' ?>">
                                <i class="fas fa-star mr-2"></i><?= esFavorito($archivo['id'], $USUARIO_ID) ? 'Quitar Favorito' : 'Marcar Favorito' ?>
                            </button>
                            <button onclick="deleteFile(<?= $archivo['id'] ?>)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700 <?= $estaBorrado ? 'disabled' : '' ?>">
                                <i class="fas fa-trash-alt mr-2"></i>Eliminar
                            </button>
                        </div>
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
    var descripcionInput = document.getElementById('descripcionInput');
    
    if (fileInput.files.length === 0) {
        Swal.fire('Error', 'Seleccione un archivo para subir.', 'error');
        return;
    }

    // Revisar extensión del archivo
    var allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(fileInput.value)) {
        Swal.fire('Error', 'El archivo no es válido. Solo se permiten PDF, JPG, JPEG, PNG y GIF.', 'error');
        return;
    }

    var fileName = fileInput.files[0].name;
    if (fileName.length > 256) {
        Swal.fire('Error', 'El nombre del archivo excede 256 caracteres.', 'error');
        return;
    }
    var desc = descripcionInput.value.trim();
    if (desc.length > 1024) {
        Swal.fire('Error', 'La descripción excede 1024 caracteres.', 'error');
        return;
    }
    const MAX_BIGINT = 9223372036854775807;
    if (fileInput.files[0].size > MAX_BIGINT) {
        Swal.fire('Error', 'El tamaño del archivo excede el límite permitido.', 'error');
        return;
    }

    var formData = new FormData();
    formData.append('file', fileInput.files[0]);

    if (descripcionInput.value.trim() !== '') {
        formData.append('descripcion', descripcionInput.value.trim());
    }

    fetch('do_upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result.startsWith("ERROR:")) {
            Swal.fire('Error', result, 'error');
        } else {
            Swal.fire('Éxito', result, 'success').then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al subir el archivo.', 'error');
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
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Error en la solicitud');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                Swal.fire('Error', data.error, 'error');
                return;
            }
            Swal.fire('Éxito', data.message, 'success').then(() => {
                location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', error.message, 'error');
        });
    }

    // Función para eliminar un archivo
    function deleteFile(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "¡No podrá revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete_file.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        return;
                    }
                    Swal.fire('Eliminado', data.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar el archivo.', 'error');
                });
            }
        });
    }

    function toggleFavorite(id) {
        fetch('toggle_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire('Error', data.error, 'error');
                return;
            }
            Swal.fire('Éxito', data.message, 'success').then(() => {
                location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al cambiar favorito.', 'error');
        });
    }

    var descripcionInput = document.getElementById('descripcionInput');
    descripcionInput.addEventListener('input', function() {
        let currentLength = descripcionInput.value.length;
        document.getElementById('charCount').textContent = currentLength + '/1024';
    });

    function mostrarDescripcion(desc) {
        Swal.fire({
            title: 'Descripción',
            text: desc,
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    }
    
    </script>
</body>
</html>