<?php
require "config.php";
require APP_PATH . "sesion_requerida.php";
require APP_PATH . "data_access/db.php";

$usuario_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($usuario_id <= 0) {
    header('Location: ' . APP_ROOT);
    exit();
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

$db = getDbConnection();

// Obtener información del usuario
$stmt = $db->prepare("SELECT username, nombre, apellidos FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Obtener archivos públicos del usuario
$stmt = $db->prepare("
    SELECT a.* 
    FROM archivos a
    WHERE a.usuario_subio_id = ? 
      AND a.es_publico = 1
      AND YEAR(a.fecha_subido) = ?
      AND MONTH(a.fecha_subido) = ?
");
$stmt->execute([$usuario_id, $year, $month]);
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tituloPagina = "Archivos de " . $usuario['nombre'];
require APP_PATH . "views/archivos_usuario.view.php";