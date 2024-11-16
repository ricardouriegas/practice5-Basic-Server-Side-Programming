<?php

require "config.php";
require_once APP_PATH . "session.php";

$tituloPagina = "Actualizar Perfil";

// Obtener datos del usuario actual
require_once APP_PATH . "data_access/db.php";

$username = $_SESSION['username'];

$db = getDbConnection();
$stmt = $db->prepare("SELECT nombre, apellidos, genero, fecha_nacimiento FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

require APP_PATH . "views/actualizar_perfil.view.php";