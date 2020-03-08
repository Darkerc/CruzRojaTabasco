<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $conexionDB->actualizaDatos('lista_cursos',['curso_clave' => $_POST['curso_clave']],$_POST);

    $conexionDB->cierraConexion();

    echo "success";
?>