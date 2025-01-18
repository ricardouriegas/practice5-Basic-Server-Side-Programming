<?php
// admin/do_admin_action.php
header('Content-Type: application/json');
require "../config.php";
require_once APP_PATH . "sesion_requerida.php";

// Verify admin access
if (!$USUARIO_ES_ADMIN) {
    echo json_encode([
        "error" => "Acceso denegado",
        "status" => "error",
        "hint" => "Se requieren privilegios de administrador."
    ]);
    exit();
}

try {
    // Validate parameters
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$userId || !$action) {
        throw new Exception("Parámetros inválidos");
    }

    // Prevent self-modification for certain actions
    if (($action == 'toggleActive' || $action == 'delete') && $userId == $USUARIO_ID) {
        throw new Exception("No puede modificarse a sí mismo");
    }

    require_once APP_PATH . "data_access/db.php";
    $db = getDbConnection();

    switch ($action) {
        case 'toggleAdmin':
            $stmt = $db->prepare("UPDATE usuarios SET es_admin = NOT es_admin WHERE id = ?");
            $mensaje = "Estado de administrador actualizado";
            break;
            
        case 'resetPassword':
            $salt = strtoupper(bin2hex(random_bytes(32)));
            $defaultPass = "Welcome123";
            $passwordEncrypted = strtoupper(hash("sha512", $defaultPass . $salt));
            
            $stmt = $db->prepare("UPDATE usuarios SET password_encrypted = ?, password_salt = ? WHERE id = ?");
            $stmt->execute([$passwordEncrypted, $salt, $userId]);
            echo json_encode([
                "success" => true,
                "mensaje" => "Contraseña restablecida a: $defaultPass",
                "status" => "success",
                "hint" => null
            ]);
            exit();
            
        case 'toggleActive':
            $stmt = $db->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
            $mensaje = "Estado activo actualizado";
            break;

        case 'delete':
            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
            $mensaje = "Usuario eliminado correctamente";
            break;
            
        default:
            throw new Exception("Acción no válida");
    }

    // Execute statement for non-password actions
    if ($action != 'resetPassword') {
        $stmt->execute([$userId]);
    }

    // Verify if user exists and was updated
    if ($stmt->rowCount() === 0) {
        throw new Exception("Usuario no encontrado");
    }

    echo json_encode([
        "success" => true,
        "mensaje" => $mensaje,
        "status" => "success",
        "hint" => null
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "error" => $e->getMessage(),
        "status" => "error",
        "hint" => "Verifique parámetros o contacte soporte."
    ]);
}