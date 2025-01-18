<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception('ID de archivo inválido.');
    }

    $id_archivo = intval($data['id']);
    $db = getDbConnection();

    // Verificar si ya es favorito
    $stmt = $db->prepare("SELECT * FROM favoritos WHERE archivo_id = ? AND usuario_id = ?");
    $stmt->execute([$id_archivo, $USUARIO_ID]);
    $favorito = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($favorito) {
        // Quitar de favoritos
        $stmt = $db->prepare("DELETE FROM favoritos WHERE archivo_id = ? AND usuario_id = ?");
        $stmt->execute([$id_archivo, $USUARIO_ID]);
        $mensaje = 'Archivo quitado de favoritos.';
    } else {
        // Marcar como favorito
        $fecha_agregado = date('Y-m-d H:i:s');
        $stmt = $db->prepare("INSERT INTO favoritos (archivo_id, usuario_id, fecha_agregado) VALUES (?, ?, ?)");
        $stmt->execute([$id_archivo, $USUARIO_ID, $fecha_agregado]);
        $mensaje = 'Archivo marcado como favorito.';
    }

    echo json_encode(['message' => $mensaje]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}