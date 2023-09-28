<?php

/**
 * Modelo para gestionar las conexiones
 */

class model_migraciones
{

    /**
     * Variables de la clase
     */

    //Conexion
    private $conexion = null;
    //Manejo de mensajes entre el modelo y el contralador
    private $mensaje = "";

    //variables que mapean la tabla
    private $id_migraciones = -1;
    private $descripcion = "";
    private $nombre = "";
    private $is_load = 0;

    //campo para el script
    private $scriptSQL = "";

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

    public function set_id_migracion($migracionid)
    {
        $this->id_migraciones = $migracionid;
    }

    public function get_id_migraciones()
    {
        return $this->id_migraciones;
    }

    public function set_descipcion($descrip)
    {
        $this->descripcion = $descrip;
    }

    public function get_descripcion()
    {
        return $this->descripcion;
    }

    public function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function set_is_load($isload)
    {
        $this->is_load = $isload;
    }

    public function get_is_load()
    {
        return $this->is_load;
    }

    public function get_mensaje()
    {
        return $this->mensaje;
    }

    public function set_scriptSQL($scriptSQL)
    {
        $this->scriptSQL = $scriptSQL;
    }

    /**
     * Crear el script SQL fisico en el servidor
     */
    private function crear_script_sql()
    {
        //crear script sql para migración y guardarlo en el servidor
        // Get the current date and time in the default format (Y-m-d H:i:s)
        $currentDateTime = date('Y-m-d_H-i-s');
        $this->nombre = 'Migrations_' . $currentDateTime . '.sql'; // Replace 'example.txt' with your desired filename
        $content = $this->scriptSQL; // Replace this with the content you want to write to the file
        $ruta = "../../model/migrations/" . $this->nombre;
        // Create or overwrite the file with the specified content
        if (file_put_contents($ruta, $content) !== false) {
            return true;
        } else {
            return false;
        }
    }

    private function eleminar_sql()
    {
        $ruta = "../migrations/" . $this->nombre; // Replace 'example.txt' with the filename you want to delete
        // Check if the file exists before attempting to delete it
        if (file_exists($ruta)) {
            // Attempt to delete the file
            if (unlink($ruta)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    

    // Funcion para guardar las migraciones , pasa parametros 
    public function guardarNuevaMigracion($descripcion, $isload)
    {
        // Verificar si el nombre y la descripción son válidos
        if (empty($this->scriptSQL) || empty($descripcion)) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "La descripción y el script SQL son obligatorios.");
            return;
        }
        // Configurar los valores para nombre y descripción
        $this->set_descipcion($descripcion);
        $this->set_is_load($isload);
        // Verificar si se puede crear el script SQL
        if (!$this->crear_script_sql()) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al crear el script, intente de nuevo.");
            return;
        }
        // Realizar la operación de guardar la nueva migración
        $sql_insercion = "INSERT INTO `migraciones`(`descripcion`, `nombre_de_migracion`, `is_load`) 
                          VALUES ('$this->descripcion','$this->nombre', $this->is_load)";
        $response = $this->conexion->insert($sql_insercion);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "La nueva migración se guardó correctamente.");
        } else {
            $this->eleminar_sql();
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar la nueva migración, intente de nuevo.");
        }
    }
}







// prueba para guardar
// $prueba = new model_migraciones();
// $nombre = "segunda pruba ";
// $descripcion = "LA primera prueba no aparecio en pantalla , mirando si esta aparece";
// $prueba->guardarNuevaMigracion($nombre, $descripcion);

// $resultado_guardar = $prueba->get_mensaje();
// print_r($resultado_guardar);

