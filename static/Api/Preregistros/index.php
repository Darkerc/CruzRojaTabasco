<?php

    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $data = $conexionDB->datosSeleccionados('preregistros', ['tipo_registro' => $_POST['tipo_registro']],PDO::FETCH_ASSOC,true);

    echo json_encode($data);

    $conexionDB->cierraConexion();



?>