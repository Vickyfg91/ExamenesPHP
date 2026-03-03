<?php
session_name("Examen2_SW");
session_start();
require "src/funciones_ctes.php";

if (isset($_POST["btnQuitar"])) {
    $datos_env["grupo"] = $_POST["grupoQuitar"];
    $datos_env["dia"] = $_POST["diaQuitar"];
    $datos_env["hora"] = $_POST["horaQuitar"];
    $usuario = $_POST["usuarios"];

    $respuesta = consumir_servicios_REST(DIR_SERV . "/quitarGrupo/" . $usuario, "DELETE", $datos_env);
    $json = json_decode($respuesta, true);

    if (!$json) {
        session_destroy();
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>Sin respuesta oportuna de la API Quitar Grupo</p>"));
    }
    if (isset($json["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>" . $json["error"] . "</p>"));
    }
    /*
     $_SESSION["usuarios"]=$_POST["usuarios"];
    $_SESSION["dia"]=$_POST["diaQuitar"];
    $_SESSION["hora"]=$_POST["horaQuitar"];
    $_SESSION["quitar"]=true;

    */

   
    $_SESSION["mensaje_accion"] = "¡¡ Grupo Eliminado con exito !!";
    header("Location:index.php");
    exit;
}
/*
if(isset($_SESSION["mensaje_accion"]))
{

    $_POST["usuarios"]=  $_SESSION["usuarios"];
    $_POST["diaQuitar"]=  $_SESSION["dia"];
    $_POST["horaQuitar"]=  $_SESSION["hora"];
    unset($_SESSION["usuarios"]);
    unset($_SESSION["dia"]);
    unset($_SESSION["hora"]);

}

*/

// obtengo a todos los usuarios
$respuesta = consumir_servicios_REST(DIR_SERV . "/profesores", "GET");
$json = json_decode($respuesta, true);
if (!$json) {
    session_destroy();
    die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>Sin respuesta oportuna de la API notasAlumno</p>"));
}
if (isset($json["error"])) {

    session_destroy();
    consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
    die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>" . $json["error"] . "</p>"));
}
// si el alumno no tiene notas lo controlo aqui
$usuarios = $json["usuarios"];





if (isset($_POST["usuarios"])) {
    $usuario = $_POST["usuarios"];
    $respuesta = consumir_servicios_REST(DIR_SERV . "/horarios/" . $usuario . "", "GET");
    $json = json_decode($respuesta, true);
    if (!$json) {
        session_destroy();
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>Sin respuesta oportuna de la API notasAlumno</p>"));
    }
    if (isset($json["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>" . $json["error"] . "</p>"));
    }

   $horarios_profesor = $json["horarios"];
    // var_dump($horarios_profesor);

    // solucion profesor
    foreach ($json["horarios"] as $tupla)
     {
        if (isset($horario_pro[$tupla["dia"]][$tupla["hora"]])) // si existe esta posicion
            $horario_pro[$tupla["dia"]][$tupla["hora"]].= "/" . $tupla["nombre"]; // me concatenas
        else
            $horario_pro[$tupla["dia"]][$tupla["hora"]] = $tupla["nombre"];
    }
}
if (isset($_POST["btnEditar"])) { // aqui el profesor usaa la variable dia if(isset($_post["dia"]))

    $datos_env["dia"] = $_POST["dia"];
    $datos_env["hora"] = $_POST["hora"];
    $usuario = $_POST["usuarios"];


    $respuesta = consumir_servicios_REST(DIR_SERV . "/obtenerGrupos/" . $usuario, "GET", $datos_env);
    $json = json_decode($respuesta, true);
    //var_dump($json);
    if (!$json) {
        session_destroy();
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>Sin respuesta oportuna de la API obtener grupos</p>"));
    }
    if (isset($json["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>" . $json["error"] . "</p>"));
    }
    $grupos = $json["grupos"];


    // select del grupoNOincluidos
    $datos_env2["dia"] = $_POST["dia"];
    $datos_env2["hora"] = $_POST["hora"];
    $usuario2 = $_POST["usuarios"];

    $respuesta = consumir_servicios_REST(DIR_SERV . "/gruposNoIncluidos/" . $usuario2, "POST", $datos_env2);
    $json = json_decode($respuesta, true);

    if (!$json) {
        session_destroy();
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>Sin respuesta oportuna de la API obtener grupos</p>"));
    }
    if (isset($json["error"])) {

        session_destroy();
        consumir_servicios_REST(DIR_SERV . "/salir", "POST", $datos_env);
        die(error_page("Examen 2 Horarios", "<h1>Examen 2 Horarios</h1><p>" . $json["error"] . "</p>"));
    }
    $gruposNO = $json["gruposNO"];
    // var_dump($gruposNO);
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen2 PHP</title>
    <style>
        table,
        td,
        th {
            border-collapse: collapse;
            border: 1px solid black;
            text-align: center;
            width: 800px;
        }

        th {
            background-color: gray;
        }

        .enlace {
            border: none;
            background: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        .segunda {
            width: 500px;
        }
    </style>
</head>

<body>
    <h1>Examen2 PHP</h1>
    <h2>Horario de los Profesores</h2>
    <form action="index.php" method="post">
        <p>
            <label for="usuarios">Horario del Profesor: </label>
            <select id="usuarios" name="usuarios">
                <?php
                foreach ($usuarios as $tupla) {
                    if (isset($_POST["usuarios"]) && $_POST["usuarios"] == $tupla["id_usuario"]) {
                        echo "<option selected value='" . $tupla["id_usuario"] . "'>" . $tupla["nombre"] . "</option>";
                        $nombre_profesor = $tupla["nombre"];
                    } else {
                        echo "<option value='" . $tupla["id_usuario"] . "'>" . $tupla["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="btnVerHorario">Ver Horario</button>
        </p>
    </form>

    <?php


    if (isset($_POST["usuarios"])) {
        // horas
        $horas[1] = "8:15-9:15";
        $horas[2] = "9:15-10:15";
        $horas[3] = "10:15-11:15";
        $horas[4] = "11:15-11:45"; //recreo
        $horas[5] = "11:45-12:45";
        $horas[6] = "12:45-13:45";
        $horas[7] = "13:45-14:45";
        //días
        $dias[0] = "";
        $dias[1] = "Lunes";
        $dias[2] = "Martes";
        $dias[3] = "Miercoles";
        $dias[4] = "Jueves";
        $dias[5] = "Viernes";

        echo "<h2>El horario del profesor " . $nombre_profesor . " </h2>";

        echo "<table>";
        echo "<tr><th></th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th></tr>";
        for ($hora = 1; $hora < 8; $hora++) {
            echo "<tr>";
            echo "<td>" . $horas[$hora] . " </td>";
            if ($hora == 4) {
                echo "<td colspan='5'>RECREO</td>";
            } else {
                for ($dia = 1; $dia < 6; $dia++)
                 {
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";
                    foreach ($horarios_profesor as $tupla) {

                        if ($tupla["dia"] == $dia && $tupla["hora"] == $hora) {
                            echo $tupla['nombre'] . "<br/>";
                        }
                    }

                    echo "<input type='hidden' name='dia' value='" . $dia . "'/>";
                    echo "<input type='hidden' name='hora' value='" . $hora . "'/>";
                    echo "<input type='hidden' name='usuarios' value='" . $_POST["usuarios"] . "'/>";
                    echo "<br/><button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                    echo "</form>";
                    echo "</td>";
                }
            }

            echo "</tr>";
        }

        echo "</table>";

        //solucion profesor
        echo "<table>";
        echo "<tr>";
        for ($i = 0; $i < count($dias); $i++) {//primera linea
            echo "<th>" . $dias[$i] . "</th>";
        }
        echo "</tr>";
        for ($hora = 1; $hora <= count($horas); $hora++) {

            echo "<tr>";
            echo "<th>" . $horas[$hora] . "</th>";
            if ($hora == 4)
             {
                echo "<td colspan='5'>RECREO</td>";
            } else 
            {
                for ($dia = 1; $dia < count($dias); $dia++) 
                {
                    echo "<td>";
                    if(isset($horario_pro[$dia][$hora]))
                        echo $horario_pro[$dia][$hora];
                    
                        echo "<form action='index.php' method='post'>";
                        echo "<input type='hidden' name='dia' value='" . $dia . "'/>";
                        echo "<input type='hidden' name='hora' value='" . $hora . "'/>";
                        echo "<input type='hidden' name='usuarios' value='" . $_POST["usuarios"] . "'/>";
                        echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                        echo "</form>";
                     echo "</td>";

                }
            }

            echo "</tr>";
        }
        echo "</table>";
    }
    if (isset($_POST["btnEditar"]) || isset($_POST["btnQuitar"])) { //aqui el profesor usa la variable dia yo editar


        $dia = $_POST["dia"];
        $hora = $_POST["hora"];
        $usuario = $_POST["usuarios"];
        echo "<h3>Editando la " . $hora . " hora de  (" . $horas[$hora] . ") del " . $dias[$dia] . "</h3>";

        if (isset($_SESSION["mensaje_accion"])) {
            echo "<p class='mensaje'>" . $_SESSION["mensaje_accion"] . "</p>";
            unset($_SESSION["mensaje_accion"]);
        }
    

        if (count($grupos) > 0) {
            echo "<table class='segunda'>";
            echo "<tr><th>Grupo</th><th>Acción</th></tr>";
            foreach ($grupos as $tupla) {
                echo "<tr>";
                echo "<td>" . $tupla["nombre"] . "</td>";
                echo "<td> <form action='index.php' method='post'><button class='enlace' type='submit' name='btnQuitar'>Quitar</button>";
                echo "<input type='hidden' name='usuarios' value='" . $tupla["usuario"] . "'/>";
                echo "<input type='hidden' name='grupoQuitar' value='" . $tupla["id_grupo"] . "'/>";
                echo "<input type='hidden' name='diaQuitar' value='" . $tupla["dia"] . "'/>";
                echo "<input type='hidden' name='horaQuitar' value='" . $tupla["hora"] . "'/>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<table class='segunda'>";
            echo "<tr><th>Grupo</th><th>Acción</th></tr>";
            echo "</table>";
        }

    ?>
        <form action="index.php" method="post">
            <?php
              echo "<input type='hidden' name='usuarios' value='" . $tupla["usuario"] . "'/>";
              echo "<input type='hidden' name='grupoQuitar' value='" . $tupla["id_grupo"] . "'/>";
              echo "<input type='hidden' name='diaQuitar' value='" . $tupla["dia"] . "'/>";
              echo "<input type='hidden' name='horaQuitar' value='" . $tupla["hora"] . "'/>";
            ?>
            <p>
            <select id="grupos" name="gruposNo">
                <?php
                 
                foreach ($gruposNO as $tupla) {

                    echo "<option value='" . $tupla["id_grupo"] . "'>" . $tupla["nombre"] . "</option>";
                }

                ?>
            </select>
            <button type="submit" name="btnAgregar">Añadir</button>
            </p>
        </form>
    <?php



    }

    ?>

</body>

</html>