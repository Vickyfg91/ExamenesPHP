<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;






$app->post('/login',function($request){

    $usuario=$request->getParam("usuario");
    $clave=$request->getParam("clave");
    echo json_encode(login($usuario,$clave));
});


$app->get('/logueado',function($request){

   session_id($request->getParam("api_session"));
   session_start();
   if(isset($_SESSION["usuario"]))
   {
    $usuario=$_SESSION["usuario"];
    $clave=$_SESSION["clave"];
    echo json_encode(logueado($usuario,$clave));
   }
   else
   {
    $respuesta["no_auth"]="El usuario no tiene permisos";
    echo json_encode($respuesta);

   }
   
});

$app->get('/usuario/{id_usuario}',function($request){

    session_id($request->getParam("api_session"));
    session_start();
    if(isset($_SESSION["usuario"]))
    {
     $usuario=$request->getAttribute("id_usuario");
     
     echo json_encode(usuario($usuario));
    }
    else
    {
     $respuesta["no_auth"]="El usuario no tiene permisos";
     echo json_encode($respuesta);
 
    }
    
 });

 $app->get('/usuariosGuardia/{dia}/{hora}',function($request){

    session_id($request->getParam("api_session"));
    session_start();
    if(isset($_SESSION["usuario"]))
    {
     $dia=$request->getAttribute("dia");
     $hora=$request->getAttribute("hora");
     
     echo json_encode(usuariosGuardia($dia,$hora));
    }
    else
    {
     $respuesta["no_auth"]="El usuario no tiene permisos";
     echo json_encode($respuesta);
 
    }
    
 });
 


// Una vez creado servicios los pongo a disposición
$app->run();
?>
