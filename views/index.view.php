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
</head>
<body class="bg-gray-100 text-gray-800">
    <?php require APP_PATH . "html_parts/info_usuario.php"; ?>
    <?php require APP_PATH . "html_parts/menu.php"; ?>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($tituloPagina) ?></h2>

        <!-- Formulario de subida de archivos -->
        <form id="uploadForm" enctype="multipart/form-data" class="mb-4 p-4 bg-white shadow-md rounded">
            <!-- Campo para seleccionar el archivo -->
            <input type="file" name="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.gif" class="mb-2 p-2 border rounded" placeholder="para archivos" required />
            <br>
            <!-- Nuevo campo para la descripción (opcional) -->
            <textarea name="descripcion" id="descripcionInput" placeholder="Descripción (opcional) (máximo 1024)" class="mb-2 p-2 border rounded w-full"></textarea>
            
            <!-- colocar a la derecha -->
            <label id="charCount" class="block mb-2 text-right">0/1024</label>

            <!-- Botón de subir -->
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
                        <?= htmlspecialchars($archivo['descripcion'] ?? '') ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= htmlspecialchars($archivo['fecha_subido']) ?>
                    </td>
                    <td class="py-2 px-4 border-b">
                        <?= number_format($archivo['tamaño'] / 1024, 2) ?> KB
                    </td>
                    <td class="py-2 px-4 border-b">
                        <button onclick="togglePublic(<?= $archivo['id'] ?>)" class="bg-blue-500 text-white px-2 py-1 rounded" <?= $estaBorrado ? 'disabled' : '' ?>>
                            <?= $archivo['es_publico'] ? 'Hacer Privado' : 'Hacer Público' ?>
                        </button>
                        <button onclick="deleteFile(<?= $archivo['id'] ?>)" class="bg-red-500 text-white px-2 py-1 rounded" <?= $estaBorrado ? 'disabled' : '' ?>>
                            Eliminar
                        </button>
                        <button onclick="toggleFavorite(<?= $archivo['id'] ?>)" class="bg-yellow-500 text-white px-2 py-1 rounded" <?= $estaBorrado ? 'disabled' : '' ?>>
                            <?= esFavorito($archivo['id'], $USUARIO_ID) ? 'Quitar Favorito' : 'Marcar Favorito' ?>
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
    
    </script>
</body>
</html>