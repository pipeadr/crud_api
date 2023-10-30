<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/vendor/autoload.php';
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=crud_api','root',''));


// leer los datos y mostrar
Flight::route('GET /users', function() {
    $sentencia = Flight::db()->prepare("SELECT * FROM `usuarios`");
    $sentencia->execute();
    $datos = $sentencia->fetchALL();

    Flight::json($datos);
});


// Recepciona los datos por POST y los inserta ala BD
Flight::route('POST /users', function() {
    $id = NULL;
    $nombre = (Flight::request()->data->name);
    $apellido = (Flight::request()->data->lastname);
    $correo = (Flight::request()->data->mail);
    $comandoSql = "INSERT INTO `usuarios` (`id`, `name`, `lastname`, `email`) VALUES (?,?,?,?)";
    $sentencia = Flight::db()->prepare($comandoSql);
    $sentencia->bindParam(1, $id);
    $sentencia->bindParam(2, $nombre);
    $sentencia->bindParam(3, $apellido);
    $sentencia->bindParam(4, $correo);
    $sentencia->execute();
    Flight::jsonp(["Usuario Registrado con exito"]);
    // print_r($sentencia);
});


// Borrar Registro
Flight::route('DELETE /users', function() {
    $id = (Flight::request()->data->id);
    $comandoSql = "DELETE FROM `usuarios`  WHERE id=?";
    $sentencia = Flight::db()->prepare($comandoSql);
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    Flight::jsonp(["Usuario Elimminado con exito"]);
    // print_r($id);

});

// Actualizar Datos

Flight::route('PUT /users', function() {
    $id = (Flight::request()->data->id);
    $nombre = (Flight::request()->data->name);
    $apellido = (Flight::request()->data->lastname);
    $correo = (Flight::request()->data->mail);
    $comandoSql = "UPDATE `usuarios` SET name=?,  lastname=?, email=? WHERE id=?";
    $sentencia = Flight::db()->prepare($comandoSql);
    $sentencia->bindParam(1, $nombre);
    $sentencia->bindParam(2, $apellido);
    $sentencia->bindParam(3, $correo);
    $sentencia->bindParam(4, $id);
    $sentencia->execute();
    Flight::jsonp(["Usuario Actualizado Correctamente"]);
     print_r($id);

});


// busqueda personalizada
Flight::route('GET /users/@id', function($id) {
    $sentencia = Flight::db()->prepare("SELECT * FROM `usuarios` WHERE id=?");
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    $datos = $sentencia->fetchALL();

    Flight::json($datos);
    // print_r($id);
});


Flight::start();

