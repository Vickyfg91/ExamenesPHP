<?php
$respuesta = consumir_servicios_REST(DIR_SERV."/notasAlumno/".$datos_usuario_log["cod_usu"], "GET", $datos_env);


$json = json_decode($respuesta, true);
if (!$json) {
    session_destroy();
    die(error_page("Examen 4 Notas", "<h1>Examen 4 Notas</h1><p>Sin respuesta oportuna de la API notasAlumno</p>"));
}
if (isset($json["error"])) {

    session_destroy();
    consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
    die(error_page("Examen 4 Notas", "<h1>Examen 4 Notas</h1><p>" . $json["error"] . "</p>"));
}

if (isset($json["no_auth"])) {
    session_unset();
    $_SESSION["seguridad"] = "Usted ha dejado de tener acceso a la API. Por favor vuelva a loguearse.";
    header("Location:index.php");
    exit();
}
// si el alumno no tiene notas lo controlo aqui

    $datos_notas_alum = $json["notas"];




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona Alumno</title>
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

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th {
            background-color: gray;
        }
    </style>
</head>

<body>
    <h1>Notas de los alumnos</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usuario_log["usuario"]; ?></strong> -
        <form class="en_linea" action="index.php" method="post">
            <button class="enlace" name="btnSalir" type="submit">Salir</button>
        </form>
    </div>
    <h2>Notas del alumno <?php echo $datos_usuario_log["nombre"]; ?> </h2>

    <?php
    if (isset($json["notas"]) && count($json["notas"]) > 0) {
        echo "<table class='table'>";
        echo "<tr><th>Asignaturas</th><th>Notas</th></tr>";

        foreach ($datos_notas_alum as $tupla) {
            echo "<tr>";
            echo "<td>" . $tupla["denominacion"] . "</td>";
            echo "<td>" . $tupla["nota"] . "</td>";
            echo "<tr>";
        }
        echo "</table>";
    }
    else
    {
        echo"<h3>El alumno no tiene asignaturas Asignadas</h3>";
    }


    ?>

</body>

</html>

