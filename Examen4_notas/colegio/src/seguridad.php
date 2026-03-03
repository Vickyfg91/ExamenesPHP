<?php
// recojo la clave api de mi session  para usarlo en logueado

$datos_env["api_session"]=$_SESSION["api_session"];
// le paso el logueado
$respuesta=consumir_servicios_REST(DIR_SERV."/logueado","GET",$datos_env);
$json=json_decode($respuesta,true);
 if(!$json)
 {
     session_destroy();
     die(error_page("Examen 4 Notas","<h1>Examen 4 Notas</h1><p>Sin respuesta oportuna de la API</p>"));  
 }
 if(isset($json["error"]))
 {

     session_destroy();
     consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env);
     die(error_page("Examen 4 Notas","<h1>Examen 4 Notas</h1><p>".$json["error"]."</p>"));
 }

 if(isset($json["no_auth"]))
 {
    session_unset();
    $_SESSION["seguridad"]="Usted ha dejado de tener acceso a la API. Por favor vuelva a loguearse.";
    header("Location:".$salto);
    exit();
 }

if(isset($json["mensaje"]))
{
    session_unset();
    consumir_servicios_REST(DIR_SERV."/salir","POST",$datos_env);
    $_SESSION["seguridad"]="Usted ya no se encuentra registrado en la BD";
    header("Location:".$salto);
    exit();
}
// Acabo de pasar el control de baneo
$datos_usuario_log=$json["usuario"];


//Ahora paso el control de tiempo

if(time()-$_SESSION["ultm_accion"]>MINUTOS*60)
{
    session_unset();
    $_SESSION["seguridad"]="Su tiempo de sesión ha expirado. Por favor vuelva a loguearse";
    header("Location:".$salto);
    exit();
}
// Paso el control de tiempo y lo renuevo
$_SESSION["ultm_accion"]=time();


?>