<?php
require "config_bd.php";

function profesores()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        $consulta = "select * from usuarios";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (PDOException $e) {
        $respuesta["error"] = "Error al realizar la consulta Profesores:" . $e->getMessage();
        $conexion = null;
        $sentencia = null;
        return $respuesta;
    }
    $respuesta["usuarios"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $conexion = null;
    $sentencia = null;
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
        
        $consulta="SELECT horario_lectivo.dia, horario_lectivo.hora, horario_lectivo.grupo,grupos.nombre from horario_lectivo, grupos WHERE horario_lectivo.grupo=grupos.id_grupo and usuario=?";      
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Error al realizar la consulta Profesores:" . $e->getMessage();
        $conexion = null;
        $sentencia = null;
        return $respuesta;
    }
    $respuesta["horarios"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $conexion = null;
    $sentencia = null;
    return $respuesta;
}



function obtenerGrupos($usuario,$hora,$dia)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
      
        $consulta="SELECT grupos.nombre, grupos.id_grupo, horario_lectivo.usuario, horario_lectivo.dia,  horario_lectivo.hora  from grupos, horario_lectivo WHERE horario_lectivo.grupo= grupos.id_grupo and usuario=? and horario_lectivo.hora=? and horario_lectivo.dia=?";      
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario,$hora,$dia]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Error al realizar la consulta Profesores:" . $e->getMessage();
        $conexion = null;
        $sentencia = null;
        return $respuesta;
    }
    $respuesta["grupos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function quitarGrupo($usuario,$grupo,$dia,$hora)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        
        $consulta="DELETE from horario_lectivo WHERE horario_lectivo.usuario=? and horario_lectivo.grupo=? and horario_lectivo.dia=? and horario_lectivo.hora=?";      
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario,$grupo,$dia,$hora]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Error al realizar la consulta Profesores para su borrado:" . $e->getMessage();
        $conexion = null;
        $sentencia = null;
        return $respuesta;
    }
    $respuesta["mensaje"] ="Grupo quitado con exito";
    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function gruposNoIncluidos($hora,$dia,$usuario)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        
        $consulta="SELECT grupos.* from grupos where id_grupo not in(select grupo from horario_lectivo where hora=? and dia=? and usuario=?)";
        $consulta1="SELECT horario_lectivo.id_horario, grupos.nombre , grupos.id_grupo FROM horario_lectivo, grupos WHERE horario_lectivo.grupo=grupos.id_grupo AND horario_lectivo.hora=? and horario_lectivo.dia=? and horario_lectivo.usuario not in (SELECT usuarios.id_usuario FROM usuarios WHERE usuarios.id_usuario=?)";      
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$hora,$dia,$usuario]);
    } catch (PDOException $e) {
        $respuesta["error"] = "Error al realizar la consulta Profesores:" . $e->getMessage();
        $conexion = null;
        $sentencia = null;
        return $respuesta;
    }
    $respuesta["gruposNO"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $conexion = null;
    $sentencia = null;
    return $respuesta;
}

function insertarGrupo($usuario,$dia,$hora,$grupo)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar en poner nota conexión:" . $e->getMessage();
        return $respuesta;
    }
    try {
      
        $consulta = "INSERT INTO horario_lectivo  (usuario,dia,hora,grupo) VALUES (?,?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario,$dia,$hora,$grupo]);

        $respuesta["mensaje"] = "Insertado grupo con exito";
        $sentencia = null;
        $conexion = null;
        return $respuesta;

    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar en poner nota Sentencia:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}