<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;


$app->post('/salir',function($request){
    session_destroy();
    $respuesta["log_out"]="Cerrada sesión en la API";
    echo json_encode($respuesta);
});


$app->get('/profesores',function($request){
   
    echo json_encode(profesores());

});

$app->get('/horarios/{usuario}',function($request){
   $usuario=$request->getAttribute("usuario");
    echo json_encode(horarios($usuario));

});

$app->get('/obtenerGrupos/{usuario}',function($request){
    $usuario=$request->getAttribute("usuario");
    $hora=$request->getParam("hora");
    $dia=$request->getParam("dia");
    echo json_encode(obtenerGrupos($usuario,$hora,$dia));
});


$app->delete('/quitarGrupo/{usuario}',function($request){
    $usuario=$request->getAttribute("usuario");
    $grupo=$request->getParam("grupo");
    $dia=$request->getParam("dia");
    $hora=$request->getParam("hora");

    echo json_encode(quitarGrupo($usuario,$grupo,$dia,$hora));
});

$app->post('/gruposNoIncluidos/{usuario}',function($request){
    $usuario=$request->getAttribute("usuario");
    $dia=$request->getParam("dia");
    $hora=$request->getParam("hora");
    echo json_encode(gruposNoIncluidos($dia,$hora,$usuario));

});
$app->post('/insertarGrupo/{usuario}',function($request){
    $usuario=$request->getAttribute("usuario");
    $dia=$request->getParam("dia");
    $hora=$request->getParam("hora");
    $grupo=$request->getParam("grupo");
    echo json_encode(insertarGrupo($usuario,$dia,$hora,$grupo));

});


// Una vez creado servicios los pongo a disposición
$app->run();



?>
