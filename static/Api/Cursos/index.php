<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);
    $datos = $conexionDB->datosTodos("lista_cursos",PDO::FETCH_ASSOC,true);
    echo json_encode($datos);
    $conexionDB->cierraConexion();
?>