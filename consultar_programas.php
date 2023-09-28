<?php

/*
 * Trae la informacion de todos los ROLES creados.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_programas';

// Table's primary key
$primaryKey = 'id_Programa';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names
$columns = array(
    array(
        'db' => 'id_Programa',
        'dt' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => 'id_Programa', 'dt' => 'miIdPrograma'),
    array('db' => 'codigo', 'dt' => 'codigoPrograma'),
    array('db' => 'programa', 'dt' => 'nombrePrograma'),
    array('db' => 'version', 'dt' => 'versionPrograma'),
    array(
        'db' => 'estado',
        'dt' => 'estadoPrograma',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === "1") {
                $estado = "Habilitado";
            } else {
                $estado = "Deshabilitado";
            }
            return $estado;
        }
    ),
    array(
        'db' => 'url_programa',
        'dt' => 'urlprograma',
        'formatter' => function ($d, $row) {
            $estado = '<center><a target="_blank" href="'.$d.'"  class="verPDF"><i class="fa fa-file-pdf-o fa-2x"></i></a></center>';
            return $estado;
        }
    ),
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

