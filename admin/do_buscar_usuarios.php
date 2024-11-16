<?php
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
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    
    $query = "SELECT id, username, nombre, apellidos, es_admin, activo 
              FROM usuarios 
              WHERE (username LIKE ? OR nombre LIKE ? OR apellidos LIKE ?)
              ORDER BY fecha_hora_registro DESC 
              LIMIT 100";
    
    $searchTerm = "%$search%";
    $stmt = $db->prepare($query);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo json_encode(["error" => "Error al buscar usuarios"]);
}