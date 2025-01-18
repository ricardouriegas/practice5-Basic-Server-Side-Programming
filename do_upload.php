<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

// Habilitar reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Verificar si hubo error en la carga
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "Error en la carga del archivo: " . $file['error'];
            exit;
        }

        // Tipos de archivos permitidos
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($fileExt, $allowedTypes)) {
            // Generar nombre aleatorio para el archivo
            $maxExtensionLength = strlen($fileExt);
            $maxRandomNameLength = 64 - 1 - $maxExtensionLength;
            $bytesNeeded = floor($maxRandomNameLength / 2);

            $randomName = bin2hex(random_bytes($bytesNeeded));
            $nombre_archivo_guardado = $randomName . '.' . $fileExt;
            $uploadPath = DIR_UPLOAD . $nombre_archivo_guardado;

            // Mover el archivo a la carpeta de subida
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Calcular hash SHA-256 del archivo
                $hash_sha256 = hash_file('sha256', $uploadPath);

                // Obtener tamaño del archivo en bytes
                $tamaño = filesize($uploadPath);

                // Fecha y hora actual
                $fecha_subido = date('Y-m-d H:i:s');

                // Obtener la dirección IP del usuario
                $ip_usuario = $_SERVER['REMOTE_ADDR'];

                // Obtener la descripción si está presente
                $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;

                // Insertar registro en la tabla 'archivos'
                $db = getDbConnection();

                // Establecer el modo de error de PDO para lanzar excepciones
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $db->prepare("INSERT INTO archivos (
                    descripcion,
                    nombre_archivo,
                    extension,
                    nombre_archivo_guardado,
                    `tamaño`,
                    hash_sha256,
                    fecha_subido,
                    usuario_subio_id,
                    cant_descargas,
                    es_publico
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0)");

                $stmt->execute([
                    $descripcion, // Aquí se utiliza la descripción proporcionada por el usuario
                    $file['name'],
                    $fileExt,
                    $nombre_archivo_guardado,
                    $tamaño,
                    $hash_sha256,
                    $fecha_subido,
                    $USUARIO_ID
                ]);

                // Obtener el ID del archivo insertado
                $archivo_id = $db->lastInsertId();

                // Registrar acción en 'archivos_log_general'
                $stmt = $db->prepare("INSERT INTO archivos_log_general (
                    archivo_id,
                    usuario_id,
                    fecha_hora,
                    accion_realizada,
                    ip_realiza_operacion
                ) VALUES (?, ?, ?, ?, ?)");

                $accion_realizada = 'subido';

                $stmt->execute([
                    $archivo_id,
                    $USUARIO_ID,
                    $fecha_subido,
                    $accion_realizada,
                    $ip_usuario
                ]);

                echo "Archivo subido exitosamente.";
            } else {
                echo "Error al mover el archivo.";
                error_log("Error al mover el archivo de " . $file['tmp_name'] . " a " . $uploadPath);
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    } else {
        echo "No se ha recibido ningún archivo.";
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
    error_log("Error de PDO: " . $e->getMessage());
}