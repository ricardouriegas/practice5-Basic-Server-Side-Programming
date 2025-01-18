<div style="text-align: right;" class="top-info">
    <span>Bienvenido(a) </span>
    <strong><?= $USUARIO_AUTENTICADO ? $USUARIO_NOMBRE_COMPLETO : "USUARIO" ?></strong>
    <span>|</span>
    <?php if ($USUARIO_AUTENTICADO){ ?>
        <span><a href="<?=APP_ROOT?>logout.php" class="btn">Cerrar Sesion</a></span>
        <span><a href="<?= APP_ROOT ?>actualizar_perfil.php"class="btn">Actualizar Perfil</a></span>
        <span><a href="<?= APP_ROOT ?>cambiar_password.php" class="btn">Actualizar Contraseña</a></span>
    <?php }else{ ?>
        <!-- link a iniciar sesion -->
        <a href="<?= APP_ROOT ?>login.php" class="btn">Iniciar Sesión</a>
    <?php }?>
</div>