<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\Materia;
use App\Models\Inscripcione;
use \Firebase\JWT\JWT;
//use PDO;

class InscripcioneController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $id_materia = $dato['id_materia']?? '';
        $id_alumno = $dato['id_alumno']?? '';
        

        if (isset($id_alumno)) {
                $users = User::where('id', '=', $id_alumno)->get();
                foreach ($users as $user)
                {
                    $tipo = $user->tipo;
                }
                if ($tipo == "alumno") {
                    if (isset($id_materia)) {
                        $materias = Materia::where('materia', '=', $id_materia)->get();
                        foreach ($materias as $user)
                        {
                            $totalCupo = $user->cupos;
                        }
                        $recorrerInscripcion = Inscripcione::Where('materia_id','=',$id_materia)->count(); // Si hay cupo
                        if ($recorrerInscripcion < $totalCupo) {     
                            // Graba en la Base de Datos - Doy de alta al alumno y matria en inscriocion
                            $user = new Inscripcione();      // creo una inscripcion
                            $user->alumno_id = $id_alumno;
                            $user->materia_id = $id_materia;
                            
                            $rta = $user->save();

                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }else{
                            $response->getBody()->write(json_encode("NO HAY MAS CUPO"));
                            return $response;
                        }
                    }else{
                        $response->getBody()->write(json_encode("Error No HAY ID MATERIA"));
                        return $response;
                    }
                }else{
                    $response->getBody()->write(json_encode("El usuario no es Alumno"));
            return $response;
                }
        }else{
            $response->getBody()->write(json_encode("Error No HAY ID ALUMNO"));
            return $response;
        }
    }

    public function getAll($request, $response, $args)
    {
        $rta = Inscripcione::get();
        //$rta = Inscripcione::select('select * from inscripciones where materia_id = ?', array(P3));
        //$results = DB::select('select * from users where id = ?', array(1));
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}