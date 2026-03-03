<?php

// Obtener todos los alumnos
$url = DIR_SERV . "/alumnos";
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
    $_SESSION["seguridad"] = $obj->no_auth;
    header("Location:../index.php");
    exit;
}

if (isset($_POST["alumnos"]) || isset($_SESSION["alumnos"])) {
    if (isset($_POST["alumnos"]))
        $profesores = $_POST["alumnos"];
    else
        $profesores = $_SESSION["alumnos"];

    // Obtener notas de un alumno
    $url = DIR_SERV . "/notasAlumno/" . $profesores;
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "GET", $datos);
    $obj1 = json_decode($respuesta);
    if (!$obj1) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }
    if (isset($obj1->error)) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
    }
    if (isset($obj1->no_auth)) {
        session_unset();
        $_SESSION["seguridad"] = $obj1->no_auth;
        header("Location:../index.php");
        exit;
    }

    // Obtener notas NO calificadas de un alumno
    $url = DIR_SERV . "/NotasNoEvalAlumno/" . $profesores;
    $datos["api_session"] = $_SESSION["api_session"];
    $respuesta = consumir_servicios_REST($url, "GET", $datos);
    $obj2 = json_decode($respuesta);
    if (!$obj2) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }
    if (isset($obj2->error)) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
    }
    if (isset($obj2->no_auth)) {
        session_unset();
        $_SESSION["seguridad"] = $obj2->no_auth;
        header("Location:../index.php");
        exit;
    }
}

if (isset($_POST["btnAtras"])) {
    unset($_SESSION["cod_asig"]);
    header("Location:index.php");
    exit();
}

if (isset($_POST["btnEditar"])) {
    $_SESSION["alumnos"] = $_POST["btnEditar"];
    $_SESSION["cod_asig"] = $_POST["cod_asig"];
    header("Location:index.php");
    exit();
}

if (isset($_POST["btnCalificar"])) {

    $_SESSION["alumnos"] = $_POST["btnCalificar"];
    $_SESSION["cod_asig"] = $_POST["notas"];

    // Calificar notas
    $url = DIR_SERV . "/ponerNota/" . $_POST["btnCalificar"];
    $datos["api_session"] = $_SESSION["api_session"];
    $datos["cod_asig"] = $_POST["notas"];
    $respuesta = consumir_servicios_REST($url, "POST", $datos);
    $obj3 = json_decode($respuesta);
    if (!$obj3) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }
    if (isset($obj3->error)) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
    }
    if (isset($obj3->no_auth)) {
        session_unset();
        $_SESSION["seguridad"] = $obj3->no_auth;
        header("Location:../index.php");
        exit;
    }

    if (isset($obj3->mensaje)) {
        $_SESSION["mensaje"] = "Asignatura calificada con un 0. Cambie la nota si lo estima necesario.";
        header("Location:index.php");
        exit();
    }
}

if (isset($_POST["btnBorrar"])) {

    $_SESSION["alumnos"] = $_POST["btnBorrar"];

    // Borrar nota
    $url = DIR_SERV . "/quitarNota/" . $_POST["btnBorrar"];
    $datos["api_session"] = $_SESSION["api_session"];
    $datos["cod_asig"] = $_POST["cod_asig"];
    $respuesta = consumir_servicios_REST($url, "DELETE", $datos);
    $obj4 = json_decode($respuesta);
    if (!$obj4) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }
    if (isset($obj4->error)) {
        session_destroy();
        die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
    }
    if (isset($obj4->no_auth)) {
        session_unset();
        $_SESSION["seguridad"] = $obj4->no_auth;
        header("Location:../index.php");
        exit;
    }

    if (isset($obj4->mensaje)) {
        $_SESSION["mensaje"] = $obj4->mensaje;
        header("Location:index.php");
        exit();
    }
}

if (isset($_POST["btnCambiar"])) {
    $_SESSION["alumnos"] = $_POST["btnCambiar"];
    $_SESSION["cod_asig"] = $_POST["cod_asig"];
    $error_form = $_POST["nota"] == "" || !is_numeric($_POST["nota"]) || $_POST["nota"] < 0 || $_POST["nota"] > 10;
    if (!$error_form) {
        // Actualizar nota
        $url = DIR_SERV . "/cambiarNota/" . $_POST["btnCambiar"];
        $datos["api_session"] = $_SESSION["api_session"];
        $datos["cod_asig"] = $_POST["cod_asig"];
        $datos["nota"] = $_POST["nota"];
        $respuesta = consumir_servicios_REST($url, "PUT", $datos);
        $obj5 = json_decode($respuesta);
        if (!$obj5) {
            session_destroy();
            die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }
        if (isset($obj5->error)) {
            session_destroy();
            die(error_page("Examen4 DWESE Curso 23-24", "<h1>Notas de los alumnos</h1><p>" . $obj->error . "</p>"));
        }
        if (isset($obj5->no_auth)) {
            session_unset();
            $_SESSION["seguridad"] = $obj5->no_auth;
            header("Location:../index.php");
            exit;
        }

        if (isset($obj5->mensaje)) {
            unset($_SESSION["cod_asig"]);
            $_SESSION["mensaje"] = "Nota cambiada con éxito.";
            header("Location:index.php");
            exit();
        }
    }
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

        .mensaje {
            color: blue;
            font-size: 1.2rem;
        }

        .error {
            color: red;
        }

        table {
            border-collapse: collapse;
        }

        tr,
        th,
        td {
            border: 1px solid black;
            text-align: center;
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
    <?php
    if (count($obj->alumnos) > 0) {
    ?>
        <form action="index.php" method="post">
            <p>
                <label for="alumnos">Seleccione un Alumno: </label>
                <select name="alumnos" id="alumnos">
                    <?php
                    foreach ($obj->alumnos as $tupla) {
                        if ((isset($_POST["alumnos"]) && $_POST["alumnos"] == $tupla->cod_usu) || isset($_SESSION["alumnos"]) && $_SESSION["alumnos"] == $tupla->cod_usu) {
                            echo "<option selected value='" . $tupla->cod_usu . "'>" . $tupla->nombre . "</option>";
                            $nombre_seleccionado = $tupla->nombre;
                        } else
                            echo "<option value='" . $tupla->cod_usu . "'>" . $tupla->nombre . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="btnVerNotas">Ver Notas</button>
                <?php
                if (isset($_POST["alumnos"]) || isset($_SESSION["alumnos"])) {
                    if (isset($_POST["alumnos"]))
                        $profesores = $_POST["alumnos"];
                    else
                        $profesores = $_SESSION["alumnos"];
                    echo "<h2>Notas del Alumno " . $nombre_seleccionado . "</h2>";
                    echo "<table>";
                    echo "<tr><th>Asignatura</th><th>Nota</th><th>Acción</th></tr>";
                    foreach ($obj1->notas as $tupla) {
                        echo "<tr><form action='index.php' method='post'>";
                        echo "<td>" . $tupla->denominacion . "</td>";
                        if (isset($_SESSION["cod_asig"]) && $tupla->cod_asig == $_SESSION["cod_asig"]) {
                ?>
                            <td><input type='text' name='nota' value='<?php if (isset($_POST["nota"])) echo $_POST["nota"];
                                                                        else echo $tupla->nota; ?>' placeholder='Teclee un valor entre 0 y 10'>
                    <?php
                            if (isset($_POST["btnCambiar"]) && $error_form) {
                                echo "<p class='error'>*No has introducido un valor válido de Nota*</p>";
                            }
                            echo "</td>";
                            echo "<td>
                        <input type='hidden' name='cod_asig' value='" . $tupla->cod_asig . "'>
                        <button type='submit' name='btnCambiar' class='enlace' value='" . $profesores . "'>Cambiar</button> - 
                        <button type='submit' name='btnAtras' class='enlace' value='" . $profesores . "'>Atrás</button>
                        </form></td>";
                        } else {
                            echo "<td>" . $tupla->nota . "</td>";
                            echo "<td><form action='index.php' method='post'>
                        <input type='hidden' name='cod_asig' value='" . $tupla->cod_asig . "'>
                        <button type='submit' name='btnEditar' class='enlace' value='" . $profesores . "'>Editar</button> - 
                        <button type='submit' name='btnBorrar' class='enlace' value='" . $profesores . "'>Borrar</button>
                        </form></td>";
                        }

                        echo "</tr>";
                    }
                    echo "</table>";

                    if (isset($_SESSION["mensaje"])) {
                        echo "<p class='mensaje'>¡¡¡" . $_SESSION["mensaje"] . "!!!</p>";
                        unset($_SESSION["mensaje"]);
                        unset($_SESSION["cod_asig"]);
                    }

                    if (count($obj2->notas) > 0) {
                        echo "<p>";
                        echo "<label for='notas'>Asignaturas que a <strong>" . $nombre_seleccionado . "</strong> aún le quedan por calificar: </label>";
                        echo "<select name='notas' id='notas'>";
                        foreach ($obj2->notas as $tupla) {
                            echo "<option value='" . $tupla->cod_asig . "'>" . $tupla->denominacion . "</option>";
                        }
                        echo "</select>";
                        echo "<button type='submit' name='btnCalificar' value='" . $profesores . "'>Calificar</button>";
                        echo "</p>";
                    } else {
                        echo "<p>A <strong>" . $nombre_seleccionado . "</strong> no quedan asignaturas por calificar.</p>";
                    }
                }
                    ?>
            </p>
        </form>
    <?php
    } else {
        echo "<p>En estos momentos no tenemos ningún alumno registrado en la BD.</p>";
    }
    ?>
</body>

</html>