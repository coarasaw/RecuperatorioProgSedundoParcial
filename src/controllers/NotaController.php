<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\Materia;
use App\Models\Inscripcione;
use \Firebase\JWT\JWT;

class NotaController {

    public function update($request, $response, $args)
    {
        $dato = $request->getParsedBody();
        $nota = $dato['nota']?? '';
        $idAlumno = $dato['idAlumno']?? '';
        var_dump($nota);
        var_dump($idAlumno);
        
        $id = $args['id'];
        var_dump($id);
        die();
        /* $user = User::find($id);

        $user->name = "Peter";
        $user->email = "nuevo@mail.com"; */

        $rta = ""; //$user->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getAll($request, $response, $args)
    {
        //$id = $args['id'];
        //$rta = Inscripcione::find($id);  //get();
        $rta = Inscripcione::get();
        //$rta = Inscripcione::select('select * from inscripciones where materia_id = ?', array('P3'));
        //$results = DB::select('select * from users where id = ?', array(1));
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}