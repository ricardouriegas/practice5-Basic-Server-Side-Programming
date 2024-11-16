<?php

header("Content-Type: application/json");

require "config.php";
require_once APP_PATH . "session.php";
session_start();

// Verificar si el usuario ha iniciado sesión
if (!$USUARIO_AUTENTICADO) {
    echo json_encode(["error" => "No has iniciado sesión"]);
    exit();
}

require_once APP_PATH . "data_access/db.php";

ob_start();
$errores = [];


$username = $_SESSION['username'];

$passwordActual = filter_input(INPUT_POST, 'password_actual', FILTER_SANITIZE_STRING);
$nuevoPassword = filter_input(INPUT_POST, 'nuevo_password', FILTER_SANITIZE_STRING);
$confirmarPassword = filter_input(INPUT_POST, 'confirmar_password', FILTER_SANITIZE_STRING);

// Validaciones
if (!$passwordActual) {
    echo json_encode(["error" => "La contraseña actual es obligatoria"]);
    exit();
}
file_put_contents("/tmp/debug.log", "La contraseña actual es: $passwordActual\n", FILE_APPEND);

if (!$nuevoPassword) {
    echo json_encode(["error" => "La nueva contraseña es obligatoria"]);
    exit();
} else {
    if (strlen($nuevoPassword) < 8) {
        echo json_encode(["error" => "La nueva contraseña debe tener al menos 8 caracteres"]);
        file_put_contents("/tmp/debug.log", "La nueva contraseña debe tener al menos 8 caracteres\n", FILE_APPEND);
        exit();
    }
    if (!preg_match('/[A-Za-z]/', $nuevoPassword) || !preg_match('/[0-9]/', $nuevoPassword)) {
        echo json_encode(["error" => "La nueva contraseña debe contener letras y números"]);
        file_put_contents("/tmp/debug.log", "La nueva contraseña debe contener letras y números\n", FILE_APPEND);
        exit();
    }
    if ($nuevoPassword !== $confirmarPassword) {
        echo json_encode(["error" => "Las contraseñas no coinciden"]);
        file_put_contents("/tmp/debug.log", "Las contraseñas no coinciden\n", FILE_APPEND);
        exit();
    }
}

if ($errores) {
    echo json_encode(["error" => implode(", ", $errores)]);
    exit();
}

try {
    $db = getDbConnection();
    file_put_contents("/tmp/debug.log", "Conexión a la base de datos exitosa\n", FILE_APPEND);

    // Verificar contraseña actual
    $stmt = $db->prepare("SELECT password_encrypted, password_salt FROM usuarios WHERE id = $USUARIO_ID");
    $stmt->execute();
    $usuario = $stmt->fetch();
    file_put_contents("/tmp/debug.log", "Usuario: " . print_r($usuario, true) . "\n", FILE_APPEND);
    $passwordActualMasSalt = $passwordActual . $usuario['password_salt'];
    $passwordActualEncrypted = strtoupper(hash("sha512", $passwordActualMasSalt));
    
    if ($passwordActualEncrypted !== $usuario['password_encrypted']) {
        echo json_encode(["error" => "La contraseña actual es incorrecta"]);
        exit();
    }
    
    // And update the new password hashing to match registration:
    $salt = strtoupper(bin2hex(random_bytes(32)));
    $passwordEncrypted = strtoupper(hash("sha512", $nuevoPassword . $salt));
    
    $stmt = $db->prepare("UPDATE usuarios SET password_encrypted = ?, password_salt = ? WHERE id = ?");
    $stmt->execute([$passwordEncrypted, $salt, $USUARIO_ID]);

    echo json_encode(["mensaje" => "Contraseña actualizada correctamente"]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al conectar a la base de datos: " . $e->getMessage()]);
    exit();
}