<?php
require "config_bd.php";


function login($usuario, $clave)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar :" . $e->getMessage();
        return $respuesta;
    }
    try {
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (PDOException $e) {

        $respuesta["error"] = "Imposible realizar la consulta :" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC); //me traigo los datos
        session_name("API_Examen_4_Notas"); // genero la session de la Api para el api_session
        session_start(); //inicio la sesion
        $respuesta["api_session"] = session_id(); // genero la clave para la api
        $_SESSION["usuario"] = $respuesta["usuario"]["usuario"]; //guardo el usuario en la sessión
        $_SESSION["clave"] = $respuesta["usuario"]["clave"]; // guardo la clave en la sessión
        $_SESSION["tipo"] = $respuesta["usuario"]["tipo"]; // guardo la clave en la sessión

    } else {
        $respuesta["mensaje"] = "Usuario no se encuentra registrado en la base de datos";
    }
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function logueado($usuario, $clave)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        $consulta = "select * from usuarios where usuario=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
        if ($sentencia->rowCount() > 0) {
            $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
        } else {
            $respuesta["mensaje"] = "El usuario no se encuentra registrado en la BD";
        }

        $sentencia = null;
        $conexion = null;
        return $respuesta;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

function alumnos()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        $consulta = "select * from usuarios where tipo='alumno'";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
        
         $respuesta["alumnos"] = $sentencia->fetchall(PDO::FETCH_ASSOC);
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

function notasAlumno($cod_usu)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {
        $consulta = "SELECT asignaturas.cod_asig, asignaturas.denominacion, notas.nota FROM asignaturas, notas WHERE asignaturas.cod_asig=notas.cod_asig and cod_usu=?";
        // $consulta="SELECT n.cod_usu, u.nombre, a.denominacion, n.nota, n.cod_asig FROM notas n JOIN asignaturas a ON a.cod_asig = n.cod_asig JOIN usuarios u ON u.cod_usu = n.cod_usu WHERE n.cod_usu = ? and n.nota<>'0'";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_usu]);
        
            $respuesta["notas"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
       

        $sentencia = null;
        $conexion = null;
        return $respuesta;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

function NotasNoEvalAlumno($cod_usu)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {

        $consulta = "SELECT * FROM asignaturas where cod_asig not in(select cod_asig from notas where cod_usu=?) ";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_usu]);
       
            $respuesta["asignaturas"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        

        $sentencia = null;
        $conexion = null;
        return $respuesta;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

function quitarNota($alumno, $asignatura)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {

        $consulta = "DELETE FROM notas WHERE cod_usu = ? AND cod_asig = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$alumno, $asignatura]);

        $respuesta["mensaje"] = "Asignatura descalificada con exito";
        $sentencia = null;
        $conexion = null;
        return $respuesta;

    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

function ponerNota($alumno, $asignatura)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar en poner nota conexión:" . $e->getMessage();
        return $respuesta;
    }
    try {

        $consulta = "INSERT INTO notas ( cod_usu,cod_asig, nota) VALUES (?, ?, 0.00)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$alumno, $asignatura]);

        $respuesta["mensaje"] = "Asignatura cambiada con exito";
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

function cambiarNota($alumno, $asignatura, $nota)
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        return $respuesta;
    }
    try {

        $consulta = "UPDATE notas   SET nota = ?  WHERE cod_usu = ? AND cod_asig = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$nota, $alumno, $asignatura]);

        $respuesta["mensaje"] = "Asignatura cambiada con exito";
        $sentencia = null;
        $conexion = null;
        return $respuesta;

    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
        $sentencia = null;
        $conexion = null;
        return $respuesta;
    }
}

