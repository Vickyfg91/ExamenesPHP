<?php
if(isset($_POST["grupitos"])){
    $url = DIR_SERV ."/horarioGrupo/".$_POST["grupitos"];
    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_horariosGrupo = json_decode($respuesta, true);
    
    if(!$json_horariosGrupo)
    {
        session_destroy();
        die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
    }
    if(isset($json_horariosGrupo["error"]))
    {
        session_destroy();
        die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$json_horariosGrupo["error"]."</p>"));
    }

    if(isset($json_horariosGrupo["no_auth"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }
    if(isset($json_horariosGrupo["mensaje_baneo"]))
    {
        session_unset();
        $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
        header("Location:index.php");
        exit;
    }
    $grupos = $json_horariosGrupo["grupos"];

}

$url = DIR_SERV . "/grupos";
$headers[] = "Authorization: Bearer " . $_SESSION["token"];
$respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
$json_grupos = json_decode($respuesta, true);

if(!$json_grupos)
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>".$url."</strong></p>"));
}
if(isset($json_grupos["error"]))
{
     session_destroy();
     die(error_page("Examen Final PHP","<h1>Examen Final PHP</h1><p>".$json_grupos["error"]."</p>"));
}

if(isset($json_grupos["no_auth"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}
if(isset($json_grupos["mensaje_baneo"]))
{
    session_unset();
    $_SESSION["mensaje_seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:index.php");
    exit;
}
$grupitos = $json_grupos["grupos"];

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

    <h2><strong>Horario de los Grupos</strong></h2>
    <div>Elija un grupo:</div>
    <form action="index.php" method="post">
        <select name="grupitos" id="grupitos" required>
          <?php
            foreach($grupitos as $tupla)
             
            {
              $selected = (isset($_POST["grupitos"]) && $_POST["grupitos"] == $tupla["id_grupo"]) ? "selected" : "";
                echo "<option value='" . $tupla["id_grupo"] . "' $selected>" . $tupla["nombre"] . "</option>";
                
            }
          ?>
        </select>
    <button type="submit" name="btnVerHorario">Ver Horario</button>
        </form>
        <?php
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
        echo "<tr><th></th>";
        for ($i = 1; $i < count($dias); $i++) {
            echo "<th>" . $dias[$i] . "</th>";
        }
        echo "</tr>";

        for ($hora = 1; $hora <= 7; $hora++) {
            echo "<tr>";
            echo "<td>" . $horas[$hora] . "</td>";
            if ($hora == 4) {
                echo "<td colspan='5'>RECREO</td>";
            } else {
                for ($dia = 1; $dia <= 5; $dia++) {
                    echo "<td>";
                    foreach ($grupos as $clase) {
                        if ($clase["dia"] == $dia && $clase["hora"] == $hora) {
                            echo $clase["nombre"] . " (" . $clase["aula"] . ")<br/>";
                        }
                    }
                    echo "<form action='index.php' method='post'>";
                    echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                    echo "<input type='hidden' name='dia' value='" . $dia . "'>";
                    echo "<input type='hidden' name='hora' value='" . $hora . "'>";
                    echo "<input type='hidden' name='grupitos' value='" . $_POST["grupitos"] . "'>";
                    echo "</form>";
                    echo "</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    
    ?>
</body>