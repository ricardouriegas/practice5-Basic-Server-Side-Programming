<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

$db = getDbConnection();

// Obtener archivos favoritos del usuario
$stmt = $db->prepare("
    SELECT a.*, u.username, u.nombre, u.apellidos
    FROM favoritos f
    JOIN archivos a ON f.archivo_id = a.id
    JOIN usuarios u ON a.usuario_subio_id = u.id
    WHERE f.usuario_id = ?
      AND (a.es_publico = 1 OR a.usuario_subio_id = ?)
    ORDER BY f.fecha_agregado DESC
");
$stmt->execute([$USUARIO_ID, $USUARIO_ID]);
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tituloPagina = "Archivos Favoritos";
require APP_PATH . "views/archivos_favoritos.view.php";