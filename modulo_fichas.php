<?php

/**
 * Modelo para gestionar las conexiones
 */

class model_fichas
{

    /**
     * Variables de la clase
     */

    //Conexion
    private $conexion = null;
    //Manejo de mensajes entre el modelo y el contralador
    private $mensaje = "";

    //variables que mapean la tabla
    private $idFicha = -1;
    private $ficha = null;
    private $idPrograma = null;
    private $estado = 0;

    //En el constructor se configura la conexión a la base de datos, pero no
    //se realizar la conexión
    function __construct()
    {
        include_once '../../model/MDB/configCon.php';
        if (!isset($this->conexion)) {
            $this->conexion = $mysql_adapter_ptv;
        }
    }

    /**
     * Encapuslate Fields
     */

    public function set_idFicha($idFicha)
    {
        $this->idFicha = $idFicha;
    }

    public function get_idFicha()
    {
        return $this->idFicha;
    }

    public function set_ficha($ficha)
    {
        $this->ficha = $ficha;
    }

    public function get_ficha()
    {
        return $this->ficha;
    }

    public function set_idPrograma($idPrograma)
    {
        $this->idPrograma = $idPrograma;
    }

    public function get_idPrograma()
    {
        return $this->idPrograma;
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
    // Funcion para guardar las fichas con 3 parametros
    public function guardarFicha($fichap, $idPrograma, $estado)
    {        
        // Verificar si ficha programa y estado son validos
        if (empty($fichap) || empty($idPrograma) || empty($estado)) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Los campos deben estar completos");
            return;
        }
        // Configurar los valores para ficha , programa , estado de la ficha 
        $this->set_ficha($fichap);
        $this->set_idPrograma($idPrograma);
        $this->set_estado($estado);
        // Realizar la operación de guardar la nueva ficha 
        $sql_insercion = "INSERT INTO `ficha`(`numeroFicha`, `id_programa`, `estado`) VALUES ($this->ficha, $this->idPrograma, $this->estado)";
        $response = $this->conexion->insert($sql_insercion);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "La nueva migración se guardó correctamente.");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar la nueva migración, intente de nuevo. ");
        }
    }

    //Generamos los datos para el combo programas de formación en el formulario ficha
    public function cargarComboProgramas()
    {
        $sql_veririficiacion = "SELECT  id_Programa AS idPrograma, programa AS nombrePrograma "
            . "FROM `tbl_programas` WHERE (`estado` = '1')";
        $respuesta = $this->conexion->select($sql_veririficiacion);
        $combo = array();
        foreach ($respuesta as $row) {
            $datosCombo = array("id" => $row['idPrograma'], "value" => $row['nombrePrograma']);
            array_push($combo, $datosCombo);
        }
        $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => $combo);
    }
}
