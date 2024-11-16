<?php
// admin/do_admin_action.php
header('Content-Type: application/json');
require "../config.php";
require_once APP_PATH . "sesion_requerida.php";

if (!$USUARIO_ES_ADMIN) {
    echo json_encode(["error" => "Acceso denegado"]);
    exit();
}

require_once APP_PATH . "data_access/db.php";

try {
    $db = getDbConnection();
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);

    switch ($action) {
        case 'toggleAdmin':
            $stmt = $db->prepare("UPDATE usuarios SET es_admin = NOT es_admin WHERE id = ?");
            $stmt->execute([$userId]);
            break;
            
        case 'resetPassword':
            $salt = strtoupper(bin2hex(random_bytes(32)));
            $defaultPass = "Welcome123";
            $passwordEncrypted = strtoupper(hash("sha512", $defaultPass . $salt));
            
            $stmt = $db->prepare("UPDATE usuarios SET password_encrypted = ?, password_salt = ? WHERE id = ?");
            $stmt->execute([$passwordEncrypted, $salt, $userId]);
            break;
            
        case 'toggleActive':
            $stmt = $db->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
            $stmt->execute([$userId]);
            break;
    }
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al ejecutar la acción"]);
}