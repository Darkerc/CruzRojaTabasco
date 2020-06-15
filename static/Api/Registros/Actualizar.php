<?php 
    require_once '../ConexionClase.php';
    require_once '../config.php';
    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->actualizaDatos('registro_comunidad',['rc_id' => $_POST['rc_id']], $_POST);

    $conexionDB->cierraConexion();

    echo 'Success';
?>