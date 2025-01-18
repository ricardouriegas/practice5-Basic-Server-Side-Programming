<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?=APP_ROOT?>css/style.css" rel="stylesheet" type="text/css" /> 
    <title><?php echo $tituloPagina; ?></title>
    <script src="<?=APP_ROOT?>js/config.js"></script>
</head>
<body>

    <div class="header">
        <h1>Práctica 05</h1>
        <h2>Basic Server Side Programming</h2>
    </div>
      
    <?php require APP_PATH . "html_parts/menu.php"; ?>
      
    <div class="row">

        <div>

        <div class="card">
            <h2>Datos Recibidos por POST</h2>
            <h5>Aquí desplegamos los datos recibidos por POST. Mar 12, 2024</h5>
            <p>Hola <strong><?php echo htmlspecialchars($nombre . " " . $apellidos); ?></strong></p>
        </div>

        <div class="card">
            <h2>TITLE HEADING</h2>
            <h5>Title description, Mar 12, 2024</h5>
            <div class="fakeimg" style="height:200px;">Image</div>
            <p>Some text..</p>
            <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
        </div>

        </div>  <!-- End left column -->

    </div>  <!-- End row-->

</body>
</html>
