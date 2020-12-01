<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class ProfesorMiddleware {

    public function __invoke (Request $request, RequestHandler $handler) {

        $headersEnvio = getallheaders();
        // llave
        try {
            $key = "pro3-parcial";  
            $miToken = $headersEnvio["token"] ?? 'No mando Token'; // Si se genero el Token aca lo obtengo de la cabecera
            var_dump($miToken);
            $decoded = JWT::decode($miToken, $key, array('HS256'));
            $verificoTipo=$decoded['tipo'];
            var_dump($decoded);
            var_dump($verificoTipo);
            die();
            if (isset($miToken)){
                $decoded = JWT::decode($miToken, $key, array('HS256'));
                //Verifico si es Profesor
                $verificoTipo=$decoded['tipo'];
                /* var_dump($decoded);
                var_dump($verificoTipo);
                die(); */
                if ($verificoTipo=="Profesor") {
                    $response = $handler->handle($request);
                    $existingContent = (string) $response->getBody();
                    $resp = new Response();
                    $resp->getBody()->write($existingContent);
                    return $resp;
                }else{
                    $response = new Response();
                    $rta = array("No es Profesor");
                    $response->getBody()->write(json_encode($rta));
                    return $response;
                }
            }else{
                $response = new Response();
                $rta = array("No Mando [TOKEN]");
                $response->getBody()->write(json_encode($rta));
                return $response;
            }  
        }
        catch (\Throwable $th) {
            
            $response = new Response();
            $rta = array("No tiene permisos [TOKEN]");
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }
}