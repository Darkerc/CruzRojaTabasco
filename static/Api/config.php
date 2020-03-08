<?php

    require 'CORS.php';
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');


    $config = [
        'direccion' => 'localhost',
        'nombreDB' => 'cruzroja',
        'usuario' => 'root',
        'contraseña' => ''
    ];

?>