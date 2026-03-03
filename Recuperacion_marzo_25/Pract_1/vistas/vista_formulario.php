<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 1- Rec</title>
</head>
<body>
    <h1>Rellena tu CV</h1>
    <form action="index.html" method="post" enctype="multipart/form-data">
        <p>
            <label for="usuario">Usuario: </label><br/>
            <input type="text" name="usuario" id="usuario" value="<?php /*if(isset($_POST*/?>" placeholder="Usuario..."/><br/>
        </p>
        <p>
            <label for="nombre">Nombre: </label><br/>
            <input type="text" name="nombre" id="nombre" value="" placeholder="Nombre..."/><br/>
        </p>
        <p>
            <label for="clave">Contraseña:</label><br/>
            <input type="password" name="clave" id="clave" value="" placeholder="Contraseña..."/><br/>
        </p>
        <p>
        <label for="dni">DNI:</label><br/>
        <input type="text" name="dni" id="dni" value="" placeholder="DNI: 11223344Z"/><br/>
        </p>
        <p>
        <label for="sexo">Sexo</label><br/>
        <input type="radio" id="hombre" name="sexo" value="hombre" checked>
        <label for="hombre">Hombre</label><br/>
        <input type="radio" id="mujer" name="sexo" value="mujer">
        <label for="mujer">Mujer</label><br/>
        </p>
        <p>
        <label for="foto">Incluir mi foto (Max. 500kb):</label>
        <input type="file" id="foto" name="foto" accept="image/"><br>
        </p>
        <p>
        <input type="checkbox" id="subscribcion" name="subscribcion">
        <label for="subscribcion">Suscribirme al boletín de novedades</label><br>
        </p>
        <p>
        <button type="submit" name="btnEnviar" id="btnEnviar">Guardar cambios</button>
        <button type="reset" name="btnReset">Borrar los datos introducidos</button>
        </p>
    </form>    
</body>
</html>
