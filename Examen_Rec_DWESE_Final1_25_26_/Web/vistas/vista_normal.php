<?php
    const DIAS=array(1=>"Lunes","Martes","Miércoles","Jueves","Viernes");
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
        table{border-collapse:collapse; width: 90%; text-align:center}
        table, td, th{border: 1px solid black; }
        th{background-color: #ccc}
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
    <h2>Equipos de guardia del IES MAR DE ALBORAN</h2>
    <?php
    $equipos=1;
    echo "<table>";
        echo "<tr>";
        echo "<th></th>";
        for ($i=1;$i<=5;$i++){
            echo "<th>".DIAS[$i]."</th>";
        }
        echo "</tr>";
        for ($hora=1; $hora<=7;$hora++){
            echo "<tr>";
            if ($hora!=4){
            echo "<td>".$hora."º Hora</td>";
            for($dia=1;$dia<=5;$dia++){
                echo "<td>Equipo ".$equipos."</td>";
                $equipos++;
            }

            }else{
                echo "<td colspan='6'>RECREO</td>";
            }
            echo "</tr>";
        }

    echo "</table>";

    ?>

</body>

</html>