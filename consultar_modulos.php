<?php

/*
 * Trae la informacion de todos los ROLES creados.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

 //captura de datos
$idModulo = isset($_GET['param1']) ? htmlspecialchars(stripslashes($_GET['param1'])) : '0';

// DB table to use
$table = 'modulos';

// Table's primary key
$primaryKey = 'id_modulos';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = array(
    array(
        'db' => 'id_modulos',
        'dt' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => 'id_modulos', 'dt' => 'mimodulos'),
    array('db' => 'codigo', 'dt' => 'micodigo'),
    array('db' => 'nombre', 'dt' => 'minombre'), // este va en html
    array('db' => 'nombre_personalizado', 'dt' => 'minombre_personalizado'),
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
        }, 'field' => 'estado_rol_modulo_permiso', 'as' => 'estado_rol_modulo_permiso'

        
    ),
        //array('db' => 'estado_rol', 'dt' => 'estadoRol')
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
