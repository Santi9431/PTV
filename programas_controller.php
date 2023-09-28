<?php

/*
 * Controller to Modulo Roles.
 */
session_start();
include_once("../../model/programas/programas_model.php");

//captura de datos
$opcion = isset($_GET['opcion']) ? htmlspecialchars(stripslashes($_GET['opcion'])) : '0';
$id_Programa = isset($_POST['idP']) ? $_POST['idP'] : "";
$programa = isset($_POST['nombre']) ? $_POST['nombre'] : "";
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : "";
$version = isset($_POST['version']) ? $_POST['version'] : "";
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";
$url_programa = isset($_FILES['url_programa']) ? $_FILES['url_programa'] : [];

//Instancia de tipo ROL
$GLOBALS['objProgr'] = new model_Programas();

switch ($opcion) {
    //Insert
    case '1': {
            $GLOBALS['objProgr']->setPrograma($programa);
            $GLOBALS['objProgr']->setCodigo($codigo);
            $GLOBALS['objProgr']->setVersion($version);
            $GLOBALS['objProgr']->setEstado($estado);
            $GLOBALS['objProgr']->setUrl_programa($url_programa);
            $GLOBALS['objProgr']->guardarPrograma($codigo, $programa, $version, $estado, $url_programa);
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }

    //Update
    case '2': {
            $GLOBALS['objProgr']->setIdprograma($id_Programa);
            $GLOBALS['objProgr']->setPrograma($programa);
            $GLOBALS['objProgr']->setCodigo($codigo);
            $GLOBALS['objProgr']->setVersion($version);
            $GLOBALS['objProgr']->setEstado($estado);

            $urlantigua = "";
            $count = count($url_programa);
            if ($count > 0) {
                $GLOBALS['objProgr']->setUrl_programa($url_programa);                
            } else {
                $GLOBALS['objProgr']->setUrl_programa("");
            }
            $urlantigua = isset($_POST['url_old']) ? $_POST['url_old'] : "";
            $GLOBALS['objProgr']->actualizarProgramas($urlantigua);
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }

    //change estado
    case '3': {
            $GLOBALS['objProgr']->setIdprograma($idPrograma);
            $GLOBALS['objProgr']->setEstado($estado);
            $GLOBALS['objProgr']->update_estado_programa();
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }

    //Change state multiple row
    case '4': {
            $datosEstados = isset($_POST['datos']) ? json_decode($_POST['datos'], true) : "";
            $GLOBALS['objProgr']->update_estado_programa_multiple_fila($datosEstados);
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }

    //Cargar PF COMBO
    case '5': {
            $GLOBALS['objProgr']->select_all_programas($idCadena);
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }
        
    //Cargar PF COMBO programas del instructor
    case '6': {
            $GLOBALS['objProgr']->select_all_programas_instructor($idCadena, $instrucor);
            echo json_encode(array('msg' => $GLOBALS['objProgr']->getMensaje()));
            break;
        }
    default:{
        break;
    }
}

