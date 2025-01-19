<div style="text-align: right;" class="top-info">
    <span>Bienvenido(a) </span>
    <strong><?= $USUARIO_AUTENTICADO ? $USUARIO_NOMBRE_COMPLETO : "USUARIO" ?></strong>
    <span>|</span>
    <?php if ($USUARIO_AUTENTICADO){ ?>
        <span><a href="<?=APP_ROOT?>logout.php" class="btn">Cerrar Sesion</a></span>
        <span><a href="<?= APP_ROOT ?>actualizar_perfil.php"class="btn">Actualizar Perfil</a></span>
        <span><a href="<?= APP_ROOT ?>cambiar_password.php" class="btn">Actualizar Contraseña</a></span>
        <span><a href="#" id="toggle-narrador" class="btn">Activar Narrador</a></span>
    <?php }else{ ?>
        <!-- link a iniciar sesion -->
        <a href="<?= APP_ROOT ?>login.php" class="btn">Iniciar Sesión</a>
        <a href="#" id="toggle-narrador" class="btn">Activar Narrador</a>
    <?php }?>
</div>

<script>
    const synth = window.speechSynthesis;
    const utterance = new SpeechSynthesisUtterance();
    let narradorActivo = false;

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
                    readText(e.target.value);
                }
            });
        });

        // Add event listener to textarea elements
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('mouseover', (e) => {
                if (narradorActivo) {
                    readText('Área de texto. ' + (e.target.placeholder || ''));
                }
            });

            textarea.addEventListener('input', (e) => {
                if (narradorActivo) {
                    readText(e.target.value);
                }
            });
        });
    });

    const toggleNarradorLink = document.getElementById('toggle-narrador');
    toggleNarradorLink.addEventListener('click', function(event) {
        event.preventDefault();
        narradorActivo = !narradorActivo;
        this.textContent = narradorActivo ? 'Desactivar Narrador' : 'Activar Narrador';
        if (synth.speaking) synth.cancel();
    });
</script>
<script>
    // Recuperar el estado del narrador del localStorage
    narradorActivo = localStorage.getItem('narradorActivo') === 'true';
    toggleNarradorLink.textContent = narradorActivo ? 'Desactivar Narrador' : 'Activar Narrador';

    // Actualizar el localStorage cuando cambie el estado
    toggleNarradorLink.addEventListener('click', function() {
        localStorage.setItem('narradorActivo', narradorActivo);
    });
</script>