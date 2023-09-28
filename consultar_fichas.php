<?php

/*
 * Trae la informacion de todos las cadenas creadas.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */


// DB table to use
$table = 'ficha';

// Table's primary key
$primaryKey = 'id_ficha';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names

$GLOBALS['programa'] = "";

$columns = array(
    array(
        'db' => '`f`.`id_ficha`',
        'dt' => 'DT_RowId',
        'field' => 'DT_RowId', 'as' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => '`f`.`numeroFicha`', 'dt' => 'numFicha', 'field' => 'numFicha', 'as' => 'numFicha'),
    array(
        'db' => '`f`.`id_programa`',
        'dt' => 'idPrograma',
        'field' => 'idPrograma', 'as' => 'idPrograma',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            $GLOBALS['programa'] = '<input type="hidden" id="idprogramaIntH" 
                    name="idprogramaIntH" value="' . $d . '">';
        }
    ),
    array('db' => '`p`.`programa`',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return $GLOBALS['programa'] . $d;
        },
        'dt' => 'nombre_PF', 'field' => 'nombre_PF', 'as' => 'nombre_PF'),    
    array('db' => '`f`.`estado`', 'dt' => 'estado',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 3) {
                $estado = "<p class='label-warning'>Finalizada<p>";
            } else if ($d === 1) {
                $estado = "<p class='label-success'>Etapa Lectiva</p>";
            } else if ($d === 2) {
                $estado = "<p class='label-primary'>Etapa Practica</p>";
            } else if ($d === 4) {
                $estado = "<p class='label-danger'>Cancelada</p>";
            }
            return $estado;
        }, 'field' => 'estado', 'as' => 'estado'),
    array('db' => '`f`.`id_ficha`', 'dt' => 'horario',
        'formatter' => function ($d, $row) {
            return '<center><a href="#" title="' . $d . '"  class="mostrar_horario"><i class="fa fa-calendar-check-o fa-2x"></i></a></center>';
        }, 'field' => 'horario', 'as' => 'horario'),
    array('db' => '`f`.`id_ficha`', 'dt' => 'Aprendices',
        'formatter' => function ($d, $row) {
            return '<center><a href="#" title="' . $d . '"  class="mostrar_Aprendices"><i class="fa fa-users fa-2x"></i></a></center>';
        }, 'field' => 'Aprendices', 'as' => 'Aprendices'),    
    array('db' => '`f`.`id_ficha`', 'dt' => 'editar',
        'formatter' => function ($d, $row) {
            return '<center><a href="#" title="' . $d . '"  class="modificar"><i class="fa fa-pencil-square-o fa-2x"></i></a></center>';
        }, 'field' => 'editar', 'as' => 'editar'),
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

include '../MDB/setConfigDB.php';
require( '../datatablesscript/ssp.customized.class.php' );

$joinQuery = " FROM `ficha` AS `f` INNER JOIN `tbl_programas` AS `p` ON `f`.`id_programa` = `p`.`id_Programa` ";
$extraWhere = "";
$groupBy = "";
$having = "";

echo json_encode(
        SSP::simple($_GET, $sql_details_ptv_local, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
