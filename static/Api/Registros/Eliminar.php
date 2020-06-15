<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->eliminaDatosSeleccionados('registro_comunidad',['rc_id' => $_POST['rc_id']]);

    echo "success";
?>