<?php

require 'flight/Flight.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api','root',''));

// mostrar todos los datos
Flight::route('GET /alumnos', function () {

    $sentencia = Flight::db()->prepare("SELECT * FROM alumnos"); // 1 hacemos la seleccion
    $sentencia->execute();  // la ejecutamos
    $datos = $sentencia->fetchAll(); // la mostramos 
    Flight::json($datos); // mostrarlo en formato json
});

// recepciona los datos por metodos post e inserta los datos en una base de datos
Flight::route('POST /alumnos', function () {

    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);
    $sentencia = Flight::db()->prepare("INSERT INTO alumnos (nombres,apelllidos) VALUES (?,?)"); // hacemos la consulta
    $sentencia->bindparam(1,$nombres); // en esta parte estamos remplazando los datos que estan dentro de VALUES(?,?)
    $sentencia->bindparam(2,$apellidos);// en esta parte estamos remplazando los datos que estan dentro de VALUES(?,?)
    $sentencia->execute();
    
    Flight::jsonp(["Alumno agregado"]);
});

// Borrar registros
Flight::route('DELETE /alumnos', function () {

$id = (Flight::request()->data->id);
$sentencia = Flight::db()->prepare("DELETE FROM alumnos WHERE id = ? ");
$sentencia->bindparam(1,$id); // en esta parte estamos remplazando los datos que estan dentro de VALUES(?,?)
if($sentencia->execute()){
    Flight::jsonp(["alumno borrado"]);
} else {
    Flight::jsonp(["No se puede borrar el dato"]);
}
});

//actualizar dato
Flight::route('PUT /alumnos', function () {
    $id = (Flight::request()->data->id);
    $nombres=(Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);

    $sentencia = Flight::db()->prepare("UPDATE alumnos SET nombres = ?, apelllidos = ? WHERE id = ? ");
    $sentencia->bindparam(1,$nombres); 
    $sentencia->bindparam(2,$apellidos); 
    $sentencia->bindparam(3,$id); 
    $sentencia->execute();
    
    Flight::jsonp(["alumno editado"]);
    });

    //para buscar un solo dato 
    Flight::route('GET /alumnos/@ID', function ($id) {
     
    $sentencia = Flight::db()->prepare("SELECT * FROM alumnos WHERE id = ?");
    $sentencia->bindparam(1,$id);
    $sentencia->execute();
    $datos = $sentencia->fetchAll(); // fetchAll() le estamos diciendo que nos traiga lo que se le mando a pedir por la query
    Flight::json($datos);
    });
    
    
    
Flight::start();
