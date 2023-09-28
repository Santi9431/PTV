<?php

/*
 * Trae la informacion de todos las cadenas creadas.
 */

date_default_timezone_set('America/Bogota');

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */


// DB table to use
$table = 'registro_usuarios';

// Table's primary key
$primaryKey = 'id_registro_usuarios';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier - in this case object
// parameter names

$GLOBALS['programa'] = "";

$columns = array(
    array(
        'db' => '`ru`.`id_usuario`',
        'dt' => 'DT_RowId',
        'field' => 'DT_RowId', 'as' => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ),
    array('db' => '`u`.`documento`', 'dt' => 'miDocumento', 'field' => 'miDocumento', 'as' => 'miDocumento'),
    array(
        'db' => '`ru`.`id_programa`',
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
    array('db' => '`ru`.`nombre`', 'dt' => 'minombre', 'field' => 'minombre', 'as' => 'minombre'),
    array('db' => '`ru`.`direccion`', 'dt' => 'midir', 'field' => 'midir', 'as' => 'midir'),
    array('db' => '`ru`.`celular`', 'dt' => 'micel', 'field' => 'micel', 'as' => 'micel'),
    array('db' => '`u`.`email`', 'dt' => 'mimail', 'field' => 'mimail', 'as' => 'mimail'),
    array(
        'db' => '`ru`.`id_ficha`',
        'dt' => 'idFicha',
        'field' => 'idFicha', 'as' => 'idFicha',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            $GLOBALS['programa'] .= '<input type="hidden" id="idFichaIntH" 
                    name="idFichaIntH" value="' . $d . '">';
        }
    ),
    array(
        'db' => '`ru`.`foto`',
        'dt' => 'mifoto',
        'field' => 'mifoto', 'as' => 'mifoto',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return '<center><a href="'.$d.'" data-toggle="modal" class="fotoView"><i class="fa fa-camera fa-2x"></i></a></center>';
        }
    ),

    array('db' => '`ru`.`numeroFicha`',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return $GLOBALS['programa'] . $d;
        },
        'dt' => 'mificha', 'field' => 'mificha', 'as' => 'mificha'),    
    array('db' => '`u`.`estado`', 'dt' => 'estado',
        'formatter' => function ($d, $row) {
            $estado = "";
            if ($d === 3) {
                $estado = "<p class='label-warning'>Aplazado<p>";
            } else if ($d === 1) {
                $estado = "<p class='label-success'>Matriculado</p>";
            } else if ($d === 2) {
                $estado = "<p class='label-primary'>Cancelado</p>";
            } 
            return $estado;
        }, 'field' => 'estado', 'as' => 'estado'),    
    array('db' => '`u`.`id_usuarios`', 'dt' => 'Novedades',
        'formatter' => function ($d, $row) {
            return '<center><a href="#" title="' . $d . '"  class="mostrar_novedades"><i class="fa fa-comment fa-2x"></i></a></center>';
        }, 'field' => 'Novedades', 'as' => 'Novedades'),    
    array('db' => '`u`.`id_usuarios`', 'dt' => 'editar',
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

$joinQuery = " FROM `registro_usuarios` AS `ru` INNER JOIN `usuarios` AS `u` ON `ru`.`id_usuario` = `u`.`id_usuarios` ";
$extraWhere = "";
$groupBy = "";
$having = "";

echo json_encode(
        SSP::simple($_GET, $sql_details_ptv_local, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);