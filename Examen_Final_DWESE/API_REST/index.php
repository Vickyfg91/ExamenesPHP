<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app= new \Slim\App;

$app->get('/logueado',function(){

    $test=validateToken();
    if(is_array($test))
    {
        echo json_encode($test);
    }
    else
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio"));  
});


$app->post('/login',function($request){
    
    $datos_login[]=$request->getParam("usuario");
    $datos_login[]=$request->getParam("clave");


    echo json_encode(login($datos_login));
});

//Peticion get para obtener unos datos de bd con un valor
$app->get('/obtenerHorario/{id_usuario}',function($request){

    $test=validateToken();
    if(is_array($test))
    echo json_encode(obtener_horario($request->getAttribute("id_usuario")));
});

$app->run();

?>