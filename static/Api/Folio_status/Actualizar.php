<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->actualizaDatos('folios_estatus',['fs_id' => $_POST['fs_id']],$_POST);

    $conexionDB->cierraConexion();

    echo "success";
?>