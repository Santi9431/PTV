<?php

// modelo para gestionar las conexiones

class model_modelos
{

    // variables de la clase 
    //coexion

    private $conexion = null;
    private $mensaje = "";

    // variables que mapean la tabla

    private $id_modulos = -1;
    private $codigo = "";
    private $nombre = "";
    private $nombre_personalizado = "";
    private $estado = 0;

    // campos para el Script
    private $scriptSQL = "";
    // En el constructor se configura la conexion a la base de datos
    // se realiza la conexion 
    function __construct()
    {
        include_once '../../model/MDB/configCon.php';
        if (!isset($this->conexion)) {
            $this->conexion = $mysql_adapter_ptv;
        }
    }

    // encapsulamiento de carpetas 

    public function set_id_modulos($mimodulos)
    {
        $this->id_modulos = $mimodulos;
    }
    public function get_id_modulos()
    {
        return $this->id_modulos;
    }
    public function set_codigo($codigo)
    {
        $this->codigo = $codigo;
    }
    public function get_codigo()
    {
        return $this->codigo;
    }
    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function set_nombre_personalizado($nombre_personalizado)
    {
        $this->nombre_personalizado = $nombre_personalizado;
    }
    public function get_nombre_personalizado()
    {
        return $this->nombre_personalizado;
    }
    public function set_estado($estado)
    {
        $this->estado = $estado;
    }
    public function get_estado()
    {
        return $this->estado;
    }
    public function get_mensaje()
    {
        return $this->mensaje;
    }
    public function set_scriptSQL($scriptSQL)
    {
        $this->scriptSQL = $scriptSQL;
    }


    public function guardarMimodulo()
    {
        $sql_seleccionar = ("SELECT COUNT(`id_modulos`) as total FROM modulos WHERE nombre = '$this->nombre' OR codigo = '$this->codigo'");
        $response = $this->conexion->select($sql_seleccionar);
        if ($response[0]['total'] > 0) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Ya existe un módulo con el mismo nombre ");
        } else {
            //insertamos
            $sql_insercion = "INSERT INTO `modulos` (`codigo`, `nombre`, `nombre_personalizado`,`estado`) 
            VALUES ('$this->codigo','$this->nombre', '$this->nombre_personalizado', $this->estado )";
            $response = $this->conexion->insert($sql_insercion);
            if ($response) {
                $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El modulo se creo correctamente");
            } else {
                // $this->eliminar_sql();
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al insertar, intente de nuevo");
            }
        }
    }
    public function actualizarMimodulo()
    {
        // Verificar si la propiedad "id_modulos" tiene un valor válido
        if ($this->id_modulos === -1) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "ID de módulo no válido.");
            return;
        }

        // Verificar si el "nombre" o "codigo" proporcionado ya existen en otros registros (excluyendo el actual)
        $sql_seleccionar = "SELECT COUNT(`id_modulos`) as total FROM modulos WHERE (nombre = '$this->nombre' OR codigo = '$this->codigo') AND id_modulos != $this->id_modulos";

        $response = $this->conexion->select($sql_seleccionar);
        if ($response[0]['total'] > 0) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Ya existe un módulo con el mismo nombre o código.");
            return;
        }

        // Realizar la operación de actualización
        $sql_actualizacion = "UPDATE `modulos` SET 
                                `codigo`='$this->codigo',
                                `nombre`='$this->nombre',
                                `nombre_personalizado`='$this->nombre_personalizado',
                                `estado`='$this->estado'
                                WHERE `id_modulos`='$this->id_modulos'";
        $response = $this->conexion->update($sql_actualizacion);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El módulo se actualizó correctamente.");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al actualizar el módulo, intente de nuevo.");
        }
    }
    /**
     * Modifica el estado
     */
    function update_estado()
    {
        //Actualizamos el rol
        $sql_update = "UPDATE `modulos` SET `estado`='$this->estado' WHERE `id_modulos`=$this->id_modulos";
        $response = $this->conexion->update($sql_update);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El Rol se modificó correctamente");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al modificar los datos, intente de nuevo");
        }
    }

    /** desde aca debo empezar la cpcion 4
     * Modifica el estado de todos los modulos enviados
     */
    function update_estado_modulo_multiple_fila($parametros)
    {
        //Actualizamos el rol        
        $response = $this->conexion->update_state_multiple_data("modulos", "id_modulos", "estado", $parametros);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "Los roles se modificaron correctamente");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al modificar los datos, intente de nuevo");
        }
    }

    public function get_all_roles_active()
    {

        $sql_veririficiacion = "SELECT  id_roles   AS id  , nombre_rol AS nombre "
            . "FROM `roles` WHERE (`estado` = '1')";
        $respuesta = $this->conexion->select($sql_veririficiacion);
        $combo = array();
        foreach ($respuesta as $row) {
            $datosCombo = array("id" => $row['id'], "value" => $row['nombre']);
            array_push($combo, $datosCombo);
        }
        return $combo;
    }


    //Funcion para insertar y modificar los roles asociadas a cada modulo
    // * @param type $id_roles
    // * @param type $parametros

    function insertar_modificar_roles($id_modulos, $parametros)
    {
        $sql_querys = array();
        foreach ($parametros as $key => $value) {
            $sql_insercion = "INSERT INTO `mvto_rol_modulos`(`id_modulo`, `id_rol`, `estado`) "
                . "SELECT $id_modulos, " . $value['id_rol'] . " , " . $value['estado'] . "  WHERE NOT EXISTS "
                . "(SELECT 1  FROM `mvto_rol_modulos` WHERE `id_modulo` = $id_modulos AND `id_rol`= " . $value['id_rol'] . ")";
            $sql_update = "UPDATE `mvto_rol_modulos` SET `estado`='" . $value['estado'] . "' WHERE "
                . "(`id_modulo`= $id_modulos AND `id_rol`= " . $value['id_rol'] . " )";
            array_push($sql_querys, $sql_insercion, $sql_update);
        }

        $response = $this->conexion->insert_update_multiple_rows($sql_querys);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "Los roles se asociaron correctamente");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al vincular los roles, intente de nuevo ");
        }
    }

    // Lista los roles y los estados de los roles por cada modulo.
    function cargar_roles_por_modulo()
    {
        //Verificamos que un rol con el mismo nombre no exista
        $sql_veririficiacion = "SELECT  `id_rol`, `estado` FROM `mvto_rol_modulos` WHERE `id_modulo` = $this->id_modulos ";
        $resultado = $this->conexion->select($sql_veririficiacion);
        $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => $resultado);
    }

    // Modifica el estado del rol asociado

    function update_estado_rol_asociado_modulo($id_rol_modulos, $estado)
    {
        //Actualizamos el rol
        $sql_update = "UPDATE `mvto_rol_modulos` SET `estado`='$estado' WHERE `id_mvto_rol_modulo` ='$id_rol_modulos'";
        $response = $this->conexion->update($sql_update);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "Se modificaron los datos correctamente");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al modificar los datos, intente de nuevo");
        }
    }
}



// $prueba = new model_modelos();
// $prueba->set_id_modulos(3); // Establecer el ID del registro existente del "Mimodulo" que deseas actualizar
// $prueba->set_codigo("2336"); // Actualizar la propiedad "codigo" con el nuevo valor
// $prueba->set_nombre("SANTI"); // Actualizar la propiedad "nombre" con el nuevo valor
// $prueba->set_nombre_personalizado("ESPINOZA"); // Actualizar la propiedad "nombre_personalizado" con el nuevo valor
// $prueba->set_estado(1); // Actualizar la propiedad "estado" con el nuevo valor
// $prueba->actualizarMimodulo(); // Realizar la operación de actualización
// print_r($prueba->get_mensaje()); // Obtener el mensaje de respuesta
// print_r($prueba->get_all_roles_active());