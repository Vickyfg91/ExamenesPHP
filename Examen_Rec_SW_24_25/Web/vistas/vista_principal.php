
<?php
if(isset($_POST["id_usuario"]))
{
    $headers[]="Authorization: Bearer ".$_SESSION["token"];
    $url=DIR_SERV."/usuario/".$_POST["id_usuario"];
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
    if(isset($json_usuario["mensaje_baneo"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }
}



if(isset($_POST["dia"]))
{
    $headers[]="Authorization: Bearer ".$_SESSION["token"];
    $url=DIR_SERV."/usuariosGuardia/".$_POST["dia"]."/".$_POST["hora"];
    $respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
    $json_usuarios=json_decode($respuesta,true);
    if(!$json_usuarios)
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
    }
    if(isset($json_usuarios["error"]))
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$json_usuarios["error"]."</p>"));
    }

    if(isset($json_usuarios["no_auth"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if(isset($json_usuarios["mensaje_baneo"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }
}



$headers[]="Authorization: Bearer ".$_SESSION["token"];
$url=DIR_SERV."/deGuardia/".$datos_usu_log["id_usuario"];
$respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
$json_respuesta=json_decode($respuesta,true);
if(!$json_respuesta)
{
     session_destroy();
     die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
}
if(isset($json_respuesta["error"]))
{
     session_destroy();
     die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$json_respuesta["error"]."</p>"));
}

if(isset($json_respuesta["no_auth"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}
if(isset($json_respuesta["mensaje_baneo"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}

foreach($json_respuesta["de_guardia"] as $tupla)
{
    $de_guardia[$tupla["dia"]][$tupla["hora"]]=true;
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
        table, td, th{border:1px solid black}
        table{border-collapse:collapse;text-align:center;width:80%;margin:0 auto}
        th{background-color: #CCC;}
        .text_izq{text-align:left}
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
   <h2>Equipos de Guardias del IES Mar Alborán</h2>
   <?php

    $dias[1]="Lunes";
    $dias[]="Martes";
    $dias[]="Miercoles";
    $dias[]="Jueves";
    $dias[]="Viernes";

    $indice=1;
    echo "<table>";
    echo "<tr>";
    echo "<th></th>";
    for($i=1;$i<=5;$i++)
    {
        echo "<th>".$dias[$i]."</th>";
    }
    echo "</tr>";

    for($hora=1;$hora<=6;$hora++)
    {
        if($hora==4)
        {
          echo "<tr><td colspan='6'>RECREO</td></tr>";  
        }

        echo "<tr>";

        echo "<td>".$hora." Hora</td>";
        for($dia=1;$dia<=5;$dia++)
        {
            if(isset($de_guardia[$dia][$hora]))
            {
                echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='".$dia."'>";
                echo "<input type='hidden' name='hora' value='".$hora."'>";
                echo "<input type='hidden' name='equipo' value='".$indice."'>";
                echo "<button class='enlace'>Equipo ".$indice."</button>";
                echo "</form></td>";
            }   
            else
                echo "<td></td>";

            $indice++;
        }
        echo "</tr>";
    }
    echo "</table>";

    if(isset($_POST["dia"]))
    {
        echo "<h2> GUARDIA ".$_POST["equipo"]."</h2>";
        echo "<h3>".$dias[$_POST["dia"]]." a ".$_POST["hora"]." Hora</h3>";

        echo "<table>";
        echo "<tr><th>Profesores de Guardia</th><th>Info profesor con id_usuario:";
        if(isset($_POST["id_usuario"]))
            echo $_POST["id_usuario"];
        echo "</th></tr>";
        $fila_prim=true;
        foreach($json_usuarios["usuarios"] as $tupla)
        {
            echo "<tr>";
            echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='dia' value='".$_POST["dia"]."'>";
                echo "<input type='hidden' name='hora' value='".$_POST["hora"]."'>";
                echo "<input type='hidden' name='equipo' value='".$_POST["equipo"]."'>";
                echo "<input type='hidden' name='id_usuario' value='".$tupla["id_usuario"]."'>";
                echo "<button class='enlace'>".$tupla["nombre"]."</button>";
                echo "</form></td>";
          
            if($fila_prim)
             {
                echo "<td rowspan='".count($json_usuarios["usuarios"])."'>";
                if(isset($_POST["id_usuario"]))
                {
                    echo "<p class='text_izq'><strong>Nombre: </strong>".$json_usuario["usuario"]["nombre"]."</p>";
                    echo "<p class='text_izq'><strong>Usuario: </strong>".$json_usuario["usuario"]["usuario"]."</p>";
                    echo "<p class='text_izq'><strong>Contraseña:</p>";
                    echo "<p class='text_izq'><strong>Email: </strong>";
                    if(isset($json_usuario["usuario"]["email"]))
                        echo $json_usuario["usuario"]["email"];
                    echo "</p>";
                }
                echo "</td>";
                $fila_prim=false;
             }
            echo "</tr>";
        }
        echo "</table>";
    }
   ?>
</body>

</html>