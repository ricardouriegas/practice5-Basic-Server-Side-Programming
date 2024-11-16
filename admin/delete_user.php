<?php
// admin/delete_user.php

header('Content-Type: application/json');
require "../config.php";
require_once APP_PATH . "sesion_requerida.php";

// Verify admin access
if (!$USUARIO_ES_ADMIN) {
    echo json_encode(["error" => "Acceso denegado"]);
    exit();
}

try {
    // Validate user ID
    $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$userId) {
        throw new Exception("ID de usuario inválido");
    }

    // Prevent self-deletion
    if ($userId == $USUARIO_ID) {
        throw new Exception("No puede eliminarse a sí mismo");
    }

    // Get database connection
    require_once APP_PATH . "data_access/db.php";
    $db = getDbConnection();

    // Soft delete - set activo = 0
    $stmt = $db->prepare("UPDATE usuarios SET activo = 0 WHERE id = ?");
    $stmt->execute([$userId]);

    // Verify if user exists and was updated
    if ($stmt->rowCount() === 0) {
        throw new Exception("Usuario no encontrado");
    }

    // Return success response
    echo json_encode([
        "success" => true,
        "mensaje" => "Usuario eliminado correctamente"
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}