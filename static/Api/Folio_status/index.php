<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);
    $foliosData = $conexionDB->datosTodos("folios_estatus",PDO::FETCH_ASSOC,true);
    $preregistrosData = $conexionDB->datosTodos("preregistros",PDO::FETCH_ASSOC,true);

    for ($i=0; $i < count($foliosData); $i++) { 
        $datosPreregistro = $conexionDB->datosSeleccionados("preregistros",["folio" => $foliosData[$i]['fs_folio']],PDO::FETCH_ASSOC,false);
        $foliosData[$i]['datosPreregistro']= $datosPreregistro;
    }

    echo json_encode($foliosData);
?>