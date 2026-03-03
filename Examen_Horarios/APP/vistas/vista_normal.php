<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen5 PHP</title>
    <style>
        .enlinea {
            display: inline
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Examen5 PHP</h1>
    <div>
        Bienvenido (normal)
        <strong><?php echo $datos_usu_log["usuario"]; ?></strong> -
        <form class="enlinea" action="index.php" method="post">
            <button class="enlace" type="submit" name="btnSalir">Salir</button>
        </form>
    </div>
    <?php
    if (isset($_SESSION["seguridad"])) {
        echo "<p class='error'>" . $_SESSION["seguridad"] . "</p>";
        unset($_SESSION["seguridad"]);
    }
    ?>
</body>

</html>