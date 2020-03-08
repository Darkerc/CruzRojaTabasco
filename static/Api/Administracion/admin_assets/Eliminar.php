<?php
    require_once '../../ConexionClase.php';
    require_once '../../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->eliminaDatosSeleccionados('admin_assets',['id' => $_POST['id']]);

    echo "success";
?>