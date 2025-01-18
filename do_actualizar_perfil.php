<?php

header("Content-Type: application/json");
ob_start();

require "config.php";
require_once APP_PATH . "session.php";
session_start();

// Verificar si el usuario ha iniciado sesión
if (!$USUARIO_AUTENTICADO) {
    echo json_encode(["error" => "No has iniciado sesión"]);
    exit();
}

require_once APP_PATH . "data_access/db.php";

$errores = [];

$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
$genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
$fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_STRING);

// Validaciones opcionales
if ($fecha_nacimiento && !DateTime::createFromFormat('Y-m-d', $fecha_nacimiento)) {
    $errores[] = "La fecha de nacimiento es inválida";
}

if ($errores) {
    echo json_encode(["error" => implode(", ", $errores)]);
    exit();
}

try {
    $db = getDbConnection();

    if ($nombre !== null && $nombre !== '') {
        $stmt = $db->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
        $stmt->execute([$nombre, $USUARIO_ID]);
    }

    if ($apellidos !== null && $apellidos !== '') {
        $stmt = $db->prepare("UPDATE usuarios SET apellidos = ? WHERE id = ?");
        $stmt->execute([$apellidos, $USUARIO_ID]);
    }

    if ($genero !== null && $genero !== '') {
        $stmt = $db->prepare("UPDATE usuarios SET genero = ? WHERE id = ?");
        $stmt->execute([$genero, $USUARIO_ID]);
    }

    if ($fecha_nacimiento !== null && $fecha_nacimiento !== '') {
        $stmt = $db->prepare("UPDATE usuarios SET fecha_nacimiento = ? WHERE id = ?");
        $stmt->execute([$fecha_nacimiento, $USUARIO_ID]);
    }

    echo json_encode(["mensaje" => "Datos actualizados correctamente"]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al actualizar los datos: " . $e->getMessage()]);
}