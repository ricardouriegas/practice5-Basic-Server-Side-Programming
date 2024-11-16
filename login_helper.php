<?php

/**
 * Realiza la autenticación del usuario por su username y el password.
 * Regresa false en autenticación fallida y regresa un assoc array con los
 * datos del usuario cuando la autenticación es correcta.
 */
function autentificar($username, $password) {

    if (!$username || !$password) {  // Validación básica de los parámetros
        return false;
    }

    $sqlCmd = 
        "SELECT id, username, password_encrypted, password_salt, " . 
        "    nombre, apellidos, es_admin, activo" . 
        "  FROM usuarios WHERE username = ? ORDER BY id DESC";
    
    $db = getDbConnection();  // obtenemos la conexión (PDO object)
    $stmt = $db->prepare($sqlCmd);  // Statement a ejecutar
    $sqlParams = [$username];  // Parámetros de la consulta
    $stmt->execute($sqlParams);  // Ejecutamos con los parámetros 
    $queryResult = $stmt->fetchAll();  // Todos los resultados del consulta

    // Si la consulta no regresó resultados o usuario no activo, return false / no autenticado
    if (!$queryResult || !$queryResult[0]["activo"]) {
        return false;
    }

    // registro del usuario
    $usuario = $queryResult[0];

    // Obtenemos el password cifrado del password que nos pasaron
    // en la función (que este esta en texto plano)
    $passwordMasSalt = $password . $usuario["password_salt"];
    $passwordEncrypted = strtoupper(hash("sha512", $passwordMasSalt));

    // verificación del password cifrado
    if ($usuario["password_encrypted"] != $passwordEncrypted) {
        return false;
    }

    // Se regresan los datos del usuario
    return [
        "id" => $usuario["id"],
        "username" => $usuario["username"],
        "nombre" => $usuario["nombre"],
        "apellidos" => $usuario["apellidos"],
        "esAdmin" => $usuario["es_admin"]
    ];
}
