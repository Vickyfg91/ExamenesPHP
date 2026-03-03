<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Práctica 1 - Rec</title>
        <style>
            .error{
                color:red
            }
        </style>
    </head>
    <body>
        <h1>DATOS ENVIADOS</h1>
            <p><strong>Nombre: </strong><?php echo $_POST["nombre"]?></p>
            <p><strong>Nacido en: </strong><?php echo $_POST["nacido"]?></p>
            <p><strong>Sexo: </strong><?php echo $_POST["sexo"]?></p>
            <?php
            if(isset($_POST["aficiones"]))
            {
                echo "<p>Las aficiones marcadas son:</p>";
                echo"<ol>";
                for ($i=0; $i <count($_POST["aficiones"]) ; $i++) { 
                    echo "<li>".$_POST["aficiones"][$i]."</li>";
                }
                echo"</ol>";

            }
            else{
                echo"<p>No has seleccionado ninguna afición</p>";
            }

            echo "<p>El comentario realizado  ha sido: <strong>".$_POST["comentarios"]."</strong></p>";

            if($_FILES["foto"]["name"]!="")
            {
                $array_ext=explode(".",$_FILES["foto"]["name"]);
                $ext=".".strtolower(end($array_ext));
                $nombre_nuevo=md5(uniqid(uniqid(),true));
                $nombre_foto=$nombre_nuevo.$ext;
                @$var=move_uploaded_file($_FILES["foto"]["tmp_name"],"images/".$nombre_foto);


                echo "<h3>Foto</h3>";
                echo "<p><strong>Nombre: </strong>" . $_FILES["foto"]["name"] . "</p>";
                echo "<p><strong>Tipo: </strong>" . $_FILES["foto"]["type"] . "</p>";
                echo "<p><strong>Tamanio: </strong>" . $_FILES["foto"]["size"] . "</p>";
                echo "<p><strong>Error: </strong>" . $_FILES["foto"]["error"] . "</p>";
                echo "<p><strong>El tmp_name: </strong>" . $_FILES["foto"]["tmp_name"] . "</p>";
                echo "<p>La imagen subida con exito</p>";
                echo "<p><img class='tan_img' src='images/" . $nombre_foto . "' alt='Foto' title='Foto'/></p>";
            } else {
                echo "<span> No se ha podido mover la imgen a la carpeta destino en el servidor</span>";


            }


            ?>
    </body>
</html>
