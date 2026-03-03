<?php
$id_usuario=$datos_usu_log["id_usuario"];
    $url = DIR_SERV . "/horarioProfesor/".$id_usuario;
    $headers[]="Authorization: Bearer ".$_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url,"GET",$headers);
    $json_profesor = json_decode($respuesta, true);

if(!$json_profesor)
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
}
if(isset($json_profesor["error"]))
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$json_profesor["error"]."</p>"));
}

if(isset($json_profesor["no_auth"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}
if(isset($json_profesor["mensaje_baneo"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}
$horario_profesor=$json_profesor["horario"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final PHP</title>
    <style>
        .enlinea {
            display: inline
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
        table,th, td{border:1px solid black;}
        table{border-collapse:collapse;text-align:center}
        th{background-color:#CCC}
        .horas{background-color:#CCC}
    </style>
</head>

<body>
    <h1>Examen Final PHP</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>

    <h2><strong>Su horario</strong></h2>
<?php
    echo"<h3>Horario del profesor: ". $datos_usu_log["nombre"]."</h3>";
    $dias[0]="";
    $dias[1]="Lunes";
    $dias[2]="Martes";
    $dias[3]="Miercoles";
    $dias[4]="Jueves";
    $dias[5]="Viernes";

    $horas[1]="8:15-9:15";
    $horas[2]="9:15-10:15";
    $horas[3]="10:15-11:15";
    $horas[4]="11:15-11:45";
    $horas[5]="11:45_12:45";
    $horas[6]="12:45-13:45";
    $horas[7]="13:45-14:45";

      echo "<table>";
    echo"<tr>";
    for ($i=0; $i <count($dias) ; $i++) { 
        echo"<th>".$dias[$i]."</th>";
    }
    echo"</tr>";
   
    for ($hora=1; $hora <=7 ; $hora++) { 
        echo"<tr>";
        echo"<td>".$horas[$hora]."</td>";
          if($hora==4)
          {
            echo"<td colspan='5'>RECREO</td>";
          }
          else
          {
            for ($dia=1; $dia <=5 ; $dia++) { 
                echo"<td>";
              for ($i=0; $i <count($horario_profesor) ; $i++) { 
                if($horario_profesor[$i]["dia"]==$dia && $horario_profesor[$i]["hora"]==$hora)
                {
                  echo "".$horario_profesor[$i]["grupo"]."</br>";  
                  echo "(".$horario_profesor[$i]["aula"].")";  
                }
              }
              echo"</td>";
              }
          }
          
     echo"</tr>";
    }
    echo "</table>";

    ?>
</body>

</html>