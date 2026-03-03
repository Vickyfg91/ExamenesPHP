<?php
if(isset($_POST["btnBorrar"])){

}
    if(isset($_POST["btnEnviar"])){
        $error_nombre=$_POST["nombre"]=="";
        $error_sexo=!isset($_POST["sexo"]);//SI NO EXISTE, SI NO SE HA SELECCIONADO
        $error_comentarios=$_POST["comentarios"]=="";
        $error_foto=$_FILES["foto"]["name"]==""||$_FILES["foto"]["error"]||!getimagesize($_FILES["foto"]["tmp_name"])|| !explode(".",$_FILES["foto"]["name"]) || $_FILES["foto"]["size"] >500 *1024;
        $error_form=$error_nombre||$error_sexo||$error_comentarios||$error_foto;




    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .error{
            color:red
        }
        .oculta{
            display: none;
        }
    </style>
</head>

<body>
   
    <?php
        if(isset($_POST["btnEnviar"]) && !$error_form){
        
            require "vistas/vista_respuestas.php";
        }
        else{
            require "vistas/vista_formulario.php";

        }
    ?>
</body>

</html>