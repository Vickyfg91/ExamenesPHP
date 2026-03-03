<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;


$app->post('/salir',function($request){
    session_id($request->getParam("api_session"));
    session_start();
    session_destroy();
    $respuesta["log_out"]="Cerrada sesión en la API";
    echo json_encode($respuesta);
});

$app->post('/login',function($request){
    $usuario=$request->getParam("usuario");
    $clave=$request->getParam("clave");

    echo json_encode(login($usuario,$clave));


});
$app->post('/logueado',function($request){

    session_id($request->getParam("api_session"));
    session_start();
    if(isset($_SESSION["usuario"]))
    {
        $user=$_SESSION["usuario"];
        $pass=$_SESSION["clave"];
        echo json_encode(logueado($user,$pass));
    }
});

$app->get("/usuario",function($request){

session_id($request->getParam("api_session"));
session_start();
$usuario=$request->getParam("id_usuario");
if(isset($_SESSION["usuario"]))
{
echo json_encode(obtenerUsuario($usuario));
}
else
{
    session_destroy();
    $respuesta["no_auth"]="No tienes permisos para usar este servicio";
    echo json_encode($respuesta);

}

});

$app->get("/usuariosGuardia/{dia}/{hora}",function($request){
    session_id($request->getParam("api_session"));
    session_start();
    $dia=$request->getAttribute("dia");
    $hora=$request->getAttribute("hora");

    if(isset($_SESSION["usuario"]))
    {
        echo json_encode(usuariosGuardia($dia,$hora));
    }
    else
    {
        session_destroy();
        $respuesta["no_auth"]="No tienes permisos para usar este servicio";
        echo json_encode($respuesta);
    }


});

$app->get("/deGuardia",function($request){

    session_id($request->getParam("api_session"));
    session_start();  
    $dia=$request->getParam("dia");
    $hora=$request->getParam("hora");
    $usuario=$request->getParam("usuario");

    if(isset($_SESSION["usuario"]))
    {
        echo json_encode(deGuardia($usuario,$dia,$hora));
    }
    else
    {
        session_destroy();
        $respuesta["no_auth"]="No tienes permisos para usar este servicio";
        echo json_encode($respuesta);
    }


});


$app->run();

?>
