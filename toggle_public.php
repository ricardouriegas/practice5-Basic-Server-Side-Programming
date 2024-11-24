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
    echo json_encode(['message' => 'No tiene permiso para modificar este archivo.']);
    exit;
}

// Cambiar el estado de 'es_publico'
$nuevoEstado = $archivo['es_publico'] ? 0 : 1;
$stmt = $db->prepare("UPDATE archivos SET es_publico = ? WHERE id = ?");
$stmt->execute([$nuevoEstado, $id_archivo]);

// Registrar acción en el log
$accion = $nuevoEstado ? 'hecho público' : 'hecho privado';
$stmt = $db->prepare("INSERT INTO archivos_log_general (id_usuario, id_archivo, accion, fecha_hora) VALUES (?, ?, ?, NOW())");
$stmt->execute([$USUARIO_ID, $id_archivo, $accion]);

echo json_encode(['message' => 'El archivo ha sido ' . ($nuevoEstado ? 'hecho público' : 'hecho privado') . '.']);