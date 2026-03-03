<?php
session_name("Examen_guardias_24");
session_start();
require "src/funciones_ctes.php";
if(isset($_POST["btnSalir"]))
{
 $datos_env["api_session"]=$_SESSION["api_session"];
 $respuesta=consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env);
 session_destroy();
 header("Location:index.php");
exit;
}



if(isset($_SESSION["usuario"]))
{
    
    require "src/seguridad.php";
    if(count($datos_usuario_log)>0)
    {
        require "vistas/vista_home.php";
    }
    
    
   
}
else
{
    require "vistas/vista_login.php";
}

?>
