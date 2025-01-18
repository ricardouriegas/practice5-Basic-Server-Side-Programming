<?php
require "config.php";
require_once APP_PATH . "sesion_requerida.php";

// Verificar si el usuario es admin
if (!$USUARIO_ES_ADMIN) {
    header("Location: " . APP_ROOT);
    exit();
}

$tituloPagina = "Administración de Usuarios";
require APP_PATH . "views/admin_usuarios.view.php";