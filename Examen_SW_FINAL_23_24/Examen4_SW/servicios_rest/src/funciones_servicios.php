<?php
require "config_bd.php";

function conexion_pdo()
{
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

        $respuesta["mensaje"] = "Conexi&oacute;n a la BD realizada con &eacute;xito";

        $conexion = null;
    } catch (PDOException $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }
    return $respuesta;
}


function conexion_mysqli()
{

    try {
        $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
        mysqli_set_charset($conexion, "utf8");
        $respuesta["mensaje"] = "Conexi&oacute;n a la BD realizada con &eacute;xito";
        mysqli_close($conexion);
    } catch (Exception $e) {
        $respuesta["error"] = "Imposible conectar:" . $e->getMessage();
    }
    return $respuesta;
}

function login($usuario, $clave)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "select * from usuarios where usuario = ? and clave = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    if ($sentencia->rowCount() > 0) {
        // Está en la bd
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
        session_name("Examen_SW");
        session_start();
        $_SESSION["usuario"] = $respuesta["usuario"]["usuario"];
        $_SESSION["clave"] = $respuesta["usuario"]["clave"];
        $_SESSION["tipo"] = $respuesta["usuario"]["tipo"];
        $respuesta["api_session"] = session_id();
    } else {
        // No está en la bd
        $respuesta["mensaje"] = "Usuario no se encuentra regis. en la BD";
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function logueado($usuario, $clave)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "select * from usuarios where usuario = ? and clave = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    if ($sentencia->rowCount() > 0) {
        // Está en la bd
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        // No está en la bd
        $respuesta["mensaje"] = "Usuario no se encuentra regis. en la BD";
    }

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtener_alumnos()
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "select * from usuarios where tipo='alumno'";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["alumnos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtener_notas_alumno($cod_alu)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "select asignaturas.cod_asig, denominacion, nota from asignaturas, notas where asignaturas.cod_asig=notas.cod_asig && cod_usu=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_alu]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["notas"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtener_no_notas_alumno($cod_alu)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "select * from asignaturas where cod_asig not in (select asignaturas.cod_asig from asignaturas, notas where asignaturas.cod_asig=notas.cod_asig && cod_usu=?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_alu]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["notas"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}



function quitar_nota($cod_alu, $cod_asig)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "delete from notas where cod_asig=? and cod_usu=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_asig, $cod_alu]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["mensaje"] = "Asignatura descalificada con éxito";
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function poner_nota($cod_alu, $cod_asig)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "insert into notas (cod_asig, cod_usu, nota) values(?,?,?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod_asig, $cod_alu, 0]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["mensaje"] = "Asignatura calificada con éxito";
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function cambiar_nota($cod_alu, $cod_asig, $nota)
{
    // Conexion
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (Exception $e) {
        echo json_encode(array("error" => "Imposible conectar:" . $e->getMessage()));
    }

    // Consulta
    try {
        $consulta = "update notas set nota=? where cod_asig = ? and cod_usu=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$nota, $cod_asig, $cod_alu]);
    } catch (Exception $e) {
        $sentencia = null;
        $conexion = null;
        echo json_encode(array("error" => "Imposible realizar la consulta:" . $e->getMessage()));
    }

    $respuesta["mensaje"] = "Asignatura cambiada con éxito";
    $sentencia = null;
    $conexion = null;
    return $respuesta;
}
