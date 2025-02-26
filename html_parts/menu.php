<?php require_once APP_PATH . "session.php"; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="topnav">
    <?php if ($USUARIO_AUTENTICADO): ?>
        <!-- <a href="<?=APP_ROOT?>">Inicio</a> -->
        <a href="<?=APP_ROOT?>archivos_usuario.php"><i class="fas fa-folder mr-2"></i> Mis Archivos</a>
        <a href="<?=APP_ROOT?>buscar_usuarios.php"><i class="fas fa-search mr-2"></i> Buscar Usuarios</a>
        <a href="<?=APP_ROOT?>archivos_favoritos.php"><i class="fas fa-star mr-2"></i> Archivos Favoritos</a>

        <!-- icono de perfil para ir a las opciones de perfil -->
        <a href="<?=APP_ROOT?>account.php" style="float:right"><i class="fas fa-user mr-2"></i> Cuenta</a>
        <!-- <a href="#" id="toggle-narrador" style="float:right">Activar Narrador</a> -->
    <?php else: ?>
        <a href="<?=$APP_ROOT . "login.php"?>"><i class="fas fa-sign-in-alt mr-2"></i> Login</a>
    <?php endif; ?>
    <?php
        require_once APP_PATH . "data_access/db.php";
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$USUARIO_ID]);
        $usuario = $stmt->fetch();
        $es_admin = $usuario["es_admin"];
        if ($es_admin){
            $_SESSION["Usuario_EsAdmin"] = 1;
    ?>
        <a href="<?=APP_ROOT?>admin_usuarios.php"><i class="fas fa-user-cog mr-2"></i> Administrar Usuarios</a>
    <?php }?>
        
    <a href="#" id="toggle-narrador" style="float:right"><i class="fas fa-microphone mr-2"></i> Activar Narrador</a>
    <a href="#" id="help-link"  class="float:right">
            <i class="fas fa-info-circle mr-2"></i> Contacto
        </a> 
    <div class="inner-header flex"></div>

    <div>
    <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
    <defs>
    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18
    58 18 88 18 v44h-352z" />
    </defs>
    <g class="parallax">
    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
    </g>
    </svg>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const synth = window.speechSynthesis;
    const utterance = new SpeechSynthesisUtterance();
    let narradorActivo = localStorage.getItem('narradorActivo') === 'true';

    utterance.lang = 'es-ES';
    utterance.rate = 1;
    utterance.pitch = 1;

    const readText = (text) => {
        if (synth.speaking) synth.cancel();
        utterance.text = text;
        synth.speak(utterance);
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('body *').forEach((element) => {
            element.addEventListener('mouseover', (e) => {
                const text = e.target.innerText.trim();
                if (narradorActivo && text) readText(text);
            });
        });

        // Add event listener to input elements
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('mouseover', (e) => {
                if (narradorActivo) {
                    readText('Campo de entrada. ' + (e.target.placeholder || ''));
                }
            });

            input.addEventListener('input', (e) => {
                if (narradorActivo) {
                    readText('Escribiendo: ' + e.target.value);
                }
            });
        });

        // Add event listener to textarea elements
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('mouseover', (e) => {
                if (narradorActivo) {
                    readText('Campo de entrada. ' + (e.target.placeholder || ''));
                }
            });

            textarea.addEventListener('input', (e) => {
                if (narradorActivo) {
                    readText('Escribiendo: ' + e.target.value);
                }
            });
        });


        // Intercept SweetAlert calls
        const originalSwalFire = Swal.fire;
        Swal.fire = function(options) {
            if (narradorActivo) {
                let textToRead = '';
                if (typeof options === 'string') {
                    textToRead = options;
                } else if (typeof options === 'object') {
                    textToRead = options.title || '';
                    if (options.text) textToRead += ' ' + options.text;
                    if (options.html) textToRead += ' ' + options.html;
                }
                readText(textToRead);

                // Wait for the SweetAlert to be rendered
                setTimeout(() => {
                    document.querySelectorAll('.swal2-confirm, .swal2-cancel').forEach(button => {
                        button.addEventListener('mouseover', (e) => {
                            const text = e.target.innerText.trim();
                            if (narradorActivo && text) readText(text);
                        });
                    });
                }, 100); // Adjust the timeout as needed
            }
            return originalSwalFire.apply(this, arguments);
        };
    });

    const toggleNarradorLink = document.getElementById('toggle-narrador');
    toggleNarradorLink.addEventListener('click', function(event) {
        event.preventDefault();
        narradorActivo = !narradorActivo;
        this.textContent = narradorActivo ? 'Desactivar Narrador' : 'Activar Narrador';
        localStorage.setItem('narradorActivo', narradorActivo);
        if (synth.speaking) synth.cancel();
    });

    // Set initial state of the button
    toggleNarradorLink.textContent = narradorActivo ? 'Desactivar Narrador' : 'Activar Narrador';

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
<script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
<script>
    
    const options = {
        bottom: '2%', // default: '32px'
        right: '2%', // default: '32px'
        left: 'unset', // default: 'unset'
        time: '1s', // default: '0.3s'
        mixColor: '#fff', // default: '#fff'
        backgroundColor: '#fff',  // default: '#fff'
        saveInCookies: true, // default: true,
        label: '🌓', // default: ''
    }

const darkmode = new Darkmode(options);
darkmode.showWidget();

window.addEventListener('load', addDarkmodeWidget);
</script>