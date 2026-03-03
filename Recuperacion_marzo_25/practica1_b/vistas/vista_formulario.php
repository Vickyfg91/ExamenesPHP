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
<h1>Segundo Formulario</h1>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php if(isset($_POST["nombre"])) echo $_POST["nombre"] ?>">
            <?php
                if(isset($_POST["btnEnviar"])&& $error_nombre){
                    echo "<span class='error'>*Campo obligatorio*</span>";
                }
            ?>
        </p>
        <p>
            <label for="nacido">Nacido en :</label>
            <select name="nacido" id="nacido">
                <option value="malaga" <?php if(isset($_POST["nacido"])&& $_POST["nacido"]=="malaga") echo "selected";?>>Málaga</option>
                <option value="cadiz" <?php if(isset($_POST["nacido"])&& $_POST["nacido"]=="cadiz") echo "selected";?>>Cádiz</option>
                <option value="granada" <?php if(isset($_POST["nacido"])&& $_POST["nacido"]=="granada") echo "selected";?>>Granada</option>
            </select>
        </p>

        <p>
            Sexo:
            <label for="hombre">Hombre</label>
            <input type="radio" id="hombre" name="sexo" value="hombre" <?php if(isset($_POST["sexo"])&& $_POST["sexo"]=="hombre") echo "checked";?>>
            <label for="mujer">Mujer</label>
            <input type="radio" id="mujer" name="sexo" value="mujer" <?php if(isset($_POST["sexo"])&& $_POST["sexo"]=="mujer") echo "checked";?>>
            <?php
                if(isset($_POST["btnEnviar"])&& $error_sexo){
                    echo "<span class='error'>*Campo obligatorio*</span>";
                }
            ?>
        </p>

        <p>
            Aficiones:
            <label for="deportes">Deportes</label>
            <input type="checkbox" id="deportes" name="aficiones[]" value="deportes" <?php if(isset($_POST["aficiones"])&& in_array("deportes", $_POST["aficiones"])) echo "checked";?>>
            <label for="lectura">Lectura</label>
            <input type="checkbox" id="lectura" name="aficiones[]" value="lectura" <?php if(isset($_POST["aficiones"])&& in_array("lectura", $_POST["aficiones"])) echo "checked";?>>
            <label for="otros">Otros</label>
            <input type="checkbox" id="otros" name="aficiones[]" value="otros" <?php if(isset($_POST["aficiones"])&& in_array("otros", $_POST["aficiones"])) echo "checked";?>>
        </p>
        <p>
            <label for="comentarios">Comentarios:</label>
            <textarea id="comentarios" name="comentarios" ><?php if(isset($_POST["comentarios"])) echo $_POST["comentarios"]?></textarea>
            <?php
                if(isset($_POST["btnEnviar"])&& $error_comentarios){
                    echo "<span class='error'>*Campo obligatorio*</span>";
                }
            ?>
        </p>
        <p>
            <label for="foto">Incluir mi foto (foto de tipo imagen Máx 500KB)</label>
            <input class="oculta" type="file" id="foto" onchange="document.getElementById('nombre_foto').innerHTML=' '+document.getElementById('foto').files[0].name;" name="foto"accept="image/*" >
            <button onclick="event.preventDefault(); document.getElementById('foto').click();">Examinar</button>
            <span id="nombre_foto">
            <?php
                    if (isset($_POST["btnEnviar"]) && $error_foto) {
                        
                            if ($_FILES["foto"]["name"] == "") {
                                echo " <span class='error'> debes de seleccionar un foto</span>";
                            }
                            else if ($_FILES["foto"]["error"]) {
                                echo " <span class='error'> No se ha podido subir el foto al servidor</span>";
                            } elseif (!getimagesize($_FILES["foto"]["tmp_name"])) {

                                echo " <span class='error'>El foto subido debe de ser una imagen </span>";
                            } elseif(!explode(".",$_FILES["foto"]["name"])) {
                                echo " <span class='error'> El foto tiene que tener extension</span>";
                            }else{
                                echo " <span class='error'> El foto seleccionado supera los 500 KB MAX</span>";
                            }
                        }
                    
                    ?>
                    </span>
        </p>
        <p>
            <input type="submit"  name="btnEnviar" value="Enviar">
            <input type="submit"  name="btnBorrar" value="Borrar">
        </p>
    </form>
</body>
</html>