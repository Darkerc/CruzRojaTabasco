<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->insertaDatos('lista_cursos', $_POST);

    $conexionDB->cierraConexion();

    echo 'success';
?>
