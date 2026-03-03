<?php
if (isset($_POST["btnEntrar"])) {

    $error_usuario = $_POST["usuario"] == "";
    $error_clave = $_POST["clave"] == "";
    $error_form = $error_usuario || $error_clave;
    if (!$error_form) {
        try {
            $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        } catch (PDOException $e) {
            session_destroy();
            die(error_page("Examen3 Librería", "<h1>Examen3 Librería</h1><p>Imposible conectar a la BD. Error:" . $e->getMessage() . "</p>"));
        }

        try {
            $datos[0] = $_POST["usuario"];
            $datos[1] = md5($_POST["clave"]);
            $consulta = "SELECT * FROM usuarios WHERE lector=? AND clave=?";
            $sentencia = $conexion->prepare($consulta);
            $sentencia->execute($datos);
        } catch (PDOException $e) {
            $sentencia = null;
            $conexion = null;
            session_destroy();
            die(error_page("Examen3 Librería", "<h1>Examen3 Librería</h1><p>Imposible realizar la consulta. Error:" . $e->getMessage() . "</p>"));
        }

        if ($sentencia->rowCount() > 0) {

            $datos_usu = $sentencia->fetch(PDO::FETCH_ASSOC);
            $sentencia = null;
            $conexion = null;
            $_SESSION["usuario"] = $datos[0];
            $_SESSION["clave"] = $datos[1];
            $_SESSION["ultm_accion"] = time();
            if ($datos_usu["tipo"] == "normal") {
                header("Location:index.php");
            } else {
                header("Location:admin/gest_libros.php");
            }

            exit();
        } else {
            $sentencia = null;
            $conexion = null;
            $error_usuario = true;
        }
    }
}


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


/*

$url=DIR_SERV."/obtener_libros";
$respuesta=consumir_servicios_REST($url,"GET");
//$obj=json_decode($respuesta,true); // asi te traes el array asociativo
$obj=json_decode($respuesta); // asi te tres el objeto
if(!$obj)
 {
     session_destroy();
     die("<p>Error consumiendo el servicio: ".$url."</p></body></html>");
 }
 if(isset($obj->error))
 {
     session_destroy();
     die("<p>".$obj->error."</p></body></html>");
 }

*/


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería</title>
    <style>
        .error {
            color: red
        }

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

        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black
        }

        th {
            background-color: #CCC
        }

        .reducida {
            height: 100px
        }

        .img_editar {
            width: 30%
        }

        .centrar {
            width: 80%;
            margin: 0 auto;
        }

        .mensaje {
            font-size: 1.25rem;
            color: blue
        }

        #t_editar,
        #t_editar td {
            border: none
        }

        .centrado {
            text-align: center;
        }

        .d_flex {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5em
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
    <h1>Librería</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"]; ?>">
            <?php
            if (isset($_POST["btnEntrar"]) && $error_usuario) {
                if ($_POST["usuario"] == "")
                    echo "<span class='error'> Campo vacío</span>";
                else
                    echo "<span class='error'> Usuario y/o Contraseña no válidos</span>";
            }

            ?>
        </p>
        <p>
            <label for="clave">Contraseña: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if (isset($_POST["btnEntrar"]) && $error_clave)
                echo "<span class='error'> Campo vacío</span>";
            ?>
        </p>
        <p>
            <button type="submit" name="btnEntrar">Entrar</button>

        </p>
    </form>

    <?php
    if (isset($_SESSION["seguridad"])) {
        echo "<p class='mensaje'>" . $_SESSION["seguridad"] . "</p>";
        session_destroy();
    }

    ?>
    <h1>Listado de los Libros</h1>

    <?php
    /*
    //si te traes el array asociativo 
    echo "<div class='contenedor'>";
    foreach ($obj["libros"] as $tupla) {
        echo "<div class='list_libros'>";
        echo "<img class='reducida' src='images/" .$tupla['portada'] . "' alt='Foto' title='Foto'></br>";
        echo "<p>" . $tupla['titulo'] . " -- " .$tupla['precio'] . "</p>";
        echo "</div>";
    }
    */
    // si te traes el objeto 
    echo "<div class='contenedor'>";
    foreach ($libros as $tupla) {
        echo "<div class='list_libros'>";
        echo "<img class='reducida' src='images/" . $tupla["portada"] . "' alt='Foto' title='Foto'></br>";
        echo "<p>" .  $tupla["titulo"] . " -- " .  $tupla["precio"] . "</p>";
        echo "</div>";
    }

    echo "</div>";

    ?>
</body>

</html>