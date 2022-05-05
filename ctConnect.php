<?php
//@AUTOR ING. JOSE DANIEL SOLIS VELARDE

//AQUI SE DEFINE LA FUNCION GENERICA PARA CONSUMIR UN SERVICIO
            //servicioApi(METODO, RUTA_SERVICIO, DATOS_JSON, TOKEN)
            //METODO = GET o POST
            //RUTA_SERVICIO = Servicio que se requiere consumir
            //DATOS_JSON = Objeto JSON de datos que el servico requiere si no se ocupa se envia NULL
            //TOKEN = Cadena de texto alfanumerica que requiere el servico para validar la seguridad sino se ocupa se envia NULL
function servicioApi($metodo, $servicio, $json = null, $token = null) {
    //$ch = curl_init('http://187.210.141.12:3001/' . $servicio);   
    $ch = curl_init('http://connect.ctonline.mx:3001/'.$servicio); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json), 'x-auth: ' . $token));
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
}


function crearNuevoToken() {	
    //Credenciales del cliente para poder consumir los servicios
    $cliente = 'MXL0480';
    $email = 'dan_beta@outlook.com';
    $rfc = 'BEMD760912TG7';    
    $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YTE3NTgwNGQ5NzUwMDdhZTM0ODJhY2EiLCJjdG9ubGluZSI6ZmFsc2UsImNsaWVudGUiOiJNWEwwNDgwIiwiaWF0IjoxNjUwNjExNDgwLCJleHAiOjE2NTA2OTc4ODB9.1NBwzuofN8H2XqX-pz2Q3tz3L_WB3_Ik-Dh-Y1V1doQ';

    $servicio = 'cliente/token/'; //Ruta del servicio para la creacion de un nuevo token
    $json = json_encode(array('email' => $email, 'cliente' => $cliente, 'rfc' => $rfc));   

    //AQUI SE CONSUME UN SERVICIO POR == METODO POST == y SE RETORNA COMO RESPUESTA    
    return servicioApi('POST', $servicio, $json, null);
}

//Consulta del token almacenado en la base de datos, si aun es valida se reutiliza si ya vencio se regenera un nuevo token
//JUGAR CON ESTA VARIABLE (true o false) PARA VER EL COMPRTAMIENTO EN PANTALLA (NAVEGADOR WEB - CHROME) DEL RESULTADO
$sesionTokenValido = true;

//AQUI SE DECLARAN LAS VARIABLES A UTILIZAR EN EL SERVICIO
//Se verifica si el token actual es valido sino se crea uno nuevo y se debe actualizar el de base de datos para reutilizarlo
//AQUI SE COLOCA EL STRING DEL TOKEN ALMACENADO EN BASE DE DATOS
//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
$token = ($sesionTokenValido) ? 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YTE3NTgwNGQ5NzUwMDdhZTM0ODJhY2EiLCJjdG9ubGluZSI6ZmFsc2UsImNsaWVudGUiOiJNWEwwNDgwIiwiaWF0IjoxNjUwNjExNDgwLCJleHAiOjE2NTA2OTc4ODB9.1NBwzuofN8H2XqX-pz2Q3tz3L_WB3_Ik-Dh-Y1V1doQ' : crearNuevoToken()->{'token'}; 
$codigoArticulo = 'BOCBLR050'; //Codigo del articulo para formar la ruta del servicio 
$almacen = '02A'; //Almacen para formar la ruta del servicio 
$servicio = 'existencia/'.$codigoArticulo.'/'.$almacen; //Ruta del servicio para consultar existencia de articulo por almancen


//AQUI SE IMPRIME EL TOKEN CON EL QUE SE CONSUMEN LOS SERVICIO
print_r(array('TOKEN: '=>$token));


//AQUI SE CONSUME UN SERVICIO POR  == METODO GET == y SE ALMACENA LA RESPUESTA
$resultadoServicio = servicioApi('GET', $servicio, null, $token);


//AQUI SE IMPRIME EN PANTALLA (NAVEGADOR WEB - CHROME) LOS RESULTADOS
print_r($resultadoServicio);
?>
