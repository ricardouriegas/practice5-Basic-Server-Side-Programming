<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Tipos de archivos permitidos
    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedTypes)) {
        // Generar nombre aleatorio para el archivo
        $randomName = bin2hex(random_bytes(16)) . '.' . $fileExt;
        $uploadPath = DIR_UPLOAD . $randomName;

        // Mover el archivo a la carpeta de subida
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Insertar registro en la tabla 'archivos'
            $db = getDbConnection();
            $stmt = $db->prepare("INSERT INTO archivos (id_usuario, nombre_archivo_original, nombre_archivo_guardado, tamanio_kb, fecha_subida, es_publico, cant_descargas) VALUES (?, ?, ?, ?, NOW(), 0, 0)");
            $tamanio_kb = round($file['size'] / 1024, 2);
            $stmt->execute([$USUARIO_ID, $file['name'], $randomName, $tamanio_kb]);

            // Obtener el ID del archivo insertado
            $id_archivo = $db->lastInsertId();

            // Registrar acción en 'archivos_log_general'
            $stmt = $db->prepare("INSERT INTO archivos_log_general (id_usuario, id_archivo, accion, fecha_hora) VALUES (?, ?, 'subido', NOW())");
            $stmt->execute([$USUARIO_ID, $id_archivo]);

            echo "Archivo subido exitosamente.";
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Tipo de archivo no permitido.";
    }
} else {
    echo "No se ha recibido ningún archivo.";
}