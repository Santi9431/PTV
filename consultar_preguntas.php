<?php

/*
 * Trae la informacion de todos los ROLES creados.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */



// DB table to use
$table = 'tbl_pregunta';

// Table's primary key
$primaryKey = 'id_pregunta';

// Array of database columns which should be read and sent back to DataTables.
// The db parameter represents the column name in the database, while the dt
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = array(
    array(
        'db' => 'id_pregunta',
        'dt' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }, 'field' => 'DT_RowId', 'as' => 'DT_RowId'
    ),
    array('db' => 'id_pregunta', 'dt' => 'idPregunta', 'field' => 'idPregunta', 'as' => 'idPregunta'),
    array('db' => 'texto_pregunta', 'dt' => 'miPregunta',  'field' => 'miPregunta', 'as' => 'miPregunta'), // este va en html
    array(
        'db' => 'estado',
        'dt' => 'miestado',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 1) {
                $estado = "Habilitado";
            } else {
                $estado = "Deshabilitado";
            }
            return $estado;
        }, 'field' => 'miestado', 'as' => 'miestado'
    ),
    array(
        'db' => 'foto',
        'dt' => 'mifoto',
        'field' => 'mifoto', 'as' => 'mifoto',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return '<center><a href="'.$d.'" data-toggle="modal" class="fotoView"><i class="fa fa-camera fa-2x"></i></a></center>';
        }
    ),
    array(
        'db' => 'estado',
        'dt' => 'estado_icon',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 1) {
                return '<center><a href="#" title="' . $d . '"  class="cambiar_estado_role_permiso"><i class="fa fa-toggle-on fa-2x"></i></a></center>';
            } else {
                return '<center><a href="#" title="' . $d . '"  class="cambiar_estado_role_permiso"><i class="fa fa-toggle-off fa-2x"></i></a></center>';
            }
        }, 'field' => 'estado_icon', 'as' => 'estado_icon'
    ),
    array('db' => 'id_pregunta', 'dt' => 'respuestas',
    'formatter' => function ($d, $row) {
        return '<center><a href="#" title="' . $d . '"  class="modificar"><i class="fa fa-question-circle fa-2x"></i></a></center>';
    }, 'field' => 'respuestas', 'as' => 'respuestas') 
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

 include '../MDB/setConfigDB.php';

 require( '../datatablesscript/ssp.class.php' );
 
 echo json_encode(
         SSP::simple($_GET, $sql_details_ptv_local, $table, $primaryKey, $columns)
 );