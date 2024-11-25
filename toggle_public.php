<?php
// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Establecer el encabezado de contenido
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
        throw new Exception('No tiene permiso para modificar este archivo.');
    }

    // Cambiar el estado de 'es_publico'
    $nuevoEstado = $archivo['es_publico'] ? 0 : 1;
    $stmt = $db->prepare("UPDATE archivos SET es_publico = ? WHERE id = ?");
    $stmt->execute([$nuevoEstado, $id_archivo]);

    // Registrar acción en el log
    $accion_realizada = $nuevoEstado ? 'hecho público' : 'hecho privado';
    $ip_usuario = $_SERVER['REMOTE_ADDR'];
    $fecha_hora = date('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO archivos_log_general (
        archivo_id,
        usuario_id,
        fecha_hora,
        accion_realizada,
        ip_realiza_operacion
    ) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $id_archivo,
        $USUARIO_ID,
        $fecha_hora,
        $accion_realizada,
        $ip_usuario
    ]);

    echo json_encode(['message' => 'El archivo ha sido ' . $accion_realizada . '.']);

} catch (Exception $e) {
    // Enviar código de respuesta HTTP y mensaje de error en formato JSON
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}