<?php
header('Content-Type: application/json');
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $db = getDbConnection();

    $stmt = $db->prepare("
        SELECT id, username, nombre, apellidos 
        FROM usuarios 
        WHERE (username LIKE ? OR nombre LIKE ? OR apellidos LIKE ?)
    ");
    $likeSearch = "%$search%";
    $stmt->execute([$likeSearch, $likeSearch, $likeSearch]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($usuarios);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al buscar usuarios.']);
}