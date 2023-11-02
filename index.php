<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once "./conexion/conexion.php";
$conn = new conexion;

$metodo = $_SERVER['REQUEST_METHOD'];
// print_r($metodo);
// $path = isset($_SERVER['PATH_INFOR']) ? $_SERVER['PATH_INFOR']:'/';
// $buscarId = explode('/', $path);
// $id = ($path !== '/') ? end($buscarId):null;


switch($metodo) {
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        consulta($conn, $id);
        break;
    case 'POST':
        insertar($conn);
        break;

    case 'PUT':
        actualizar($conn);
        break;

    case 'DELETE':
        eliminar($conn);
        break;
    default: 
      echo "Método no permitido";
      break;
}


function consulta($conexion, $id) {
    // $comandoSql  = "SELECT * FROM usuarios";
    $id_ = (int)$id;
    $comandoSql  = ($id === null) ? "SELECT * FROM usuarios": "SELECT * FROM usuarios WHERE id = $id_";
    $resultado = $conexion->consulta_($comandoSql);
    if($resultado) {
        $dato = array();
        while($f = $resultado->fetch_assoc()) {
            $dato[] = $f;
        }
        echo json_encode($dato);
    }
}


function insertar($conexion) {

    $dato = json_decode(file_get_contents('php://input'), true);
    $id = null;
    $nombre = $dato['nombre'];
    $apellido = $dato['apellido'];
    $correo = $dato['correo'];
    $id = (int)$id; // Asegura que $id sea un entero
    if((filter_var($nombre, FILTER_SANITIZE_STRING)) &&  (filter_var($apellido, FILTER_SANITIZE_STRING)) && (filter_var($correo, FILTER_VALIDATE_EMAIL) === false)) {
        $response = array(); 
        $response['message'] = "Diga un Dato correcto";
    } else {
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        $apellido = htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8');
        $comandoSql = "INSERT INTO `usuarios` (`id`, `name`, `lastname`, `email`) VALUES (?,?,?,?)";
        $resultado = $conexion->insertar_($comandoSql, $id, $nombre, $apellido, $correo);
        echo $resultado;
    }



}


function eliminar($conexion) {
    $dato = json_decode(file_get_contents('php://input'), true);
    $id = $dato['id'];
    $id_ = (int)$id; 
    $comandoSql = "DELETE FROM `usuarios`  WHERE id=?";
    $resultado = $conexion->eliminar_($comandoSql, $id_);
    echo $resultado;
    
}

function actualizar($conexion) {
    $dato = json_decode(file_get_contents('php://input'), true);
    $nombre = $dato['nombre'];
    $apellido = $dato['apellido'];
    $correo = $dato['correo'];
    $id = $dato['id'];
    $comandoSql = "UPDATE `usuarios` SET name=?,  lastname=?, email=? WHERE id=?";
    $resultado = $conexion->actualizar_($comandoSql, $nombre, $apellido, $correo, $id);
    echo $resultado;
    
} 

?>