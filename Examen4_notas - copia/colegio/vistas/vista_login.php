<?php
if (isset($_POST["btnLogin"])) 
{
    $error_usuario = $_POST["usuario"] == "";
    $error_clave = $_POST["clave"] == "";
    $error_form = $error_usuario || $error_clave;
    if (!$error_form) //si no hay error de formulario
    {
        //preparo los datos para enviar a al api
        $datos_env["usuario"] = $_POST["usuario"];
        $datos_env["clave"] = md5($_POST["clave"]); // IMPORTANTE!!!!!! el md5
        $respuesta = consumir_servicios_REST(DIR_SERV."/login","POST", $datos_env); // llamo al servicio y le paso los datos
      
  
        $json = json_decode($respuesta, true); // la respuesta es en un arry asociativo que meto en la variable
        if (!$json) // si no hay json
        {
            session_destroy();
            die(error_page("Examen 4 Notas", "<h1>Examen 4 Notas</h1><p>Sin respuesta oportuna de la API login</p>"));
        }
        if (isset($json["error"])) // si recibo el error
        {
            session_destroy();
            die(error_page("Examen 4 Notas", "<h1>Examen 4 Notas</h1><p>" . $json["error"] . "</p>"));
        }
        if (isset($json["usuario"])) // si recibo al usuario de forma correcta meto en variables de session toda la información
        {
           
            $_SESSION["usuario"] = $json["usuario"]["usuario"];
            $_SESSION["clave"] = $json["usuario"]["clave"];
            $_SESSION["tipo"] = $json["usuario"]["tipo"];
            $_SESSION["cod_usu"]=$json["usuario"]["cod_usu"];
            $_SESSION["ultm_accion"] = time(); // actualizo el time de la seguridad
            $_SESSION["api_session"] = $json["api_session"]; // la clave api para el control de baneo

            //salto a index
            header("Location:index.php");
            exit();
        }
        else // si hay algun error ponemos el error a true;
        $error_usuario = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Alumnos</title>
    <style>
        .error {
            color: red
        }

        .mensaje {
            color: blue;
            font-size: 1.25em
        }
    </style>
</head>

<body>
    <h1>Notas de los alumnos</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"]; ?>">
            <?php
            if (isset($_POST["btnLogin"]) && $error_usuario) {
                if ($_POST["usuario"] == "")
                    echo "<span class='error'> Campo vacío</span>";
                else
                    echo "<span class='error'> Usuario y/o Contraseña no válidos</span>";
            }

            ?>
        </p>
        <p>
            <label for="clave">Clave:</label>
            <input type="text" name="clave" value="<?php if (isset($_POST["clave"])) echo $_POST["clave"] ?>">
            <?php
            if (isset($_POST["btnLogin"]) && $error_clave) {
                if ($_POST["clave"] == "")
                    echo "<span class='error'> Campo vacío</span>";
                else
                    echo "<span class='error'> Usuario y/o Contraseña no válidos</span>";
            }
            ?>
        </p>
        <button type="submit" name="btnLogin">Login</button>
    </form>
    <?php
    if(isset($_SESSION["seguridad"]))
    {
        echo "<p class='mensaje'>".$_SESSION["seguridad"]."</p>";
        session_destroy();
    }


    ?>  
</body>

</html>
