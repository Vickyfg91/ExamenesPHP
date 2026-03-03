<?php

require "src/funciones_servicios.php";
require __DIR__ . '/Slim/autoload.php';

$app= new \Slim\App;



$app->get('/conexion_PDO',function($request){

    echo json_encode( conexion_pdo(), JSON_FORCE_OBJECT);
});

$app->get('/conexion_MYSQLI',function($request){
    
    echo json_encode( conexion_mysqli(), JSON_FORCE_OBJECT);
});



// Una vez creado servicios los pongo a disposición
$app->run();
?>
