<div style="text-align: right;" class="top-info">
    <!-- <span>Bienvenido(a) </span> -->
    <!-- <strong><?= $USUARIO_AUTENTICADO ? $USUARIO_NOMBRE_COMPLETO : "USUARIO" ?></strong> -->
    <!-- <span>|</span> -->
    <?php if ($USUARIO_AUTENTICADO){ header('Location: index.php'); exit();?>
        <!-- header('Location: index.php');
        exit(); -->
        <span><a href="<?=APP_ROOT?>logout.php" class="btn">Cerrar Sesión</a></span>
        <span><a href="<?= APP_ROOT ?>actualizar_perfil.php" class="btn">Actualizar Perfil</a></span>
        <span><a href="<?= APP_ROOT ?>cambiar_password.php" class="btn">Actualizar Contraseña</a></span>
        <span><a href="#" id="toggle-narrador" class="btn">Activar Narrador</a></span>
    <?php }else{ ?>
        <!-- link a iniciar sesion -->
        <a href="<?= APP_ROOT ?>login.php" class="btn">
            <!-- icono de perfil -->
            <i class="fas fa-user"></i>
            Iniciar Sesión</a>
        |
        <a href="#" id="toggle-narrador" class="btn">
            Activar Narrador</a>
    <?php }?>
</div>

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