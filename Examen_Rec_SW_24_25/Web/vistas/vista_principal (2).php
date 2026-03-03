<?php
if(isset($_POST["btnInfo"]))
{
    $url=DIR_SERV."/usuario/".$_POST["btnInfo"];
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
}


if(isset($_POST["equipo"]))
{
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
}




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

foreach($json_respuesta["de_guardia"] as $tupla)
{
    $horario[$tupla["dia"]][$tupla["hora"]]=true;
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
        table{
            width:80%;margin:0 auto;text-align: center;border-collapse:collapse;
        }
        table,td,th{border:1px solid black}
        th{background-color:#CCC}
        .txt_izq{text-align: left;padding:0.25em}
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
   
    <h2>Equipos de Guardia del IES Mar de Alborán</h2>

    <?php
    $dias[]="";
    $dias[]="Lunes";
    $dias[]="Martes";
    $dias[]="Miércoles";
    $dias[]="Jueves";
    $dias[]="Viernes";


    $horas[1]="8:15-9:15";
    $horas[]="9:15-10:15";
    $horas[]="10:15-11:15";
    $horas[]="11:45-12:45";
    $horas[]="12:45-13:45";
    $horas[]="13:45-14:45";

    echo "<table>";
    echo "<tr>";
    for($i=0;$i<count($dias);$i++)
        echo "<th>".$dias[$i]."</th>";
    echo "</tr>";

    $equipo=1;
    for($hora=1;$hora<=count($horas);$hora++)
    {
        echo "<tr>";
        echo "<td>".$horas[$hora]."</td>";
        //después 5 td más
        
        
        for($dia=1;$dia<count($dias);$dia++)
        {
            if(isset($horario[$dia][$hora]))
            {
                echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='equipo' value='".$equipo."'/>";
                echo "<input type='hidden' name='dia' value='".$dia."'/>";
                echo "<input type='hidden' name='hora' value='".$hora."'/>";
                echo "<button class='enlace' name='btnEquipo'>Equipo".$equipo."</button></form></td>";
            } 
            else
                echo "<td></td>";

            $equipo++;
        }
        
        
        echo "</tr>";
        if($hora==3)
        {
            echo "<tr><td>11:15-11:45</td><td colspan='5'>RECREO</td></tr>";
        }
    }
    echo "</table>";


    if(isset($_POST["equipo"]))
    {
        echo "<h1>EQUIPO DE GUARDIA ".$_POST["equipo"]."</h1>";
        echo "<h2>".$dias[$_POST["dia"]]." a ".$_POST["hora"]."º hora</h2>";

        

        echo "<table>";
        echo "<tr>";
        echo "<th>Profesores de Guardia</th>";
        if(isset($json_usuario["usuario"]))
            echo "<th>Información del Profesor con id_usuario: ".$json_usuario["usuario"]["id_usuario"]."</th>";
        elseif(isset($json_usuario["mensaje"]))
            echo "<th>Información del Profesor con id_usuario: ".$_POST["btnInfo"]."</th>";
        else
            echo "<th>Información del Profesor con id_usuario: </th>";

        echo "</tr>";
        $primera=true;
        foreach($json_usuarios["usuarios"] as $tupla)
        {
            echo "<tr>";
            if($primera)
            {
                echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='equipo' value='".$_POST["equipo"]."'/>";
                echo "<input type='hidden' name='dia' value='".$_POST["dia"]."'/>";
                echo "<input type='hidden' name='hora' value='".$_POST["hora"]."'/>";
                echo "<button class='enlace' name='btnInfo' value='".$tupla["id_usuario"]."'>".$tupla["nombre"]."</button></form></td>";
                
                echo "<td rowspan='".count($json_usuarios["usuarios"])."'>";
                if(isset($json_usuario))
                {
                    if(isset($json_usuario["mensaje"]))
                    {
                        echo "<p>El profesor seleccionado ya no se encuentra en la BD</p>";
                    }
                    else
                    {
                        echo "<p class='txt_izq'><strong>Nombre: </strong>".$json_usuario["usuario"]["nombre"]."</p>";
                        echo "<p class='txt_izq'><strong>Usuario: </strong>".$json_usuario["usuario"]["usuario"]."</p>";
                        echo "<p class='txt_izq'><strong>Contraseña: </strong></p>";
                        echo "<p class='txt_izq'><strong>Email: </strong>".$json_usuario["usuario"]["email"]."</p>";
                    }
                }
                echo "</td>";
                $primera=false;
            }
            else
            {
                echo "<td><form action='index.php' method='post'>";
                echo "<input type='hidden' name='equipo' value='".$_POST["equipo"]."'/>";
                echo "<input type='hidden' name='dia' value='".$_POST["dia"]."'/>";
                echo "<input type='hidden' name='hora' value='".$_POST["hora"]."'/>";
                echo "<button class='enlace' name='btnInfo' value='".$tupla["id_usuario"]."'>".$tupla["nombre"]."</button></form></td>";
            
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>

</html>