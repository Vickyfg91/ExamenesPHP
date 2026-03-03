<?php
if(isset($_POST["btnLogin"]))
{
    $error_usuario=$_POST["usuario"]=="";
    $error_clave=$_POST["clave"]=="";
    $error_form=$error_usuario||$error_clave;
    if(!$error_form)
    {
       $datos_env["usuario"]=$_POST["usuario"];
       $datos_env["clave"]=md5($_POST["clave"]);

       $respuesta=consumir_servicios_REST(DIR_SERV."/login","POST",$datos_env);
       $json=json_decode($respuesta,true);
       //que no llegue nada
       if(!$json)
       {
        session_destroy();
        die(error_page("<h1>Examen 2 PRUEBA</h1>","<p>Error al consumir los servicios de la API Login<p>"));
       }
       //que llegue el mensaje de error
       if(isset($json["error"]))
       {
        session_destroy();
        die(error_page("<h1>Examen 2 PRUEBA</h1>","<p>Error al ".$json["error"]."<p>"));
       }
       //que llegue el mensaje 
       if(isset($json["mensaje"]))
       {
        $error_usuario=true;
       }
       else
       { //que llegue el objeto
        $_SESSION["usuario"]=$json["usuario"]["usuario"];
        $_SESSION["clave"]=$json["usuario"]["clave"];
        $_SESSION["ult_accion"]=time();
        $_SESSION["api_session"]=$json["api_session"];
        
        
        header("Location:index.php");
        exit();
        
       }
      


    }
   

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas</title>
    <style>
        .error{color:red}
    </style>
</head>
<body>
    <h1>Login Notas</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" value="">
            <?php
                if(isset($_POST["btnLogin"])&& $error_usuario)
                {
                    if($_POST["usuario"]=="")
                    {
                        echo "<span class='error'>El campo esta Vacio</span>";
                    }
                    else
                     {
                        echo "<span class='error'>Usuario / contraseña no validas</span>";
                     }

                }
            ?>
        </p>
        <p>
            <label for="clave">Clave:</label>
            <input type="password" name="clave" value="">
            <?php
                  if(isset($_POST["btnLogin"])&& $error_clave)
                  {
                     
                    echo "<span class='error'>El campo esta Vacio</span>";
                       
  
                  }
            ?>
        </p>
        <button type="submit" name="btnLogin">Login</button>
    </form>
</body>
</html>