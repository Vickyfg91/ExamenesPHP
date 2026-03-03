<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;




$app->post('/login', function ($request) {

  $usuario = $request->getParam("usuario");
  $clave = $request->getParam("clave");


  echo json_encode(login($usuario, $clave));
});



$app->get('/logueado', function ($request) {
  $api_session = $request->getParam("api_session");
  session_id($api_session);
  // session_id($request->getParam("api_session"));
  session_start();
  if (isset($_SESSION["usuario"])) {
    echo json_encode(logueado($_SESSION["usuario"], $_SESSION["clave"]));
  } else {
    session_destroy();
    $respuesta["no_auth"] = "No tienes permisos para usar este servicio";
    echo json_encode($respuesta);
  }
});



$app->post('/salir', function ($request) {

  $api_session = $request->getParam("api_session");
  session_id($api_session);
  session_start();
  session_destroy();
  echo json_encode(array("log_out" => "Cerrada sesión en la API"));
});

$app->get('/usuario/{id_usuario}', function ($request) {
  $api_session = $request->getParam("api_session");
  session_id($api_session);
  session_start();
  if (isset($_SESSION["usuario"])) {
    echo json_encode(detalles_usuario($request->getAttribute("id_usuario")));
  } else {
    session_destroy();
    $respuesta["no_auth"] = "No tienes permisos para usar este servicio";
    echo json_encode($respuesta);
  }
});

$app->get('/usuariosGuardia/{dia}/{hora}', function ($request) {
  $api_session = $request->getParam("api_session");
  session_id($api_session);
  session_start();
  $dia = $request->getAttribute("dia");
  $hora = $request->getAttribute("hora");



  if (isset($_SESSION["usuario"])) {
    echo json_encode(usuarios_guardia($dia, $hora));
  } else {
    session_destroy();
    $respuesta["no_auth"] = "No tienes permisos para usar este servicio";
    echo json_encode($respuesta);
  }
});


// Una vez creado servicios los pongo a disposición
$app->run();
