<?php require_once APP_PATH . "session.php"; ?>

<div class="topnav">
    <?php if ($USUARIO_AUTENTICADO): ?>
        <a href="<?=APP_ROOT?>">Home</a>
        <a href="<?=APP_ROOT?>enviar_datos_con_form.php">Enviar Datos<br />con form</a>
        <a href="<?=APP_ROOT?>enviar_datos_con_ajax.php">Enviar Datos<br /> con AJAX</a>
        <a href="#" style="float:right">Link</a>
    <?php else: ?>
        <a href="<?=$APP_ROOT . "login.php"?>">Login</a>
    <?php endif; ?>
    <?php
    // por alguna razon no me jala en el session.php saber si es  admin el usuario, por lo que mejor hago una consulta a la BD
        require APP_PATH . "data_access/db.php";
        
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$USUARIO_ID]);
        $usuario = $stmt->fetch();
        $es_admin = $usuario["es_admin"];

        if ($es_admin){
            $_SESSION["Usuario_EsAdmin"] = 1;
    ?>
        <a href="<?=APP_ROOT?>admin_usuarios.php">Administrar Usuarios</a>
    <?php }?>

</div>
