<?php

/*
 * Trae la informacion de todos los ROLES creados.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'migraciones';

// Table's primary key
$primaryKey = 'id_migraciones';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = array(
    array(
        'db' => 'id_migraciones',
        'dt' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => 'id_migraciones', 'dt' => 'migracionCodigo'),
    array('db' => 'descripcion', 'dt' => 'descripcionMigracion'),
    array('db' => 'nombre_de_migracion', 'dt' => 'nombreMigracion'),
    array(
        'db' => 'is_load',
        'dt' => 'cargado',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 1) {
                $estado = "Cargado en servidor";
            } else {
                $estado = "Pendiente por cargar";
            }
            return $estado;
        }
    ),
    array(
        'db' => 'is_load',
        'dt' => 'cargado_icon',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 1) {
                $estado = '<center><a href="#" data-toggle="modal" class="enabled_disabled"><i class="fa fa-toggle-on fa-2x"></i></a></center>';
            } else {
                $estado = '<center><a href="#" data-toggle="modal" class="enabled_disabled"><i class="fa fa-toggle-off fa-2x"></i></a></center>';
            }
            return $estado;
        }
    )
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
