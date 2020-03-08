<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';
    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->actualizaDatos('preregistros', ['idPreregistro' => $_POST['idPreregistro']], $_POST);

    $conexionDB->cierraConexion();

    echo 'Success';
?>