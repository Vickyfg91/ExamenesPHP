<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;



$app->get('/conexion_PDO', function ($request) {
    echo json_encode(conexion_pdo());
});

$app->get('/conexion_MYSQLI', function ($request) {
    echo json_encode(conexion_mysqli());
});

// a)
$app->post('/login', function ($request) {
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");
    echo json_encode(login($usuario, $clave));
});

// b)
$app->get('/logueado', function ($request) {
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"])) {
        echo json_encode(logueado($_SESSION["usuario"], $_SESSION["clave"]));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// c)
$app->get('/salir', function ($request) {
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();
    session_destroy();
    echo json_encode(array("log_out" => "Cerrada sesión en la API"));
});

// d)
$app->get('/alumnos', function ($request) {
    $api_session = $request->getParam("api_session");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "tutor") {
        echo json_encode(obtener_alumnos());
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// e)
$app->get('/notasAlumno/{cod_alu}', function ($request) {
    $api_session = $request->getParam("api_session");
    $cod_alu = $request->getAttribute("cod_alu");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"])) {
        echo json_encode(obtener_notas_alumno($cod_alu));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// f)
$app->get('/NotasNoEvalAlumno/{cod_alu}', function ($request) {
    $api_session = $request->getParam("api_session");
    $cod_alu = $request->getAttribute("cod_alu");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "tutor") {
        echo json_encode(obtener_no_notas_alumno($cod_alu));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// g)
$app->delete('/quitarNota/{cod_alu}', function ($request) {
    $api_session = $request->getParam("api_session");
    $cod_asig = $request->getParam("cod_asig");
    $cod_alu = $request->getAttribute("cod_alu");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "tutor") {
        echo json_encode(quitar_nota($cod_alu, $cod_asig));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// h)
$app->post('/ponerNota/{cod_alu}', function ($request) {
    $api_session = $request->getParam("api_session");
    $cod_asig = $request->getParam("cod_asig");
    $cod_alu = $request->getAttribute("cod_alu");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "tutor") {
        echo json_encode(poner_nota($cod_alu, $cod_asig));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});

// i)
$app->put('/cambiarNota/{cod_alu}', function ($request) {
    $api_session = $request->getParam("api_session");
    $cod_asig = $request->getParam("cod_asig");
    $nota = $request->getParam("nota");
    $cod_alu = $request->getAttribute("cod_alu");
    session_id($api_session);
    session_start();
    if (isset($_SESSION["usuario"]) && $_SESSION["tipo"] == "tutor") {
        echo json_encode(cambiar_nota($cod_alu, $cod_asig, $nota));
    } else {
        session_destroy();
        echo json_encode(array("no_auth" => "No tienes permisos para usar este servicio."));
    }
});


// Una vez creado servicios los pongo a disposición
$app->run();
