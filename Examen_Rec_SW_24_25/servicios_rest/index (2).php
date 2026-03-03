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

$app->get('/usuario/{id_usuario}',function($request){
    
    $test=validateToken();
    if(is_array($test))
    {
        if(isset($test["usuario"]))
        {
           
            echo json_encode(datos_usuarios($request->getAttribute("id_usuario")));
          
        }
        else
            echo json_encode($test);
    }
    else
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio")); 






    
});

$app->get('/usuariosGuardia/{dia}/{hora}',function($request){


    $test=validateToken();
    if(is_array($test))
    {
        if(isset($test["usuario"]))
        {
            echo json_encode(usuarios_guardia($request->getAttribute("dia"),$request->getAttribute("hora")));
        }
        else
            echo json_encode($test);
    }
    else
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio")); 

});


$app->get('/deGuardia/{id_usuario}',function($request){


    $test=validateToken();
    if(is_array($test))
    {
        if(isset($test["usuario"]))
        {
            echo json_encode(de_guardia($request->getAttribute("id_usuario")));
        }
        else
            echo json_encode($test);
    }
    else
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio")); 

   
});

$app->run();

?>