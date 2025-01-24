<?php
// Habilitar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Establecer encabezado de contenido JSON
header('Content-Type: application/json');

require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

try {
    // Obtener datos de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
  
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception('ID de archivo inválido.');
    }
    $id_archivo = intval($data['id']);
  
    $db = getDbConnection();
  
    // Verificar que el archivo pertenece al usuario
    $stmt = $db->prepare("SELECT * FROM archivos WHERE id = ? AND usuario_subio_id = ?");
    $stmt->execute([$id_archivo, $USUARIO_ID]);
    $archivo = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if (!$archivo) {
        http_response_code(403);
        echo json_encode(['error' => 'No tiene permiso para eliminar este archivo.']);
        exit;
    }
  
    // Eliminar el archivo físico
    $filePath = DIR_UPLOAD . $archivo['nombre_archivo_guardado'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
  
    // Marcar el archivo como eliminado y moverlo al usuario de papelera (id = 0)
    $fecha_borrado = date('Y-m-d H:i:s');
    $stmt = $db->prepare("UPDATE archivos SET usuario_subio_id = 0, fecha_borrado = ?, usuario_borro_id = ? WHERE id = ?");
    $stmt->execute([$fecha_borrado, $USUARIO_ID, $id_archivo]);
  
    // Registrar acción en el log
    $ip_usuario = $_SERVER['REMOTE_ADDR'];
    $stmt = $db->prepare("INSERT INTO archivos_log_general (archivo_id, usuario_id, accion_realizada, fecha_hora, ip_realiza_operacion) VALUES (?, ?, 'eliminado', NOW(), ?)");
    $stmt->execute([$id_archivo, $USUARIO_ID, $ip_usuario]);
  
    echo json_encode(['message' => 'Archivo eliminado exitosamente.']);
  
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
}