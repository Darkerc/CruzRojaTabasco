<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $nombre = $_POST['nombre'];
    $apellido_p = $_POST['apellido_p'];
    $apellido_m = $_POST['apellido_m'];
    $genero = $_POST['genero'];
    $edad = $_POST['edad'];
    $tipo_registro1 = $_POST['tipo_registro'];
    $clave_curso = $_POST['clave_curso'];
    $contacto = $_POST['contacto'];
    $folio=strtoupper($tipo_registro1.substr($genero,0,1).'CR'.$edad.substr($apellido_p,0,4).substr($contacto,1,2).substr($nombre,2,4));

    $_POST['folio'] = $folio;

    $conexionDB->insertaDatos('preregistros', $_POST);

    $conexionDB->cierraConexion();

    echo 'success';
?>