<?php
session_name("Examen_4_Notas");
session_start();
require "../src/func_ctes.php";

if (isset($_SESSION["usuario"])) 
{
    $salto = "../index.php"; // importante
    require "../src/seguridad.php";
    if ($datos_usuario_log["tipo"]== "tutor") 
    {
        require "../vistas/vista_admin.php";
    }
    else
    {
 
        header("location:../index.php");
        exit;
    }
} 
else 
{
    header("location:../index.php");
    exit;
}
