<?php

require "config.php";
require_once APP_PATH . "session.php";

$tituloPagina = "Registro de Usuario";

// Incluimos la vista del registro
require APP_PATH . "views/registro.view.php";