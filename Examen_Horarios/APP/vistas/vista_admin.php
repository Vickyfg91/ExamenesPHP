<?php
$dias[1] = "Lunes";
$dias[] = "Martes";
$dias[] = "Miércoles";
$dias[] = "Jueves";
$dias[] = "Viernes";

$horas[1] = "8:15 - 9:15";
$horas[] = "9:15 - 10:15";
$horas[] = "10:15 - 11:15";
$horas[] = "11:15 - 11:45";
$horas[] = "11:45 - 12:45";
$horas[] = "12:45 - 13:45";
$horas[] = "13:45 - 14:45";

$url = DIR_SERV . "/grupos";
$headers[] = "Authorization: Bearer " . $_SESSION["token"];
$respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
$json_grupos = json_decode($respuesta, true);

if (!$json_grupos) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}

if (isset($json_grupos["error"])) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_grupos["error"] . "</p>"));
}

if (isset($json_grupos["no_auth"])) {
    session_unset();
    $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}

if (isset($json_grupos["mensaje_baneo"])) {
    session_unset();
    $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}

if (isset($_SESSION["dia"])) {
    $_POST["dia"] = $_SESSION["dia"];
    unset($_SESSION["dia"]);
    $_POST["hora"] = $_SESSION["hora"];
    unset($_SESSION["hora"]);
    $_POST["id_grupo"] = $_SESSION["id_grupo"];
    unset($_SESSION["id_grupo"]);
}

if (isset($_POST["id_grupo"])) {
    $url = DIR_SERV . "/horario/" . $_POST["id_grupo"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_horario = json_decode($respuesta, true);

    if (!$json_horario) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_horario["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_horario["error"] . "</p>"));
    }

    if (isset($json_horario["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_horario["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    foreach ($json_horario["horario"] as $tupla) {
        $nombreUsu[$tupla["dia"]][$tupla["hora"]][] = $tupla["usuario"];
        $nombreAula[$tupla["dia"]][$tupla["hora"]][] = $tupla["aula"];
    }
}

if (isset($_POST["dia"])) {
    $url = DIR_SERV . "/profesores/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["id_grupo"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_profesores = json_decode($respuesta, true);

    if (!$json_profesores) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_profesores["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_profesores["error"] . "</p>"));
    }

    if (isset($json_profesores["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_profesores["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $url = DIR_SERV . "/profesoresLibres/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["id_grupo"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_profesoresLibres = json_decode($respuesta, true);

    if (!$json_profesoresLibres) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_profesoresLibres["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_profesoresLibres["error"] . "</p>"));
    }

    if (isset($json_profesoresLibres["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_profesoresLibres["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $url = DIR_SERV . "/aulas";
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_aulas = json_decode($respuesta, true);

    if (!$json_aulas) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_aulas["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_grupos["error"] . "</p>"));
    }

    if (isset($json_aulas["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_aulas["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }
}

if (isset($_POST["btnQuitar"])) {
    $url = DIR_SERV . "/borrarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["id_grupo"] . "/" . $_POST["id_usuario"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "DELETE", $headers);
    $json_borrarProfesor = json_decode($respuesta, true);

    if (!$json_borrarProfesor) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_borrarProfesor["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_borrarProfesor["error"] . "</p>"));
    }

    if (isset($json_borrarProfesor["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_borrarProfesor["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["id_grupo"] = $_POST["id_grupo"];
    $_SESSION["mensaje_accion"] = $json_borrarProfesor["mensaje"];
    header("Location:index.php");
    exit;
}

if (isset($_POST["btnQuitar"])) {
    $url = DIR_SERV . "/borrarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["id_grupo"] . "/" . $_POST["id_usuario"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "DELETE", $headers);
    $json_borrarProfesor = json_decode($respuesta, true);

    if (!$json_borrarProfesor) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_borrarProfesor["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_borrarProfesor["error"] . "</p>"));
    }

    if (isset($json_borrarProfesor["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_borrarProfesor["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["id_grupo"] = $_POST["id_grupo"];
    $_SESSION["mensaje_accion"] = $json_borrarProfesor["mensaje"];
    header("Location:index.php");
    exit;
}

if (isset($_POST["btnAniadir"])) {
    $url = DIR_SERV . "/insertarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["id_grupo"] . "/"  . $_POST["id_usuario"] . "/" . $_POST["id_aula"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "POST", $headers);
    $json_insertarProfesor = json_decode($respuesta, true);

    if (!$json_insertarProfesor) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_insertarProfesor["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>" . $json_insertarProfesor["error"] . "</p>"));
    }

    if (isset($json_insertarProfesor["no_auth"])) {
        session_unset();
        $_SESSION["seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    if (isset($json_insertarProfesor["mensaje_baneo"])) {
        session_unset();
        $_SESSION["seguridad"] = "Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["id_grupo"] = $_POST["id_grupo"];
    $_SESSION["mensaje_accion"] = $json_insertarProfesor["mensaje"];
    header("Location:index.php");
    exit;
}
?>
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

        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 60%;
            margin: auto;
        }

        th {
            background-color: #CCC;
        }

        .hora {
            padding: 0 2rem;
        }

        .titulo {
            text-align: center;
        }


    </style>
</head>

<body>
    <h1>Examen5 PHP</h1>
    <div>
        Bienvenido (admin)
        <strong><?php echo $datos_usu_log["usuario"]; ?></strong> -
        <form class="enlinea" action="index.php" method="post">
            <button class="enlace" type="submit" name="btnSalir">Salir</button>
        </form>
    </div>
    <h3>Horario de los Grupos</h3>
    <?php
    echo "<form action='index.php' method='post'>";
    echo "<label>Elija el grupo:</label>";
    echo "<select name='id_grupo'>";
    foreach ($json_grupos["grupos"] as $tuplas) {
        $seleccion = (isset($_POST["id_grupo"]) && $_POST["id_grupo"] == $tuplas["id_grupo"]) ? "selected" : "";
        echo "<option value='" . $tuplas["id_grupo"] . "' " . $seleccion . ">" . $tuplas["nombre"] . "</option>";
    }
    echo "</select>";
    echo "<button type='submit' name='btnHorario'>Ver Horario</button>";
    echo "</form>";

    if (isset($_POST["id_grupo"]) || isset($_POST["dia"])) {
        foreach ($json_grupos["grupos"] as $tuplas) {
            if (isset($_POST["id_grupo"]) && $_POST["id_grupo"] == $tuplas["id_grupo"]) {
                $grupo = $tuplas["nombre"];
            }
        }
        echo "<h3 class='titulo'>Horario del Grupo: " . $grupo . "</h3>";
        echo "<table>";
        echo "<tr>";
        echo "<th></th>";
        for ($i = 1; $i <= count($dias); $i++) {
            echo "<th>" . $dias[$i] . "</th>";
        }
        echo "</tr>";
        for ($hora = 1; $hora <= count($horas); $hora++) {
            echo "<tr>";
            echo "<td class='hora'>" . $horas[$hora] . "</td>";
            if ($hora == 4) {
                echo "<td colspan='5'>RECREO</td>";
            } else {
                for ($dia = 1; $dia <= count($dias); $dia++) {
                    echo "<td>";
                    echo "<form action='index.php' method='post' class='enlinea'>";
                    if (isset($nombreUsu[$dia][$hora])) {
                        for ($i = 0; $i < count($nombreUsu[$dia][$hora]); $i++) {
                            echo $nombreUsu[$dia][$hora][$i] . "(" . $nombreAula[$dia][$hora][$i] . ")<br/>";
                        }
                    }
                    echo "<input type='hidden' name='dia' value='" . $dia . "'>";
                    echo "<input type='hidden' name='hora' value='" . $hora . "'>";
                    echo "<input type='hidden' name='id_grupo' value='" . $_POST["id_grupo"] . "'>";
                    echo "<button type='submit' name='btnEditar' class='enlace'>Editar</button>";
                    echo "</form>";
                    echo "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    if (isset($_POST["dia"])) {
        echo "<h3>Editando la " . $_POST["hora"] . " Hora (" . $horas[$_POST["hora"]] . ") del " . $dias[$_POST["dia"]] . "</h3>";

        if (isset($_SESSION["mensaje_accion"])) {
            echo "<p>" . $_SESSION["mensaje_accion"] . "</p>";
            unset($_SESSION["mensaje_accion"]);
        }

        echo "<table>";
        echo "<tr>";
        echo "<th>Profesor (Aula)</th>";
        echo "<th>Acción</th>";
        echo "</tr>";
        foreach ($json_profesores["profesores"] as $tupla) {
            echo "<tr>";
            echo "<td>" . $tupla["usuario"] . " (" . $tupla["aula"] . ")</td>";

            echo "<td>";
            echo "<form action='index.php' method='post' class='enlinea'>";
            echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
            echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
            echo "<input type='hidden' name='id_grupo' value='" . $_POST["id_grupo"] . "'>";
            echo "<input type='hidden' name='id_usuario' value='" . $tupla["id_usuario"] . "'>";
            echo "<button type='submit' name='btnQuitar' class='enlace'>Quitar</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<div class='slc'>";
        echo "<form action='index.php' method='post'>";
        echo "<label>Elija Profesor:</label>";
        echo "<select name='id_usuario'>";
        foreach ($json_profesoresLibres["profesores_libres"] as $tuplas) {
            echo "<option value='" . $tuplas["id_usuario"] . "'>" . $tuplas["nombre"] . "</option>";
        }
        echo "</select>";
        echo "<label>Elija Aula:</label>";
        echo "<select name='id_aula'>";
        foreach ($json_aulas["aulas"] as $tuplas) {
            echo "<option value='" . $tuplas["id_aula"] . "'>" . $tuplas["nombre"] . "</option>";
        }
        echo "</select>";
        echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
        echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
        echo "<input type='hidden' name='id_grupo' value='" . $_POST["id_grupo"] . "'>";
        echo "<button type='submit' name='btnAniadir'>Añadir</button>";
        echo "</form>";
        echo "</div>";
    }
    ?>
</body>

</html>