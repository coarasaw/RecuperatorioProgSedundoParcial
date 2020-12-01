<?php
namespace App\Controllers;
use App\Models\User;
use Clases\Usuario; 
use \Firebase\JWT\JWT;

class LoginController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $email = $dato['email']?? '';
        $nombre = $dato['nombre']?? '';
        $clave = $dato['clave']?? '';
        //Buscar email
        if($email <> ''){
            $count = User::where('email', '=', $email)->count();
            if ($count > 0) {
                $users = User::where('email', '=', $email)->get();
                foreach ($users as $user)
                {
                    $claveGuardada = $user->clave;
                    $tipo = $user->tipo;
                }
                //Veo tipo

                /* var_dump($tipo);
                die(); */

                //Clave encriptada
                $claveATratar = $clave;
                $alt = "f#@V)Hu^%Hgfds";
                $clave = sha1($alt.$claveATratar); 

                if($claveGuardada == $clave){
                    //Genero Token
                    // llave
                    $key = "pro3-parcial";  
                    //Generamos el Payload-CargaDatos
                    $payload = array(
                        "iss" => "http://example.org",
                        "aud" => "http://example.com",
                        "iat" => 1356999524,               //vencimiento del token
                        "nbf" => 1357000000,
                        "email" => $email,
                        "clave" => $clave,
                        "tipo" => $tipo
                    );
                    $jwt = JWT::encode($payload, $key);
                    $response->getBody()->write(json_encode($jwt));
                    return $response;
                }else{
                    $response->getBody()->write(json_encode("Clave Erronea"));
                    return $response;
                }
            }else{
                $response->getBody()->write(json_encode("NO Encontro Usuario por emal"));
                return $response;
            }
        }else{
            if($nombre <> ''){
                $count = User::where('nombre', '=', $email)->count();
                if ($count > 0) {
                    $users = User::where('nombre', '=', $email)->get();
                    foreach ($users as $user)
                    {
                        $claveGuardada = $user->clave;
                        $tipo = $user->tipo;
                    }
                    
                    //Clave encriptada
                    $claveATratar = $clave;
                    $alt = "f#@V)Hu^%Hgfds";
                    $clave = sha1($alt.$claveATratar); 

                    if($claveGuardada == $clave){
                        //Genero Token
                        // llave
                        $key = "pro3-parcial";  
                        //Generamos el Payload-CargaDatos
                        $payload = array(
                            "iss" => "http://example.org",
                            "aud" => "http://example.com",
                            "iat" => 1356999524,               //vencimiento del token
                            "nbf" => 1357000000,
                            "email" => $email,
                            "clave" => $clave,
                            "tipo" => $tipo
                        );
                        $jwt = JWT::encode($payload, $key);
                        $response->getBody()->write(json_encode($jwt));
                        return $response;
                    }else{
                        $response->getBody()->write(json_encode("Clave Erronea"));
                        return $response;
                    }
                }else{
                    $response->getBody()->write(json_encode("NO Encontro Usuario por nombre"));
                    return $response;
                }
            }else{
                $response->getBody()->write(json_encode("Datos Enviados Erroneos"));
                return $response;
            }

        }
    }
}