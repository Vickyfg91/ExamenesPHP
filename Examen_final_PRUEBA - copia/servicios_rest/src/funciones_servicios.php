<?php
require "config_bd.php";



function login($usuario,$clave)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    try{
       $consulta="SELECT * FROM usuarios WHERE usuario=? and clave=?";
       $sentencia=$conexion->prepare($consulta);
       $sentencia->execute([$usuario,$clave]);
        
    }
    catch(PDOException $e){
        $sentencia=null;
        $conexion=null;
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    if($sentencia->rowCount()>0)
    {
        $respuesta["usuario"]=$sentencia->fetch(PDO::FETCH_ASSOC);
        session_name("API_Examen_final");
        session_start();
        $respuesta["api_session"]=session_id();
        $_SESSION["usuario"]=$respuesta["usuario"]["usuario"];
        $_SESSION["clave"]=$respuesta["usuario"]["clave"];
        $_SESSION["tipo"]=$respuesta["usuario"]["tipo"];



    }
    else
    {
        $respuesta["mensaje"]="El usuario no se encuentra en la BD";
    }

    $conexion=null;
    $sentencia=null;
    return $respuesta;

}

function logueado($usuario,$clave)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    try{
       $consulta="SELECT * FROM usuarios WHERE usuario=? and clave=?";
       $sentencia=$conexion->prepare($consulta);
       $sentencia->execute([$usuario,$clave]);
        
    }
    catch(PDOException $e){
        $sentencia=null;
        $conexion=null;
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    if($sentencia->rowCount()>0)
    {
        $respuesta["usuario"]=$sentencia->fetch(PDO::FETCH_ASSOC);
        
    }
    else
    {
        $respuesta["mensaje"]="El usuario no se encuentra en la BD";
    }

    $conexion=null;
    $sentencia=null;
    return $respuesta;

}


function horarioProfesor($id_usuario)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    try{
       $consulta="SELECT horario_lectivo.dia,horario_lectivo.hora,grupos.nombre as grupo, aulas.nombre as aula FROM aulas,horario_lectivo,grupos WHERE horario_lectivo.grupo=grupos.id_grupo AND horario_lectivo.aula=aulas.id_aula and horario_lectivo.usuario=?";
       $sentencia=$conexion->prepare($consulta);
       $sentencia->execute([$id_usuario]);
        
    }
    catch(PDOException $e){
        $sentencia=null;
        $conexion=null;
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

   $respuesta["horario"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);

    $conexion=null;
    $sentencia=null;
    return $respuesta;

}


function todosGrupos()
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    try{
       $consulta="SELECT * FROM grupos ";
       $sentencia=$conexion->prepare($consulta);
       $sentencia->execute();
        
    }
    catch(PDOException $e){
        $sentencia=null;
        $conexion=null;
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

   
   $respuesta["todos_grupos"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
  
    $conexion=null;
    $sentencia=null;
    return $respuesta;

}

function horarioGrupo($id_grupo)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

    try{
       $consulta="SELECT horario_lectivo.dia ,horario_lectivo.hora,usuarios.usuario as nombre, aulas.nombre as aula FROM horario_lectivo,usuarios,aulas WHERE horario_lectivo.aula=aulas.id_aula and horario_lectivo.usuario=usuarios.id_usuario and horario_lectivo.grupo=?";
       $sentencia=$conexion->prepare($consulta);
       $sentencia->execute([$id_grupo]);
        
    }
    catch(PDOException $e){
        $sentencia=null;
        $conexion=null;
        $respuesta["error"]="Imposible conectar :".$e->getMessage();
        return $respuesta;
    }

   
   $respuesta["grupos"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
  
    $conexion=null;
    $sentencia=null;
    return $respuesta;

}


?>