<?php
session_name("ExamenRec_Horarios_SW_23_24");
session_start();
 require "src/funciones_ctes.php";

 if(isset($_POST["btnSalir"]))
 {
    $datos_env["api_session"];
    consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env); // datos enviados siempre se envia un array
    session_destroy();
    header("Location:index.php");
    exit;
 }

 if(isset($_SESSION["usuario"]))
 {
    //si estoy logeado hago la seguridad
    require "src/seguridad.php";

    require "vistas/vista_examen.php";
 }
else
{
 
require "vistas/vista_login.php";
}

?>
