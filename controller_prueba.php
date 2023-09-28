<?php

/**
 * No es un clase, es un archivo que vincula la vista con el modelo a traves del JS
 */

/*
 * Controller to Modelo_prueba
 */

include_once("../../model/prueba/Modelo_prueba.php");



// Captura de datos
//estos campos son campos que van en el js igualitos
$opcion = isset($_GET['opcion']) ? htmlspecialchars(stripslashes($_GET['opcion'])) : '0';
$nombre_prueba = isset($_POST['nombrePregunta']) ? $_POST['nombrePregunta'] : "";
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";
$p_total = isset($_POST['puntuacion']) ? $_POST['puntuacion'] : "";
$objetivos= isset($_POST['objetivos']) ? $_POST['objetivos'] : "";
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
$indicaciones = isset($_POST['indicaciones']) ? $_POST['indicaciones'] : "";
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";

// Instancia de Modelo de Migraciones
$GLOBALS['tbl_pruebas'] = new model_prueba();

switch ($opcion) {
        // Llamada a la función de guardar migración
    case '1': {
            $GLOBALS['tbl_pruebas']->guardarPrueba($nombre_prueba, $fecha, $p_total, $objetivos, $descripcion, $indicaciones, $estado);
            echo json_encode(array('msg' => $GLOBALS['tbl_pruebas']->get_mensaje()));
            break;
        }
    }
