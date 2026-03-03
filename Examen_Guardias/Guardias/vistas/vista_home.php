<?php
if (isset($_POST["btnEquipo"]) || isset($_POST["btnProfesor"])) {

    if ($_POST["hora"] > 3) {
        $hora = ($_POST["hora"]) - 1;
    } else {
        $hora = ($_POST["hora"]);
    }

    $datos_env["dia"] = $_POST["dia"];
    $datos_env["hora"] = $_POST["hora"];
    $datos_env["usuario"] = $datos_usuario_log["id_usuario"];

    $respuesta = consumir_servicios_REST(DIR_SERV . "/deGuardia", "GET", $datos_env);
    $json = json_decode($respuesta, true);

    if (!$json) {
        session_destroy();
        die(error_page("Examen Guardias", "<h1>Examen Guardias</h1><p>Sin respuesta oportuna de la API deGuardia</p>"));
    }
    if (isset($json["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen Guardias", "<h1>Examen Guardias</h1><p>" . $json["error"] . "</p>"));
    }

    $guardia = $json["de_guardia"];


    $respuesta = consumir_servicios_REST(DIR_SERV . "/usuariosGuardia/" . $_POST["dia"] . "/" . $_POST["hora"] . "", "GET", $datos_env);
    $json2 = json_decode($respuesta, true);


    if (!$json2) {
        session_destroy();
        die(error_page("Examen Guardias", "<h1>Examen Guardias</h1><p>Sin respuesta oportuna de la API Profesores de guardia</p>"));
    }
    if (isset($json2["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen Guardias", "<h1>Examen Guardias</h1><p>" . $json["error"] . "</p>"));
    }

    $profesores_guardia = $json2["profesores"];


   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            text-align: center;
        }

        th {
            background-color: gray;
        }
    </style>
</head>

<body>
    <h1>Gestión de guardias</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <?php echo "" . $datos_usuario_log["usuario"] . "" ?>
            <button class="enlace" type="submit" name="btnSalir">Salir</button>
        </p>
    </form>
    <?php
    $dias[1] = "Lunes";
    $dias[2] = "Martes";
    $dias[3] = "Miercoles";
    $dias[4] = "Jueves";
    $dias[5] = "Viernes";

    $horas[1] = "1ºHora";
    $horas[2] = "2ºHora";
    $horas[3] = "3ºHora";
    $horas[4] = "";
    $horas[5] = "4ºHora";
    $horas[6] = "5ºHora";
    $horas[7] = "6ºHora";
    $numero = 1;
    echo "<table>";
    echo "<tr><th></th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th></tr>";

    for ($hora = 1; $hora < 8; $hora++) {

        echo "<tr>";
        echo "<td>" . $horas[$hora] . " </td>";
        if ($hora == 4) {

            echo "<td colspan='5'>RECREO</td>";
        } else {
            for ($dia = 1; $dia < 6; $dia++) {
                echo "<td>";
                echo "<form action='index.php' method='post'><button class='enlace' type='submit' name='btnEquipo'>Equipo " . $numero . "</button>";
                echo "<input type='hidden' name='dia' value='" . $dia . "'/>";
                echo "<input type='hidden' name='hora' value='" . $hora . "'/>";
                echo "<input type='hidden' name='numero' value='" . $numero . "'/>";
                echo "</form>";
                echo "</td>";
                $numero++;
            }
            echo "</tr>";
        }
    }

    echo "</table>";

    if (isset($_POST["btnEquipo"]) || isset($_POST["btnProfesor"])) {
        echo "<h2>EQUIPO DE GUARDIA " . $_POST["numero"] . "</h2>";


        if ($guardia == false) {
            echo "<h2>!!Atención, usted no se encuentra de guardia el  " . $dias[$_POST["dia"]] . " a " . $horas[$_POST["hora"]] . "</h2>";
        } else {
            echo "<h3>" . $dias[$_POST["dia"]] . " a " . $horas[$_POST["hora"]] . "</h3>";
            echo "<table>";
            echo "<tr><th>Profesores de Guardia</th><th>Información del Profesor con id: </th></tr>";
            for ($i = 0; $i < count($profesores_guardia); $i++) {
                echo "<tr>";
                echo "<td>";
                echo "<form action='index.php' method='post'><button class='enlace' name='btnProfesor'>" . $profesores_guardia[$i]["nombre"] . "</button>";
                echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'/>";
                echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'/>";
                echo "<input type='hidden' name='numero' value='" . $_POST["numero"] . "'/>";
                echo "<input type='hidden' name='id_usuario' value='" . $profesores_guardia[$i]["id_usuario"] . "'/>";
                echo "</form>";
                echo "</td>";

                if (isset($_POST["btnProfesor"]) && $i==0) {
                    echo "<td rowspan='".count($profesores_guardia)."'>";
                    echo "<p><strong>Nombre: </strong></p>";
                    echo "<p><strong>Usuario: </strong></p>";
                    echo "<p><strong>Contraseña: </strong></p>";
                    echo "<p><strong>Email : </strong></p>";
                    echo "</td>";
                }
                echo "</tr>";
            }
           
            echo "</table>";
        }
    }


    ?>

</body>

</html>