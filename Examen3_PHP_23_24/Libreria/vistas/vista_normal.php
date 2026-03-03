<?php

/*****consulta para mostra la tabla******/
try {
    $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    session_destroy();
    die(error_page("Examen3 Librería", "<h1>Examen3 Librería</h1><p>Imposible conectar a la BD. Error:" . $e->getMessage() . "</p>"));
}

try {

    $consulta = "SELECT * FROM libros ";
    $sentencia = $conexion->prepare($consulta);
    $sentencia->execute();
} catch (PDOException $e) {
    $sentencia = null;
    $conexion = null;
    session_destroy();
    die(error_page("Examen3 Librería", "<h1>Examen3 Librería</h1><p>Imposible realizar la consulta. Error:" . $e->getMessage() . "</p>"));
}
$libros = $sentencia->fetchAll(PDO::FETCH_ASSOC);
$sentencia = null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica Rec 2</title>
    <style>
        .en_linea {
            display: inline
        }

        .enlace {
            border: none;
            background: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        .mensaje {
            font-size: 1.25em;
            color: blue
        }

        .reducida {
            height: 100px
        }

        .img_editar {
            width: 30%
        }

        .contenedor {
            display: flex;
            flex-wrap: wrap;
        }

        .list_libros {
            border: 1px solid black;
            margin: 0.5rem;
            flex: 0 25%;
        }
    </style>
</head>

<body>
    <h1>Práctica Rec 2</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usuario_log["lector"]; ?></strong> -
        <form class="en_linea" action="index.php" method="post">
            <button class="enlace" name="btnSalir" type="submit">Salir</button>
        </form>
    </div>
    <h1>Listado de los Libros</h1>

    <?php
    echo "<div class='contenedor'>";
    foreach ($libros as $tupla) {
        echo "<div class='list_libros'>";
        echo "<img class='reducida' src='images/" . $tupla["portada"] . "' alt='Foto' title='Foto'></br>";
        echo "<p>" . $tupla["titulo"] . " -- " . $tupla["precio"] . "</p>";
        echo "</div>";
    }
    echo "</div>";
    if (isset($_SESSION["seguridad"])) {
        echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";
        session_destroy();
    }

    ?>


</body>

</html>