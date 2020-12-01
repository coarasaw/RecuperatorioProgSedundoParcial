<?php
namespace App\Controllers;
use App\Models\Materia;
use \Firebase\JWT\JWT;

class MateriaController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $materia = $dato['materia']?? '';
        $cupos = $dato['cupos']?? '';
        $cuatrimestre = $dato['cuatrimestre']?? '';

        if(isset($materia) && isset($cupos) && isset($cuatrimestre)){

            // Graba en la Base de Datos
            $user = new Materia;                       // creo una materia
            $user->materia = $materia;
            $user->cupos = $cupos;
            $user->cuatrimestre = $cuatrimestre;
            
            $rta = $user->save();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Materia"));
            return $response;
        }
    }

    public function getAll ($request, $response, $args) {
        $rta = Materia::get();  // Trae Todos los de la base
        
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}