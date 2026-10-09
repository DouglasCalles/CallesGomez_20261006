<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, x-Requested-With, Content-Type, Accept, Acces-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
require('classes/estudiante.class.php');

$Estudiante = new Estudiante();

if($_SERVER["REQUEST_METHOD"] === "GET"){
    $tipo_peticion = ((isset($_GET["t"])) ? (($_GET["t"])!="" ? $_GET["t"]: null): null);
    switch($tipo_peticion){
        case "selectAll":
            //devuelve todos los registros
            $resultado = $Estudiante->obtenerEstudiantes();
        break;
        case "select":
            //devuelve registro
            $id = ((isset($_GET["id"])) ? (($_GET["id"]!="") ? intval($_GET["id"]) : 0) : 0); //obtengo el valor del parametro id
            if($id > 0){
            $resultado = $Estudiante->obtenerEstudiante ($id);
            }else{
                //NO existe un valor para ID
            header('HTTP/1.1 412 Precondition Failed');
            $resultado = array("mensaje"=>"El parametro ID no es correcto","valores"=>"");
            }
        break;
        case "insert":
            //INSERTA UN REGISTRO
            if(array_key_exists("fecha_nac",$_GET) and array_key_exists("id_genero",$_GET)){
                if($_GET["fecha_nac"]!="" and $_GET["id_genero"]!=""){
                    $resultado = $Estudiante->nuevoEstudiante($_GET["fecha_nac"],$_GET["id_genero"]);
                }else{
                   header('HTTP/1.1 400 Bad Request');
                    $resultado = array("mensaje"=>"Verifique el valor de la fecha de nacimiento o el genero","valores"=>""); 
                }
            }else{
                //NO SE HAN ENVIADO VALOREES DESDE EL METODO GET
                header('HTTP/1.1 400 Bad Request');
                $resultado = array("mensaje"=>"No se han enviado los parametros requeridos","valores"=>"");
            }
        default;
            //NO SE DEFINIO EL TIOPO DE PETICION "t"
            header('HTTP/1.1 403 Forbidden');
            $resultado = array("mensaje"=>"Debe de indicar el tipo de procesamiento que se realizara","valores"=>"");
        break;
    }
}elseif($_SERVER["REQUEST_METHOD"] === "POST"){
    if(array_key_exists("fecha_nac",$_GET) and array_key_exists("id_genero",$_GET)){
        //SI SE ENVIARON LOS VALORES DESDE EL METODO POST
        if($_GET["fecha_nac"]!="" and $_GET["id_genero"]!=""){
            $resultado = $Estudiante->nuevoEstudiante($_GET["fecha_nac"],$_GET["id_genero"]);
        }else{
            header('HTTP/1.1 400 Bad Request');
            $resultado = array("mensaje"=>"Verifique el valor de la fecha de nacimiento o el genero","valores"=>""); 
        }
        }else{
            //NO SE HAN ENVIADO VALOREES DESDE EL METODO POST
            header('HTTP/1.1 400 Bad Request');
            $resultado = array("mensaje"=>"No se han enviado los parametros requeridos","valores"=>"");
        }
}else{
    header('HTTP/1.1 400 Bad Request');
    $resultado = array("mensaje"=>"¿?","valores"=>"");
}
header('Content-type: application/json');
echo(json_encode($resultado));
?>