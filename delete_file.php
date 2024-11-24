<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

// Obtener datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$id_archivo = isset($data['id']) ? intval($data['id']) : 0;

if (!$id_archivo) {
    echo json_encode(['message' => 'ID de archivo inválido.']);
    exit;
}

$db = getDbConnection();

// Verificar que el archivo pertenece al usuario
$stmt = $db->prepare("SELECT * FROM archivos WHERE id = ? AND id_usuario = ?");
$stmt->execute([$id_archivo, $USUARIO_ID]);
$archivo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$archivo) {
    echo json_encode(['message' => 'No tiene permiso para eliminar este archivo.']);
    exit;
}

// Eliminar el archivo físico
$filePath = DIR_UPLOAD . $archivo['nombre_archivo_guardado'];
if (file_exists($filePath)) {
    unlink($filePath);
}

// Eliminar registro de la base de datos
$stmt = $db->prepare("DELETE FROM archivos WHERE id = ?");
$stmt->execute([$id_archivo]);

// Registrar acción en el log
$stmt = $db->prepare("INSERT INTO archivos_log_general (id_usuario, id_archivo, accion, fecha_hora) VALUES (?, ?, 'eliminado', NOW())");
$stmt->execute([$USUARIO_ID, $id_archivo]);

echo json_encode(['message' => 'Archivo eliminado exitosamente.']);