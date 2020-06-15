<?php
    require_once '../ConexionClase.php';
    require_once '../config.php';

    $conexionDB = new ConexionBBDD($config['direccion'],$config['nombreDB'], $config['usuario'],$config['contraseña']);

    $rc_servicio_medico = "";
    foreach ($_POST['rc_servicio_medico'] as $key => $value) {
        $rc_servicio_medico .= $value . " ";
    }
    $rc_aviso = "En caso de accidente avisar a: " . $_POST['rc_emergencia']['rc_nombre'] ." Parentesco: " . $_POST['rc_emergencia']['rc_parentesco'] . " Domicilio: ". $_POST['rc_emergencia']['rc_domicilio']  ." Telefono: " . $_POST['rc_emergencia']['rc_telefono'];
    $rc_comentarios_curso = "¿Como te enteraste del curso? " . $_POST['rc_comentarios']['rc_enterar_curso'] ." ¿Que utilidad tiene para ti el curso? " . $_POST['rc_comentarios']['rc_utilidad_curso']; 
    $rc_trabajo_estudios = "Nombre de la escuela o lugar de trabajo: " . $_POST['rc_trabajo_escuela']['rc_nombre'] ." Telefono: " . $_POST['rc_trabajo_escuela']['rc_telefono'] . "  Direccion: " . $_POST['rc_trabajo_escuela']['rc_direccion'] ." Puesto o nivel de estudios: "  . $_POST['rc_trabajo_escuela']['rc_puesto'] . " Area o Carrera: " . $_POST['rc_trabajo_escuela']['rc_carrera']  ." Horario: "  . $_POST['rc_trabajo_escuela']['rc_horario']  . " Titulo: "  . $_POST['rc_trabajo_escuela']['rc_titulo'];
    $_POST['rc_servicio_medico'] = $rc_servicio_medico;
    $_POST['rc_aviso'] = $rc_aviso;
    $_POST['rc_comentarios_curso'] = $rc_comentarios_curso;
    $_POST['rc_trabajo_estudios'] = $rc_trabajo_estudios;
    unset($_POST['rc_servicio_medico'],$_POST['rc_emergencia'],$_POST['rc_comentarios'],$_POST['rc_trabajo_escuela'],$_POST['rc_domicilio']);


    $conexionDB->insertaDatos('registro_comunidad', $_POST);

    $conexionDB->cierraConexion();

    echo 'success';
?>