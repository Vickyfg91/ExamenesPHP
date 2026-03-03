<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app = new \Slim\App;


$app->post('/login', function ($request) {
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");

    $datos_login = ["usuario" => $usuario,"clave" => $clave];

    echo json_encode(login($datos_login));
});

$app->get('/logueado', function ($request) {
    $test = validateToken();

    if (is_array($test)) {
        if (isset($test["usuario"])) {
            echo json_encode([
                "usuario" => $test["usuario"],
                "token" => $test["token"] 
            ]);
        } else if (isset($test["mensaje_baneo"])) {
            echo json_encode(["mensaje_baneo" => "El usuario no se encuentra registrado en la BD"]);
        } else {
            echo json_encode(["error" => "Servidor fallo"]);
        }
    } else {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
    }
});



$app->get('/grupos', function () {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {

                echo json_encode(grupos());
            } else {
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
            }
        } else {
            echo json_encode($test);
        }
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});

$app->get('/aulas', function () {

    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {

                echo json_encode(aulas());
            } else {
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
            }
        } else {
            echo json_encode($test);
        }
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});

$app->get('/horariosProfesor/{id_usuario}', function ($request) {
    $test = validateToken();

    if (is_array($test) && isset($test["usuario"])) {
        $id_usuario = $request->getAttribute("id_usuario");
        echo json_encode(horariosProfesor($id_usuario));
    } else if (isset($test["error"])) {
        echo json_encode(["error" => $test["error"]]);
    } else {
        echo json_encode(["error" => "Token no válido o sesión expirada"]);
    }
});

$app->run();

/*
$app->get('/', function () {
    echo json_encode(["mensaje" => "API funcionando correctamente"]);
});

$app->post('/login', function ($request) {

    $datos_login[] = $request->getParam("usuario");
    $datos_login[] = $request->getParam("clave");


    echo json_encode(login($datos_login));
});
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
$app->get('/logueado', function () {

    $test = validateToken();
    if (is_array($test)) {
        echo json_encode($test);
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});



$app->post('/login',function($request){
    
    $datos_login[]=$request->getParam("usuario");
    $datos_login[]=$request->getParam("clave");


    echo json_encode(login($datos_login));
});



$app->run();

?>
*/
?>

