<?php

function getDbConnection() {
    try {
        $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
        exit();
    }
}