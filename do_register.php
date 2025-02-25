<?php

require "config.php";
require APP_PATH . "data_access/db.php";

// Indicamos que la respuesta es JSON
header("Content-Type: application/json");

$errores = [];

// Obtenemos y validamos los datos enviados
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
$nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_POST, "apellidos", FILTER_SANITIZE_STRING);
$genero = filter_input(INPUT_POST, "genero", FILTER_SANITIZE_STRING);
$fecha_nacimiento = filter_input(INPUT_POST, "fecha_nacimiento", FILTER_SANITIZE_STRING);

// Validar el username
if (!$username || !strlen(trim($username))) {
    $errores[] = "El nombre de usuario es obligatorio";
} else {
    $username = strtolower($username);
    if (!preg_match('/^[a-z0-9_]+$/', $username)) {
        $errores[] = "El nombre de usuario solo puede contener letras, números y guion bajo";
    }
}

// Validar el nombre
if (!$nombre || !strlen(trim($nombre))) {
    $errores[] = "El nombre es obligatorio";
} else {
    if (strlen($nombre) < 2) {
        $errores[] = "El nombre debe tener al menos 2 caracteres";
    }
    if (!preg_match('/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios";
    }
}

// Validar apellidos
if (!$apellidos || !strlen(trim($apellidos))) {
    $errores[] = "Los apellidos son obligatorios";
} else {
    if (strlen($apellidos) < 2) {
        $errores[] = "Los apellidos deben tener al menos 2 caracteres";
    }
    if (!preg_match('/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/', $apellidos)) {
        $errores[] = "Los apellidos solo pueden contener letras y espacios";
    }
}

// Validar género
if (!$genero || !in_array($genero, ['M', 'F', 'O'])) {
    $errores[] = "El género es obligatorio y debe ser 'M', 'F' o 'O'";
}

// Validar fecha de nacimiento
if (!$fecha_nacimiento || !DateTime::createFromFormat('Y-m-d', $fecha_nacimiento)) {
    $errores[] = "La fecha de nacimiento es obligatoria y debe tener el formato AAAA-MM-DD";
} else {
    $fecha_nac = new DateTime($fecha_nacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nac)->y;
    
    if ($edad < 15) {
        $errores[] = "Debes ser mayor de 15 años para registrarte";
    }
    if ($edad > 120) {
        $errores[] = "La fecha de nacimiento no es válida";
    }
    if ($fecha_nac > $hoy) {
        $errores[] = "La fecha de nacimiento no puede ser futura";
    }
}

// Validar el password
if (!$password || !strlen(trim($password))) {
    $errores[] = "La contraseña es obligatoria";
} else {
    if (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres";
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errores[] = "La contraseña debe contener letras y números";
    }
}

if ($errores) {
    echo json_encode(["error" => implode("<br>", $errores)]);
    exit();
}

try {
    $db = getDbConnection();

    // Verificamos si el usuario ya existe
    $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        echo json_encode(["error" => "El nombre de usuario ya existe"]);
        exit();
    }

    // Generamos el salt y encriptamos la contraseña
    $tamañoBytes = 32;
    $bytesRandom = random_bytes($tamañoBytes);
    $salt = strtoupper(bin2hex($bytesRandom));
    $passwordMasSalt = $password . $salt;
    $passwordEncrypted = strtoupper(hash("sha512", $passwordMasSalt));

    // Establecemos los valores adicionales
    $fecha_hora_registro = date('Y-m-d H:i:s');
    $activo = 1;
    $es_admin = 0;

    // Insertamos el nuevo usuario
    $stmt = $db->prepare("
        INSERT INTO usuarios 
            (username, password_encrypted, password_salt, nombre, apellidos, genero, fecha_nacimiento, fecha_hora_registro, activo, es_admin)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $username,
        $passwordEncrypted,
        $salt,
        $nombre,
        $apellidos,
        $genero,
        $fecha_nacimiento,
        $fecha_hora_registro,
        $activo,
        $es_admin
    ]);

    echo json_encode(["mensaje" => "Registro exitoso"]);
} catch (Exception $e) {
    echo json_encode(["error" => "Error al registrar el usuario"]);
}