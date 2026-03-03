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


$app->get('/horarioProfesor/{id_usuario}',function($request){
    
    $test=validateToken();

    if(is_array($test))
    {
        $id_usuario=$request->getAttribute("id_usuario");
        $horario = horarioProfesor($id_usuario);

        if (isset($horario["error"])) {
            echo json_encode(array("error" => "Error"));
        } else {
            echo json_encode(horarioProfesor($id_usuario));
        }
    } else {
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio"));
    }
});


$app->get('/grupos',function(){

    $test=validateToken();

    if(is_array($test))
    {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                echo json_encode(grupos());
            }else{
               echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio")); 
            }
            
        }else{
            echo json_encode($test);
        }
    }else
        echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio"));  
});

$app->get('/horarioGrupo/{id_grupo}',function($request){
    //$id_grupo = $request->getAttribute("id_grupo");
    //echo json_encode(horarioGrupo($id_grupo));
    $test=validateToken();

    if(is_array($test)){
        if (isset($test["usuario"]) && $test["usuario"]["tipo"] == "admin") {
        $id_grupo=$request->getAttribute("id_grupo");
        $horarioGrupo = horarioGrupo($id_grupo);

        if (isset($horarioGrupo["error"])) {
            echo json_encode($horarioGrupo);
        }else{
           echo json_encode(array("no_auth"=>"No tienes permiso para usar el servicio"));
        }
    }else{
        echo json_encode(array("error" => "Error"));
        } 
    }
});

$app->run();

?>