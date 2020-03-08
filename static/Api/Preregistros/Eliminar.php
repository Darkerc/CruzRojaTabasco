<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->eliminaDatosSeleccionados('preregistros',['idPreregistro' => $_POST['idPreregistro']]);

    echo "success";
?>
