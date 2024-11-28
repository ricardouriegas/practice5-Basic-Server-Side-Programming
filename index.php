<?php

require_once "config.php";
require APP_PATH . "sesion_requerida.php";

// Obtener el objeto DateTime actual
$hoy = new DateTime("now");

require APP_PATH . "data_access/db.php";

$tituloPagina = "Mis Archivos";

$db = getDbConnection();

// Obtener el año y mes actual o desde parámetros GET
$year = isset($_GET['year']) ? intval($_GET['year']) : $hoy->format("Y");
$month = isset($_GET['month']) ? intval($_GET['month']) : $hoy->format("m");

// Obtener archivos del usuario filtrados por año y mes
$stmt = $db->prepare("
    SELECT * 
    FROM archivos 
    WHERE usuario_subio_id = ? 
      AND YEAR(fecha_subido) = ? 
      AND MONTH(fecha_subido) = ?
");
$stmt->execute([$USUARIO_ID, $year, $month]);
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar la vista
require APP_PATH . "views/index.view.php";
