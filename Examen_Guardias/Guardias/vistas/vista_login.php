<?php
    if(isset($_POST["btnEntrar"]))
    {
        
       
        $error_usuario=$_POST["usuario"]=="";
        $error_clave=$_POST["clave"]=="";
        $error_form=$error_usuario||$error_clave;
       
        if(!$error_form)
        {
           
            $datos_env["usuario"]=$_POST["usuario"];
            $datos_env["clave"]=md5($_POST["clave"]);
       
            //llamo a login
            $respuesta=consumir_servicios_REST(DIR_SERV."/login","POST",$datos_env);
            $json=json_decode($respuesta,true);
         
            if(!$json)
            {
                session_destroy();
                die(error_page("Examen Guardias","<h1>Equipo Guardias</h1><p>Error consumiendo la API login</p>"));
            }
            if(isset($json["error"]))
            {
                session_destroy();
                die(error_page("Examen Guardias","<h1>Equipo Guardias</h1><p>Error".$json["error"]."</p>"));

            }
            if(isset($json["mensaje"]))
            {
                $error_usuario=true;
            }
            else
            {
                $_SESSION["usuario"]=$json["usuario"]["usuario"];
                $_SESSION["clave"]=$json["usuario"]["clave"];
                $_SESSION["ult_accion"]=time();
                $_SESSION["api_session"]=$json["api_session"];
                header("Location:index.php");
                exit;
            }


         }
            
    

    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Guardias</title>
    <style>
        
        .error{
            color:red
        }
        .mensaje{
            color:blue;
            font-size:1.25em;
        }
    </style>
</head>
<body>
    <h1>Gestión de Guardias</h1>
    <form action="index.php" method="post">
    <p>
        <label for="usuario">Usuario:</label>
        <input  type="text" id="usuario" name="usuario" value="<?php if(isset($_POST["usuario"])) echo $_POST["usuario"]?>">
        <?php
         if(isset($_POST["btnEntrar"]) && $error_usuario)
         {
             if($_POST["usuario"]=="")
                 echo "<span class='error'> Campo Vacío</span>";
             else
                 echo "<span class='error'> Usuario/clave incorrectos</span>";
         }
             
        ?>
    </p>
    <p>
        <label for="clave">Contraseña:</label>
        <input  type="password" id="clave" name="clave" value="">
        <?php
         if(isset($_POST["btnEntrar"]) && $error_clave)
         {
             if($_POST["clave"]=="")
                 echo "<span class='error'> Campo Vacío</span>";
             else
                 echo "<span class='error'> Usuario/clave incorrectos</span>";
         }
             
        ?>
        
    </p>
      <button type="submit" name="btnEntrar">Entrar</button>
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
