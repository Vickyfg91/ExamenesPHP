<?php

if(isset($_POST["btnDetalles"]))
{
    $url=DIR_SERV."/usuario/".$_POST["btnDetalles"];
    $respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
    $jon_detalles=json_decode($respuesta,true);
    if(!$jon_detalles)
    {
        session_destroy();
        die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
    }
    if(isset($jon_detalles["error"]))
    {
        session_destroy();
        die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$jon_detalles["error"]."</p>"));
    }

    if(isset($jon_detalles["no_auth"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if(isset($jon_detalles["mensaje_baneo"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }



}



for($hora=1;$hora<=count(HORAS);$hora++)
{
    if($hora!=4)
    {
        $url=DIR_SERV."/usuariosGuardia/".date("w")."/".$hora;
        $respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
        $jon_profesores_guardia=json_decode($respuesta,true);
        if(!$jon_profesores_guardia)
        {
            session_destroy();
            die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
        }
        if(isset($jon_profesores_guardia["error"]))
        {
            session_destroy();
            die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$jon_profesores_guardia["error"]."</p>"));
        }

        if(isset($jon_profesores_guardia["no_auth"]))
        {
            session_unset();
            $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }
        if(isset($jon_profesores_guardia["mensaje_baneo"]))
        {
            session_unset();
            $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
            header("Location:index.php");
            exit;
        }

        $usuarios_guardia[$hora]=$jon_profesores_guardia["usuarios"];

    }
    
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final PHP</title>
    <style>
        .enlinea{display:inline}
        .enlace{background:none;border:none;color:blue;text-decoration: underline;cursor: pointer;}
        .text_centrado{text-align:center}
        .text_izq{text-align:left; padding:0 0.5em}
        .centrado{width:80%;margin:0 auto}
        table, td, th{border:1px solid black}
        table{border-collapse:collapse}
        th{background-color: #CCC;}
    </style>
</head>

<body>
    <h1>Examen Final PHP</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
    <h3>Hoy es <?php echo DIAS[date("w")];?></h3>

    <?php
    echo "<form action='index.php' method='post'>";
    echo "<table class='text_centrado centrado'>";
    echo "<tr>";
    echo "<th>Hora</th>";
    echo "<th>Profesor de Guardia</th>";
    if(isset($_POST["btnDetalles"]))
        echo "<th>Profesor con Id: ".$_POST["btnDetalles"]."</th>";
    else
        echo "<th>Profesor con Id:</th>";
    echo "</tr>";

    for($hora=1;$hora<=count(HORAS);$hora++)
    {
        if($hora!=4)
        {
            echo "<tr>";
            echo "<td>".HORAS[$hora]."</td>";
            echo "<td>";
           
            echo "<ol>";
            foreach($usuarios_guardia[$hora] as $tupla)
            {
                echo "<li><button class='enlace' name='btnDetalles' value='".$tupla["id_usuario"]."'>".$tupla["nombre"]."</button></li>";
            }
            echo "</ol>";
            echo "</td>";
            echo "<td>";
            if($hora==1)
            {
                if(isset($jon_detalles["mensaje"]))
                {
                    echo "<p>El usuario seleccionado ya no se encuentra en la BD</p>";
                }
                elseif(isset($jon_detalles["usuario"]))
                {
                    echo "<p class='text_izq'><strong>Nombre: </strong>".$jon_detalles["usuario"]["nombre"]."</p>";
                    echo "<p class='text_izq'><strong>Usuario: </strong>".$jon_detalles["usuario"]["usuario"]."</p>";
                    echo "<p class='text_izq'><strong>Contraseña: </strong></p>";
                    if(isset($jon_detalles["usuario"]["email"]))
                        echo "<p class='text_izq'><strong>Email: </strong>".$jon_detalles["usuario"]["email"]."</p>";
                    else
                        echo "<p class='text_izq'><strong>Email: </strong>Email no disponible</p>";
                }
            }
            echo "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    echo "</form>";

    ?>
</body>

</html>