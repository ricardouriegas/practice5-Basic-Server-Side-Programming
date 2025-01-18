<?php require_once APP_PATH . "session.php"; ?>

<div class="topnav">
    <?php if ($USUARIO_AUTENTICADO): ?>
        <a href="<?=APP_ROOT?>">Home</a>
        <!-- <a href="<?=APP_ROOT?>enviar_datos_con_form.php">Enviar Datos<br />con form</a> -->
        <!-- <a href="<?=APP_ROOT?>enviar_datos_con_ajax.php">Enviar Datos<br /> con AJAX</a> -->
        <a href="<?=APP_ROOT?>archivos_usuario.php">Mis Archivos</a>
        <a href="<?=APP_ROOT?>archivos_favoritos.php">Archivos Favoritos</a>
        <a href="<?=APP_ROOT?>buscar_usuarios.php">Buscar Usuarios</a>
        <a href="#" id="help-link" style="float:right">Ayuda</a>
    <?php else: ?>
        <a href="<?=$APP_ROOT . "login.php"?>">Login</a>
    <?php endif; ?>
    <?php
        // por alguna razon no me jala en el session.php saber si es  admin el usuario, por lo que mejor hago una consulta a la BD
        require_once APP_PATH . "data_access/db.php";
        
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

    
<!--Content before waves-->
<div class="inner-header flex">
<!--Just the logo.. Don't mind this-->


</div>

<!--Waves Container-->
<div>
<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
<defs>
<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
</defs>
<g class="parallax">
<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
</g>
</svg>
</div>
<!--Waves end-->

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('help-link').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Ayuda',
            text: 'Para contactar a los desarrolladores, por favor envíe un correo a soporte@ejemplo.com o llame al 123-456-7890.',
            icon: 'info',
            confirmButtonText: 'Cerrar'
        });
    });
</script>
