<?php
$url = DIR_SERV . "/notasAlumno/" . $datos_usuario_log->cod_usu;
$datos["api_session"] = $_SESSION["api_session"];
$respuesta = consumir_servicios_REST($url, "GET", $datos);
$obj = json_decode($respuesta);
if (!$obj) {
    session_destroy();
    die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}
if (isset($obj->error)) {
    session_destroy();
    die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
}
if (isset($obj->no_auth)) {
    session_unset();
    $_SESSION["seguridad"] = "El tiempo de sesión de la API ha caducado";
    header("Location:index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen4 DWESE Curso 23-24</title>
    <style>
        .enlinea {
            display: inline;
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
        }

        tr,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: #CCC;
        }
    </style>
</head>

<body>
    <h1>Notas de los alumnos</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usuario_log->usuario ?></strong> -
        <form action="index.php" method="post" class="enlinea">
            <button type="submit" name="btnSalir" class="enlace">Salir</button>
        </form>
    </div>
    <h2>Notas del Alumno <?php echo $datos_usuario_log->nombre ?></h2>
    <table>
        <tr>
            <th>Asignatura</th>
            <th>Nota</th>
        </tr>
        <?php
        foreach ($obj->notas as $tupla) {
            echo "<tr>";
            echo "<td>" . $tupla->denominacion . "</td>";
            echo "<td>" . $tupla->nota . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>