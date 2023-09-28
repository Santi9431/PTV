<?php

/**
 * No es un clase, es un archivo que vincula la vista con el modelo a traves del JS
 */

/*
 * Controller to Modulo Roles.
 */

include_once("../../model/ficha/modulo_fichas.php");



// Captura de datos
//estos campos son campos que van en el js igualitos
$opcion = isset($_GET['opcion']) ? htmlspecialchars(stripslashes($_GET['opcion'])) : '0';
$idFicha = isset($_POST['id_ficha']) ? $_POST['id_ficha'] : "";
$numficha = isset($_POST['ficha']) ? $_POST['ficha'] : "";
$idPrograma = isset($_POST['idPrograma']) ? $_POST['idPrograma'] : "";
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";


// Instancia de Modelo de Migraciones
$GLOBALS['ficha'] = new model_fichas();

switch ($opcion) {
        // Llamada a la función de guardar migración
    case '1': {
            $GLOBALS['ficha']->guardarFicha($numficha, $idPrograma, $estado);
            echo json_encode(array('msg' => $GLOBALS['ficha']->get_mensaje()));
            break;
        }




        //cargar combo PF
    case '4': {
            $GLOBALS['ficha']->cargarComboProgramas();
            echo json_encode(array('msg' => $GLOBALS['ficha']->get_mensaje()));
            break;
        }


    default:
        break;
}
