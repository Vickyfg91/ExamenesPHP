<?php

const  DIAS= array(1=>"Lunes","Martes","Miércoles","Jueves","Viernes");

if(isset($_POST["dia"]))
{
    $headers[]="Authorization: Bearer ".$_SESSION["token"];
    $url=DIR_SERV."/usuariosGuardia/".$_POST["dia"]."/".$_POST["hora"];
    $respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
    $json_usuariosGuardia=json_decode($respuesta,true);
    if(!$json_usuariosGuardia)
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
    }
    if(isset($json_usuariosGuardia["error"]))
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$json_usuariosGuardia["error"]."</p>"));
    }

    if(isset($json_usuariosGuardia["no_auth"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    //Aquí ya tengo $json_usuariosGuardia["usuarios"]


}

if(isset($_POST["usuario"]))
{
    $headers[]="Authorization: Bearer ".$_SESSION["token"];
    $url=DIR_SERV."/usuario/".$_POST["usuario"];
    $respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
    $json_usuario=json_decode($respuesta,true);
    if(!$json_usuario)
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
    }
    if(isset($json_usuario["error"]))
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$json_usuario["error"]."</p>"));
    }

    if(isset($json_usuario["no_auth"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    //Aquí ya tengo $json_usuario["usuario"]
}




//
$headers[]="Authorization: Bearer ".$_SESSION["token"];
$url=DIR_SERV."/deGuardia/".$datos_usu_log["id_usuario"];
$respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
$json_deGuardia=json_decode($respuesta,true);
if(!$json_deGuardia)
{
     session_destroy();
     die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
}
if(isset($json_deGuardia["error"]))
{
     session_destroy();
     die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$json_deGuardia["error"]."</p>"));
}

if(isset($json_deGuardia["no_auth"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}

foreach($json_deGuardia["de_guardia"] as $tupla)
{
    
    $deGuardia[$tupla["dia"]][$tupla["hora"]]=true;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Guardias</title>
    <style>
        .enlinea{display:inline}
        .enlace{background:none;border:none;color:blue;text-decoration: underline;cursor: pointer;}
        table{border-collapse:collapse;width:100%;text-align: center;}
        table, td, th{border:1px solid black}
        th{background-color:#CCC}
        .informacion{padding:0.5em; text-align: left;}
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
    <h2>Equipos de Guardias del IES Mar de Alborán</h2>
    <?php
    
    $equipo=1;
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    for($i=1;$i<=5;$i++)
    {
        echo "<th>".DIAS[$i]."</th>";
    }
    echo "</tr>";
    
    for($hora=1;$hora<=6;$hora++)
    {
        
        if($hora==4)
        {
            echo "<tr><td colspan='6'>RECREO</td></tr>";
        }
        echo "<tr>";
        echo "<td>".$hora."º Hora</td>";
        for($dia=1;$dia<=5;$dia++)
        {
            if(isset($deGuardia[$dia][$hora]))
            {
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='".$dia."'>";
                echo "<input type='hidden' name='hora' value='".$hora."'>";
                echo "<input type='hidden' name='equipo' value='".$equipo."'>";
                echo "<button class='enlace'>Equipo ".$equipo."</button>";
                echo "</form>";
                echo "</td>";
            }
            else
                echo "<td></td>";
            $equipo++;


        }
        echo "</tr>";
    }

    echo "</table>";


    if(isset($_POST["dia"]))
    {
        echo "<h2>EQUIPO DE GUARDIA ".$_POST["equipo"]."</h2>";
        echo "<h3>".DIAS[$_POST["dia"]]." a ".$_POST["hora"]."º Hora</h3>";

        echo "<table>";
        echo "<tr>";
        echo "<th>Profesores de Guardia</th>";
        if(isset($_POST["usuario"]))
            echo "<th>Información del Profesor con id_usuario: ".$_POST["usuario"]."</th>";
        else
            echo "<th>Información del Profesor con id_usuario:</th>";
        echo "</tr>";
        $usuarios_guardia=count($json_usuariosGuardia["usuarios"]);
        $primera=true;
        foreach($json_usuariosGuardia["usuarios"] as $tupla)
        {
            echo "<tr>";
            echo "<td>";
            echo "<form action='index.php' method='post'>";
            echo "<input type='hidden' name='dia' value='".$_POST["dia"]."'>";
            echo "<input type='hidden' name='hora' value='".$_POST["hora"]."'>";
            echo "<input type='hidden' name='equipo' value='".$_POST["equipo"]."'>";
            echo "<button class='enlace' name='usuario' value='".$tupla["id_usuario"]."'>".$tupla["nombre"]."</button>";
            echo "</form>";
            echo "</td>";
            if($primera)
            {
                if(isset($_POST["usuario"]))
                {
                    echo "<td class='informacion' rowspan='".$usuarios_guardia."'>";
                    echo "<p><strong>Nombre: </strong>".$json_usuario["usuario"]["nombre"]."</p>";
                    echo "<p><strong>Usuario: </strong>".$json_usuario["usuario"]["usuario"]."</p>";
                    echo "<p><strong>Contraseña: </strong></p>";
                    if(isset($json_usuario["usuario"]["email"]))
                        echo "<p><strong>Email: </strong>".$json_usuario["usuario"]["email"]."</p>";
                    else
                        echo "<p><strong>Email: </strong>Email no disponible</p>";
                    echo "</td>";
                }
                else
                    echo "<td rowspan='".$usuarios_guardia."'></td>";
                $primera=false;
            }    
            echo "</tr>";
        }

        echo "</table>";
    }
    ?>
</body>

</html>