<?php

require "src/funciones_ctes.php";

if(isset($_POST["btnEnviar"])){
    $error_usuario = $_POST["usuario"] == "";
    $error_nombre = $_POST["nombre"] == "";
    $error_clave = $_POST["clave"] == "";
    $error_dni = $_POST["dni"] == "" || strlen($_POST["dni"]) != 9 ;
    $error_foto = $_FILES["foto"]["name"] !="" &&  ($_FILES["foto"]["error"]  || !tiene_extension($_FILES["foto"]["size"]>500*1024 || !getimagesize($_FILES[""]))); 
    $error_subscripcion = !isset($_POST["boletin"]);

    $errores_form = $error_usuario || $error_nombre || $error_clave || $error_dni || $error_foto || $error_subscripcion;
}

if(isset($_POST["btnEnviar"]) && !$errores_form){
    require "vistas/vista_respuestas.php"; 
}else{
    require "vistas/vista_formulario.php";
}
?>
