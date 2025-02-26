<?php

require "config.php";
require_once APP_PATH . "sesion_requerida.php";

// Fetch current user data
require_once APP_PATH . "data_access/db.php";
$db = getDbConnection();
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$USUARIO_ID]);
$userData = $stmt->fetch();

$tituloPagina = "Actualizar Perfil";

require APP_PATH . "views/actualizar_perfil.view.php";