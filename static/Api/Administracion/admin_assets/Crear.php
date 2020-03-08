<?php
require_once '../../ConexionClase.php';
require_once '../../config.php';

$conexionDB = new ConexionBBDD($config['direccion'], $config['nombreDB'], $config['usuario'], $config['contraseña']);

if (isset($_FILES['FileImage'])) {
    try {
        $ImagenProducto = $_FILES['FileImage']['name'];
        $ImagenTMP = $_FILES['FileImage']['tmp_name'];
        $tipoImagen = $_FILES['FileImage']['type'];
        $tamagnoImagen = $_FILES['FileImage']['size'];
        $formatosPermitidos = array("jpg", "jpeg", "png", "gif");

        for ($i = 0; $i < count($formatosPermitidos); $i++) {
            $formatosPermitidos[$i] = "image/" . $formatosPermitidos[$i];
        }

        if (in_array($tipoImagen, $formatosPermitidos)) {

            //Ruta de la carpeta destino del servidor
            $carpetaDestino = getcwd() . "/assets/";
            $url_web_archivo = "/admin/Api/Administracion/admin_assets/assets/" . $ImagenProducto;
            $conexionDB->insertaDatos('admin_assets',[
                "extencion_archivo" => $tipoImagen,
                "url_archivo" => $carpetaDestino . $ImagenProducto,
                "url_web_archivo" => $url_web_archivo,
                "prosedencia_archivo" => 'carousel'
            ]);

            //Movemos la imagen del directorio temporal al directorio escogido

            move_uploaded_file($ImagenTMP, $carpetaDestino . $ImagenProducto);
        } else {
            throw new Exception("Tipo de archivo no permitido");
        }
    } catch (Exception $e) {
        echo "Error";
        echo $e;
    }
} else if (isset($_POST['URLImage'])) {
    $url_web_archivo = $_POST['URLImage'];
    $conexionDB->insertaDatos('admin_assets',[
        "extencion_archivo" => 'No asignado',
        "url_archivo" => 'No asignado',
        "url_web_archivo" => $url_web_archivo,
        "prosedencia_archivo" => 'carousel'
    ]);
} else {
    echo "No se realizo ninguna accion";
}

$conexionDB->cierraConexion();

echo 'success';
?>