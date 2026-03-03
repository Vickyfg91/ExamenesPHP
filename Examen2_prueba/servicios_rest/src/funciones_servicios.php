<?php
require "config_bd.php";

function conexion()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
}

}


function login($usuario,$clave)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
    }

    try {
     $consulta="SELECT * from usuarios WHERE usuario=? and clave=?";
     $sentencia=$conexion->prepare($consulta);
     $sentencia->execute([$usuario,$clave]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
  
    }
    if($sentencia->rowCount()>0)
    {
        $respuesta["usuario"]=$sentencia->fetch(PDO::FETCH_ASSOC);
        session_name("API_Examen2_Prueba");
        session_start();
        $respuesta["api_session"]=session_id();
        $_SESSION["usuario"]=$respuesta["usuario"]["usuario"];
        $_SESSION["clave"]=$respuesta["usuario"]["clave"];
    }
    else
    {
        $respuesta["mensaje"]="El usuario no se encuentra en la BD";
    }
    $sentencia=null;
    $conexion=null;
    return $respuesta;

}

function logueado($usuario,$clave)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
    }

    try {
     $consulta="SELECT * from usuarios WHERE usuario=? and clave=? ";
     $sentencia=$conexion->prepare($consulta);
     $sentencia->execute([$usuario,$clave]);
    } 
    catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
  
    }
    if($sentencia->rowCount()>0)
    {
        $respuesta["usuario"]=$sentencia->fetch(PDO::FETCH_ASSOC);
        
    }
    else
        $respuesta["mensaje"]="El usuario no se encuentra en la BD";

    $sentencia=null;
    $conexion=null;
    return $respuesta;

}
function profesores()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
    }

    try {
     $consulta="SELECT * from usuarios  ";
     $sentencia=$conexion->prepare($consulta);
     $sentencia->execute();
    } 
    catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
  
    }
    
        $respuesta["profesores"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
   

    $sentencia=null;
    $conexion=null;
    return $respuesta;

}

function horarios($usuario)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
    }

    try {
     $consulta="SELECT grupos.nombre, horario_lectivo.dia, horario_lectivo.hora, horario_lectivo.grupo FROM horario_lectivo, grupos where horario_lectivo.grupo=grupos.id_grupo and horario_lectivo.usuario=?";
     $sentencia=$conexion->prepare($consulta);
     $sentencia->execute([$usuario]);
    } 
    catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
  
    }
    
        $respuesta["horarios_profesor"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
   

    $sentencia=null;
    $conexion=null;
    return $respuesta;

}

function GruposDia($dia,$hora,$usuario)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
  
    }

    try {
        $consulta="SELECT grupos.nombre, horario_lectivo.id_horario from horario_lectivo, grupos where horario_lectivo.grupo=grupos.id_grupo and horario_lectivo.dia=? and horario_lectivo.hora=? and horario_lectivo.usuario=?";
     $sentencia=$conexion->prepare($consulta);
     $sentencia->execute([$dia,$hora,$usuario]);
    } 
    catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
  
    }
    
        $respuesta["grupos_profe"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
   

    $sentencia=null;
    $conexion=null;
    return $respuesta;

}


function obtener_grupos_no_horario($dia,$hora,$id_usuario)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
       
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar:".$e->getMessage();
        return $respuesta;
    }

    try{
        $consulta="SELECT grupos.* from grupos where id_grupo not in (select grupo from horario_lectivo where dia=? and hora=? and usuario=?)";
        $sentencia=$conexion->prepare($consulta);
        $sentencia->execute([$dia,$hora,$id_usuario]);
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible realizar la consulta:".$e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
    }

   
    $respuesta["grupos"]=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        
   
    $sentencia=null;
    $conexion=null;
    return $respuesta;
    
}

function borrar_grupo($id_horario)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
       
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar:".$e->getMessage();
        return $respuesta;
    }

    try{
        $consulta="delete from horario_lectivo where id_horario=?";
        $sentencia=$conexion->prepare($consulta);
        $sentencia->execute([$id_horario]);
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible realizar la consulta:".$e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
    }

   
    $respuesta["mensaje"]="Grupo borrado con éxito";
        
   
    $sentencia=null;
    $conexion=null;
    return $respuesta;
    
}

function agregar_grupo($dia,$hora,$id_usuario,$id_grupo)
{
    try{
        $conexion= new PDO("mysql:host=".SERVIDOR_BD.";dbname=".NOMBRE_BD,USUARIO_BD,CLAVE_BD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
       
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible conectar:".$e->getMessage();
        return $respuesta;
    }

    try{
        $consulta="insert into horario_lectivo (usuario,dia, hora, grupo) values (?,?,?,?)";
        $sentencia=$conexion->prepare($consulta);
        $sentencia->execute([$id_usuario,$dia,$hora,$id_grupo]);
    }
    catch(PDOException $e){
        $respuesta["error"]="Imposible realizar la consulta:".$e->getMessage();
        $sentencia=null;
        $conexion=null;
        return $respuesta;
    }

   
    $respuesta["mensaje"]="Usuario insertado con éxito";
        
   
    $sentencia=null;
    $conexion=null;
    return $respuesta;
    
}


