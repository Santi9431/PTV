<?php
/**
 * No es un clase, es un archivo que vincula la vista con el modelo a traves del JS
 */

 /*
 * Controller to Modulo Roles.
 */

include_once("../../model/roles/model_roles.php");

//captura de datos
$opcion = isset($_GET['opcion']) ? htmlspecialchars(stripslashes($_GET['opcion'])) : '0';
$IdRol = isset($_POST['id_roles']) ? $_POST['id_roles'] : "";
$nombre_rol = isset($_POST['nombre_rol']) ? $_POST['nombre_rol'] : "";
$estado = isset($_POST['estado']) ? $_POST['estado'] : "";

//Instancia de MODULO
$GLOBALS['roles'] = new model_roles();

switch ($opcion) {

    //Insert
    case '1': {
        $GLOBALS['roles']->set_nombre_rol($nombre_rol);
        $GLOBALS['roles']->set_estado($estado);     
        $GLOBALS['roles']->guardarMiroles();
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }

    // Actualizar
    case '2': {
        $GLOBALS['roles']->set_nombre_rol($nombre_rol);
        $GLOBALS['roles']->set_id_roles($IdRol);
        $GLOBALS['roles']->set_estado($estado);     
        $GLOBALS['roles']->actualizarMiroles();
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }

    // Habilitar o Deshabilitar
    case '3': {
        $GLOBALS['roles']->set_id_roles($IdRol);
        $GLOBALS['roles']->set_estado($estado);
        $GLOBALS['roles']->update_estado();
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }

    // cambiar los estados de los modulos
    case '4': {
        $datosEstados = isset($_POST['datos']) ? json_decode($_POST['datos'], true) : "";
        $GLOBALS['roles']->update_estado_roles_multiple_fila($datosEstados);
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }

     //cargar roles activos
    case '5': {                
        echo json_encode(array('msg' => $GLOBALS['roles']->get_all_modulos_active()));
        break;
    }
    
     //Insertar modificar roles asociados al modulo
     case '6': {
        $idModuloRol = isset($_POST['idRol']) ? $_POST['idRol'] : "";
        $modulos_estadop = isset($_POST['modulos_estadop']) ? json_decode($_POST['modulos_estadop'], true) : "";
        $GLOBALS['roles']->insertar_modificar_modulos($idModuloRol, $modulos_estadop);
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }
    
    //mostrar los estados de los roles en el formulario
    case '7': {
        $idModulo = isset($_POST['idRol']) ? $_POST['idRol'] : "";
        $GLOBALS['roles']->set_id_roles($IdRol);
        $GLOBALS['roles']->cargar_modulos_por_roles();
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }


    //Activar o desactivar los roles asociados a un modulo
    case '8': {
        $roles__modulo_estado = isset($_POST['roles_modulo_estado']) ? json_decode($_POST['roles_modulo_estado'], true) : "";
        $roles__modulo_id = isset($_POST['roles__modulo_id']) ? json_decode($_POST['roles__modulo_id'], true) : "";
        $GLOBALS['roles']->update_estado_rol_asociado_roles($roles__modulo_id, $roles__modulo_estado);
        echo json_encode(array('msg' => $GLOBALS['roles']->get_mensaje()));
        break;
    }

     default: {
        break;
    }

}    
