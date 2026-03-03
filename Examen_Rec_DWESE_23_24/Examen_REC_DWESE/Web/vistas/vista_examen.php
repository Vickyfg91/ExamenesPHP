<?php
//gestión de profesores y horarios
//días de la semana
$dias[1] = "Lunes";
$dias[2] = "Martes";
$dias[3] = "Miercoles";
$dias[4] = "Jueves";
$dias[5] = "Viernes";

// Horas

$horas[1] = "8:15 - 9:15";
$horas[2] = "9:15 - 10:15";
$horas[3] = "10:15 - 11:15";
$horas[4] = "11:15 - 11:45";
$horas[5] = "11:45 - 12:45";
$horas[6] = "12:45 - 13:45";
$horas[7] = "13:45 - 14:45";

$dia = date("w"); // aqui obtengo el dia
//hago el bucle para llamar a el servicio segun cantidad de horas
for ($hora = 1; $hora < count($horas); $hora++) {
    if ($hora != 4) {

        $respuesta = consumir_servicios_REST(DIR_SERV . "/usuariosGuardia/" . $dia . "/" . $hora, "GET", $datos_env);
        $json = json_decode($respuesta, true);
        if (!$json) {
            session_destroy();
            die(error_page("Práctica ExamenRec_SW_23_24  HORARIOS", "<h1>Práctica ExamenRec_SW_23_24  HORARIOS</h1><p>Sin respuesta oportuna de la API usuario</p>"));
        }

        if (isset($json["error"])) {
            session_destroy();
            consumir_servicios_REST(DIR_SERV . "/salir", "POST");
            die(error_page("Práctica ExamenRec_SW_23_24  HORARIOS", "<h1>Práctica ExamenRec_SW_23_24  HORARIOS</h1><p>" . $json["error_bd"] . "</p>"));
        }

        if (isset($json["no_auth"])) {
            session_unset();
            $_SESSION["seguridad"] = "Usted ha dejado de tener acceso a la API. Por favor vuelva a loguearse.";
            header("Location:index.php");
            exit();
        }

        $profesores_guardia[$hora] = $json["usuarios"];
    }
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica Rec 3</title>
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
            border: 1px, solid, black
        }

        table {
            border-collapse: collapse;
            text-align: center;
        }

        th {
            background-color: grey;
        }
    </style>
</head>

<body>
    <h1>Práctica ExamenRec_SW_23_24 HORARIOS</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usuario_log["usuario"]; ?></strong> -
        <form class="en_linea" action="index.php" method="post">
            <button class="enlace" name="btnSalir" type="submit">Salir</button>
        </form>
        <?php

        echo "<h4>Hoy es:  " . $dias[$dia] . " </h4>";

        echo "<table>";
        echo "<tr>";
        echo "<th>Horas</th>";
        echo "<th>Profesor de Guardia</th>";
        if (isset($_POST["btnDetalles"])) {
            echo "<th>Informacion del profesaor con ID: " . $_POST["btnDetalles"] . "</th>";
        } else {
            echo "<th>Informacion del profesaor con ID: </th>";
        }
        //
        echo "</tr>";
        for ($hora = 1; $hora < count($horas); $hora++) {

            if ($hora != 4) {
                echo "<tr>";
                echo "<td>" . $horas[$hora] . "</td>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<ol>";
                foreach ($profesores_guardia[$hora] as $tupla) {
                    echo "<li><button class='enlace' name='btnDetalles' value='" . $tupla["id_usuario"] . "'>" . $tupla["nombre"] . "</button></li>";
                }
                echo "</ol>";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                if (isset($_POST["btnDetalles"]) && $hora == 1) {
                    $respuesta = consumir_servicios_REST(DIR_SERV . "/usuario/" . $_POST["btnDetalles"], "GET", $datos_env);
                    $json = json_decode($respuesta, true);
                    if (!$json) {
                        session_destroy();
                        die(error_page("Práctica ExamenRec_SW_23_24  HORARIOS", "<h1>Práctica ExamenRec_SW_23_24  HORARIOS</h1><p>Sin respuesta oportuna de la API usuario</p>"));
                    }

                    if (isset($json["error"])) {
                        session_destroy();
                        consumir_servicios_REST(DIR_SERV . "/salir", "POST");
                        die(error_page("Práctica ExamenRec_SW_23_24  HORARIOS", "<h1>Práctica ExamenRec_SW_23_24  HORARIOS</h1><p>" . $json["error_bd"] . "</p>"));
                    }

                    if (isset($json["no_auth"])) {
                        session_unset();
                        $_SESSION["seguridad"] = "Usted ha dejado de tener acceso a la API. Por favor vuelva a loguearse.";
                        header("Location:index.php");
                        exit();
                    }

                    $detalle_usu = $json["usuario"];
                    if ($detalle_usu) {
                        echo "<p><strong>Nombre: </strong>" . $detalle_usu["nombre"] . "</p>";
                        echo "<p><strong>Usuario: </strong>" . $detalle_usu["usuario"] . "</p>";
                        echo "<p><strong>Contraseña: </strong>**********</p>";

                        echo "<p><strong>Email: </strong>";
                        if (isset($detalle_usu["email"]))
                            echo $detalle_usu["email"];
                        else
                            echo "Email no disponible";
                        echo"</p>";
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";



        ?>
    </div>
    <?php
    if (isset($_SESSION["mensaje_registro"])) {
        echo "<p class='mensaje'>" . $_SESSION["mensaje_registro"] . "</p>";
        unset($_SESSION["mensaje_registro"]);
    }
    ?>
</body>

</html>