<?php
session_name("Examen_4_Notas");
session_start();
require "src/func_ctes.php";
//si el usuario es tipo tutor
if(isset($_POST["btnSalir"]))
{
    $datos_env["api_session"]=$_SESSION["api_session"];
    session_destroy();
    consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env);
    header("Location:index.php");
    exit();
}
if (isset($_SESSION["usuario"])) {
    $salto="index.php";
    //seguridad
   require "src/seguridad.php";
    if ($datos_usuario_log["tipo"] == "alumno") {
        //vista alumno
        require "vistas/vista_normal.php";
    } else //sino 
    {
        //vista_tutor
        header("Location:admin/index.php");
        exit;
    }
}
else{
    require "vistas/vista_login.php";
}



