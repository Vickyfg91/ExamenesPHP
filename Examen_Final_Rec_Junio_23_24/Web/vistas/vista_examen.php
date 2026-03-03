<?php
    $id_usuario=$datos_usuario_log["id_usuario"];

  $respuesta=consumir_servicios_REST(DIR_SERV.'/usuario/'.$id_usuario,"GET",$datos_env);
  $obj=json_decode($respuesta,true);
  if(!$obj)
  {
      session_destroy();
      die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio: API ussuario</p>"));
  }

  if(isset($obj["error"]))
  {
      session_destroy();
      die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$obj["error"]."</p>"));
  }

 $usuario=$obj["usuario"];

 if(isset($_POST["btnEquipo"]))
 {
    $dia=$_POST["dia"];
    $hora=$_POST["hora"];
    
    $respuesta=consumir_servicios_REST(DIR_SERV.'/usuariosGuardia/'.$dia."/".$hora,"GET",$datos_env);
    $obj=json_decode($respuesta,true);
    if(!$obj)
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio: API ussuario</p>"));
    }
  
    if(isset($obj["error"]))
    {
        session_destroy();
        die(error_page("Gestión de Guardias","<h1>Gestión de Guardias</h1><p>".$obj["error"]."</p>"));
    }
  
   $de_guardia=$obj["de_guardia"];
  


 }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Guardias</title>
    <style>
        .en_linea{display:inline}
        .enlace{border:none;background:none;color:blue;text-decoration:underline;cursor:pointer}
        .mensaje{font-size:1.25em;color:blue}
        table,th, td{border:1px solid black;}
        table{border-collapse:collapse;text-align:center}
        th{background-color:#CCC}
    </style>
</head>
<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usuario_log["usuario"];?></strong> - 
        <form class="en_linea" action="index.php" method="post">
            <button class="enlace" name="btnSalir" type="submit">Salir</button>
        </form>
    </div>
  <?php
  $dias[0]="";
  $dias[1]="Lunes";
  $dias[2]="Martes";
  $dias[3]="Miercoles";
  $dias[4]="Jueves";
  $dias[5]="viernes";


  $horas[1]="1º Hora";
  $horas[2]="2º Hora";
  $horas[3]="3º Hora";
  $horas[4]="4º Hora";
  $horas[5]="5º Hora";
  $horas[6]="6º Hora";

    echo "<h2>Equipos de Guardia del IES Mar de Alboran</h2>";


    echo"<table>";
   /*
    echo"<tr>";
    for ($j=0; $j <=5 ; $j++) { 
        echo"<th>".$dias[$j]."</th>";
    }
    
    echo"</tr>";
   */
    echo"<tr><th></th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th></tr>";
    $numero=1;
    for ($hora=1; $hora <=6 ; $hora++) { 
        echo"<tr>";
        echo"<td>".$horas[$hora]."</td>";
        /* if($hora==4)
        {
            echo"<td colsapn='6'>RECREO</td>";
        }
        else
        {
        }*/
      
        for ($dia=1; $dia <=5 ; $dia++) { 
          
            echo"<td>";
            for ($i=0; $i <count($usuario) ; $i++) { 
                if($usuario[$i]["dia"]==$dia && $usuario[$i]["hora"]==$hora)
                {
                  
                    echo"<form action='index.php' method='post'>";
                    echo"<input type='hidden' name=dia value='".$dia."'>";
                    echo"<input type='hidden' name=hora value='".$hora."'>";
                    echo"<input type='hidden' name=numero value='".$numero."'>";
                    echo"<button class='enlace' type='submit' name='btnEquipo'>Equipo".$numero."</button>";
                    echo"</form>";
                   
                }
               
            }
           
            echo"</td>";
            $numero++;
        }
       
        echo"</tr>";

    }
    echo"</table>";
  
   if(isset($_POST["btnEquipo"]))
   {
      echo"<h2>Equipo de guardia ".$_POST["numero"]."</h2>";
   echo"<table>";
   echo"<tr><th>Profesores Guardia</th><th>Informacion del profesor con id_ usuario: </th></tr>";
    foreach($de_guardia as $tupla)
    {   
        echo"<tr>";
      echo "<td>";
   
      echo"<form action='index.php' method='post'>";
      echo"<input type='hidden' name=dia value='".$dia."'>";
      echo"<input type='hidden' name=hora value='".$hora."'>";
   
      echo"<button class='enlace' type='submit' name='btnnombre' value='".$tupla["id_usuario"]."'>".$tupla["nombre"]."</button>";
      echo"</form>";
     
      echo "</td>";
      echo"</tr>";
    }
    echo"<td rowspan='6'>";
    echo"</td>";

   echo"</table>";

   }
 
  
  
  ?>
   
</body>
</html>