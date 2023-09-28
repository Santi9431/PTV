<?php

/**
 * No es un clase, es un archivo que vincula la vista con el modelo a traves del JS
 */

/*
 * Controller to Modulo Roles.
 */

include_once("../../model/aprendiz/model_aprendiz.php");

// Captura de datos
//estos campos son campos que van en el js igualitos

$opcion = isset($_GET['opcion']) ? htmlspecialchars(stripslashes($_GET['opcion'])) : '0';
$idRegistroUsuario = isset($_POST['id_registro_usuarios']) ? $_POST['id_registro_usuarios '] : "";
$idUsuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : "";
$documento    = isset($_POST['identificacion']) ? $_POST['identificacion'] : "";
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
$email = isset($_POST['correoeletronico']) ? $_POST['correoeletronico'] : "";
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
$celular = isset($_POST['celular']) ? $_POST['celular'] : "";
$foto =  isset($_FILES['foto']) ? $_FILES['foto'] : [];
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";
$numeroFicha = isset($_POST['numeroFicha']) ? $_POST['numeroFicha'] : "";

$clave = "CTMA" . $documento;

$idFichaPrograma  = isset($_POST['ficha']) ? $_POST['ficha'] : "";
$idFP = explode(";", $idFichaPrograma);
$idFicha = isset($idFP[0]) ? $idFP[0] : 0;
$idPrograma = isset($idFP[1]) ? $idFP[1] : 0;



// Instancia de Modelo de Migraciones
$GLOBALS['aprendiz'] = new model_aprendiz();

switch ($opcion) {

        //cargar combo PF
    case '1': {
            $GLOBALS['aprendiz']->cargarComboFichas();
            echo json_encode(array('msg' => $GLOBALS['aprendiz']->get_mensaje()));
            break;
        }

        //Guardar
    case '2': {
            $GLOBALS['aprendiz']->set_documento($documento);
            $GLOBALS['aprendiz']->set_nombre($nombre);
            $GLOBALS['aprendiz']->set_email($email);
            $GLOBALS['aprendiz']->set_direccion($direccion);
            $GLOBALS['aprendiz']->set_id_ficha($idFicha);
            $GLOBALS['aprendiz']->set_id_programa($idPrograma);
            $GLOBALS['aprendiz']->set_numeroFicha($numeroFicha);
            $GLOBALS['aprendiz']->set_celular($celular);

            $count = count($foto);
            if ($count > 0) {
                // Read the image file
                $imageData = file_get_contents($foto['tmp_name']);

                // Convert the image data to Base64
                $base64Image = base64_encode($imageData);

                $GLOBALS['aprendiz']->set_foto($base64Image);
            } else {
                $GLOBALS['aprendiz']->set_foto("");
            }


            $GLOBALS['aprendiz']->set_estado($estado);
            $GLOBALS['aprendiz']->set_id_rol(2);
            $GLOBALS['aprendiz']->guardarUsuario();
            echo json_encode(array('msg' => $GLOBALS['aprendiz']->get_mensaje()));
            break;
        }


    case '3': {
            $GLOBALS['aprendiz']->set_id_usuario($idUsuario);
            $GLOBALS['aprendiz']->set_documento($documento);
            $GLOBALS['aprendiz']->set_nombre($nombre);
            $GLOBALS['aprendiz']->set_email($email);
            $GLOBALS['aprendiz']->set_direccion($direccion);
            $GLOBALS['aprendiz']->set_id_ficha($idFicha);
            $GLOBALS['aprendiz']->set_id_programa($idPrograma);
            $GLOBALS['aprendiz']->set_numeroFicha($numeroFicha);
            $GLOBALS['aprendiz']->set_celular($celular);
            
            $count = count($foto);
            if ($count > 0) {
                // Read the image file
                $imageData = file_get_contents($foto['tmp_name']);

                // Convert the image data to Base64
                $base64Image = base64_encode($imageData);

                $GLOBALS['aprendiz']->set_foto($base64Image);
            } else {
                $GLOBALS['aprendiz']->set_foto("");
            }

            $GLOBALS['aprendiz']->set_estado($estado);
            $GLOBALS['aprendiz']->actualizarUsuario();
            echo json_encode(array('msg' => $GLOBALS['aprendiz']->get_mensaje()));
            break;
        }

    default: {
            break;
        }
}