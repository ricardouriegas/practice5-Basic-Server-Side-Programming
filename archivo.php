<?php
require "config.php";
require_once APP_PATH . "session.php";
require APP_PATH . "data_access/db.php";

$id_archivo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id_archivo) {
    echo "ID de archivo inválido.";
    exit;
}

$db = getDbConnection();

// Obtener información del archivo
$stmt = $db->prepare("SELECT * FROM archivos WHERE id = ?");
$stmt->execute([$id_archivo]);
$archivo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    echo "Archivo no encontrado.";
    exit;
}

// Verificar permisos
if (!$archivo['es_publico'] && (!$USUARIO_AUTENTICADO || $archivo['id_usuario'] != $USUARIO_ID)) {
    echo "No tiene permiso para acceder a este archivo.";
    exit;
}

// Incrementar contador de descargas
$stmt = $db->prepare("UPDATE archivos SET cant_descargas = cant_descargas + 1 WHERE id = ?");
$stmt->execute([$id_archivo]);

// Registrar acción en el log
$stmt = $db->prepare("INSERT INTO archivos_log_general (id_usuario, id_archivo, accion, fecha_hora) VALUES (?, ?, 'descargado', NOW())");
$stmt->execute([$USUARIO_ID ?: null, $id_archivo]);

// Mostrar el archivo
$filePath = DIR_UPLOAD . $archivo['nombre_archivo_guardado'];

if (!file_exists($filePath)) {
    echo "El archivo no existe.";
    exit;
}

$mimeType = mime_content_type($filePath);

header('Content-Type: ' . $mimeType);
header('Content-Disposition: inline; filename="' . $archivo['nombre_archivo_original'] . '"');
readfile($filePath);
exit;