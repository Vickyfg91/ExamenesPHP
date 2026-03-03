<?php
$url=DIR_SERV."/obtenerHorario/".$datos_usu_log["id_usuario"];
$respuesta=consumir_servicios_JWT_REST($url,"GET",$headers);
$json_horario=json_decode($respuesta,true);
if(!$json_horario)
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
}
if(isset($json_horario["error"]))
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$json_horario["error"]."</p>"));
}

if(isset($json_horario["no_auth"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}
if(isset($json_horario["mensaje_baneo"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}


foreach($json_horario["horario"] as $tupla)
{
    if(isset($horario[$tupla["dia"]][$tupla["hora"]]))
        $horario[$tupla["dia"]][$tupla["hora"]].="/".$tupla["nombre"];
    else
        $horario[$tupla["dia"]][$tupla["hora"]]=$tupla["nombre"];
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
    <h2>Su Horario</h2>
    <h3 class="text_centrado">Horario del Profesor: <?php echo $datos_usu_log["nombre"]; ?></h3>

    <?php
    echo "<table class='text_centrado centrado'>";
    echo "<tr>";
    echo "<th></th>";
    for($i=1;$i<=count(DIAS);$i++)
        echo "<th>".DIAS[$i]."</th>";
    echo "</tr>";
    for($hora=1;$hora<=count(HORAS);$hora++)
    {
        echo "<tr>";
        echo "<th>".HORAS[$hora]."</th>";

        if($hora==4)
        {
            echo "<td colspan='5'>RECREO</td>";
        }
        else
        {
            //más 5 td
            for($dia=1;$dia<=count(DIAS);$dia++)
            {
                if(isset($horario[$dia][$hora]))
                    echo "<td>".$horario[$dia][$hora]."</td>";
                else
                    echo "<td></td>";
            }
                
        }
        

        echo "</tr>";
    }

    echo "</table>";

    ?>
</body>

</html>