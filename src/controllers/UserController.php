<?php
namespace App\Controllers;
//use Psr\Http\Message\UploadedFileInterfacey; 
use App\Models\User;
use Clases\file;
use Img\Imagenes;


class UserController {
    public function getAll ($request, $response, $args) {
        $rta = User::get();  // Trae Todos los de la base
        //$rta = User::find(1);
        //$rta = User::where('id', '>',  0)   // Se usa para obtener un rango.
        // ->where('campo', 'operador', 'valor')        
        //->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getOne($request, $response, $args)
    {
        $id = $args['id'];
        $rta = User::find($id);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $email = $dato['email']?? '';
        $nombre = $dato['nombre']?? '';
        $clave = $dato['clave']?? '';
        $tipo = $dato['tipo']?? '';
        //Foto
        $nombreFoto=$_FILES['foto']['name'];
        $nombreImagen = explode('@', $email);
        // extencion del archivo
        $extension = explode('.', $nombreFoto);
        $extension = $extension[1];
        // armamos el nombre del archivo
        $nonbreArchivo = $nombreImagen[0].time().'.'.$extension;
        //de donde saco el archivo
        $guardado=$_FILES['foto']['tmp_name'];

        //Buscar email
        $count = User::where('email', '=', $email)->count(); //Verifica email unico
        //var_dump($count);
        if ($count > 0) {
            $response->getBody()->write(json_encode("Encontro Correo YA EXISTE"));
            return $response;
        } else {
            $count = User::where('nombre', '=', $nombre)->count(); //Verifica nombre unico
            if($count > 0){
                $response->getBody()->write(json_encode("Encontro Nombe YA EXISTE"));
                return $response;
            }else{
                if(strlen($clave) < 4){
                    $response->getBody()->write(json_encode("Clave debe tener al menos 4 caracteres"));
                    return $response;
                }else{
                    if(!file_exists('imagenes')){
                        mkdir('imagenes',0777,true);
                        if(file_exists('imagenes')){
                            if(move_uploaded_file($guardado,'imagenes/'.$nonbreArchivo)){
                                $subido = "Subio";
                            }else{
                                $subido = "NO Subio";
                            }
                        }
                    }else{
                        if(move_uploaded_file($guardado,'imagenes/'.$nonbreArchivo)){
                            $subido = "Subio";
                        }else{
                            $subido = "NO Subio";
                        }
                    }
                }
            }
            //Clave encriptada
            $claveATratar = $clave;
	        $alt = "f#@V)Hu^%Hgfds";
            $clave = sha1($alt.$claveATratar);    
    
            // Graba en la Base de Datos
            $user = new User;  // creo una clase de usuario
            $user->email = $email;
            $user->clave = $clave;
            $user->nombre = $nombre;
            $user->tipo = $tipo;
            $user->foto = $nonbreArchivo;
    
            $rta = $user->save();
    
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }

    public function update($request, $response, $args)
    {
        $dato = $request->getParsedBody();
        $email = $dato['email']?? '';
        $nombre = $dato['nombre']?? '';
        var_dump($email);
        var_dump($nombre);
        
        $id = $args['id'];
        var_dump($id);
        die();
        $user = User::find($id);

        $user->name = "Peter";
        $user->email = "nuevo@mail.com";

        $rta = $user->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function delete($request, $response, $args)
    {
        $id = $args['id'];
        $user = User::find($id);
        $rta = $user->delete();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}