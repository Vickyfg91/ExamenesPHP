<?php
session_name("Examen_Rec_Final_DWESE_23_24");
session_start();

require "src/funciones_ctes.php";


if(isset($_POST["btnSalir"]))
{
    //Salir de la API
    $datos_env["api_session"]=$_SESSION["api_session"];
    consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env);
    //Salgo de mi aplicación
    session_destroy();
    header("Location:index.php");
    exit();
}


if(isset($_SESSION["usuario"]))
{
   require "src/seguridad.php";

  
    require "vistas/vista_examen.php";
}
else
    require "vistas/vista_login.php";



?>