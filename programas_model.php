<?php

/*
 * Gestion de los roles de acceso al sistema.
 */

class model_Programas
{

    //Conexion con la base de datos
    private $db = null;
    //campos de la tabla    
    private $id_Programa = null;
    private $codigo = null;
    private $programa = null;
    private $version = null;
    private $url_programa = null;
    private $estado = null;

    //variable para la gestion de mensajes con el contralador
    private $mensaje = "";

    //En el constructor se configura la conexión a la base de datos, pero no
    //se realizar la conexión
    function __construct()
    {
        include_once '../../model/MDB/configCon.php';
        if (!isset($this->db)) {
            $this->db = $mysql_adapter_ptv;
        }
    }

    function getIdprograma()
    {
        return $this->id_Programa;
    }

    function getCodigo()
    {
        return $this->codigo;
    }

    function getPrograma()
    {
        return $this->programa;
    }

    function getVersion()
    {
        return $this->version;
    }

    function getEstado()
    {
        return $this->estado;
    }

    function getMensaje()
    {
        return $this->mensaje;
    }

    function getUrl_programa()
    {
        return $this->url_programa;
    }

    function setIdprograma($id_Programa)
    {
        $this->id_Programa = $id_Programa;
    }

    function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    function setPrograma($programa)
    {
        $this->programa = $programa;
    }

    function setVersion($version)
    {
        $this->version = $version;
    }

    function setEstado($estado)
    {
        $this->estado = $estado;
    }

    function setUrl_programa($url_programa)
    {
        $this->url_programa = $url_programa;
    }

    public function guardarPrograma($codigo, $programa, $version, $estado, $url_programa)
    {
        // Verificar si el nombre y la descripción son válidos
        if (
            empty($codigo) || empty($programa) || empty($version)
            || empty($estado) || empty($url_programa)
        ) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Campos obligatorios no están llenos");
            return;
        }
        // Configurar los valores para nombre y descripción
        $this->setCodigo($codigo);
        $this->setPrograma($programa);
        $this->setVersion($version);
        $this->setEstado($estado);
        $this->setUrl_programa($url_programa);

        if (!$this->insertar_archivo()) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar el programa, intente de nuevo.");
        } else {
            // Realizar la operación de guardar la nueva migración
            $sql_insercion = "INSERT INTO `tbl_programas`(`codigo`, `programa`, `version`, `estado`, `url_programa`) 
        VALUES ('$this->codigo','$this->programa', $this->version, '$this->estado', '$this->url_programa')";

            $response = $this->db->insert($sql_insercion);
            if ($response) {
                $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El programa se guardó correctamente.");
            } else {
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar el programa, intente de nuevo.");
            }
        }
    }

    private function insertar_archivo()
    {
        ini_set('memory_limit', '96M');
        ini_set('post_max_size', '20M');
        ini_set('upload_max_filesize', '20M');
        return $this->load($this->getUrl_programa());
    }

    private function load($file)
    {
        $resp = false;
        $fileName = $file['name'][0];
        $extension = explode('.', $file['name'][0]);
        $carpeta = "../../pdfProgramas/";
        $num = count($extension) - 1;
        if ((($extension[$num] == 'pdf') || ($extension[$num] == 'PDF'))) {
            $output_dir = "$carpeta" . "$fileName";
            $this->url_programa = $output_dir;
            if (file_exists($output_dir)) {
                unlink($output_dir);
            }
            try {
                copy($file["tmp_name"][0], $output_dir);
                $resp = true;
            } catch (Exception $e) {
                var_dump($e);
            }
        }
        return $resp;
    }

    public function actualizarProgramas($url_antigua)
    {
        // Verificar si el ID del programa es válido
        if ($this->id_Programa === -1) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "ID de programa no válido.");
            return;
        }

        // Resto del código de validación...

        // Actualizar la URL del programa solo si se proporciona una nueva URL
        if (!empty($this->url_programa)) {
            if (!$this->eliminar_archivo($url_antigua)) {
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Error al eliminar el archivo antiguo, intente de nuevo.");
                return;
            }

            if (!$this->insertar_archivo()) {
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar el programa, intente de nuevo.");
                return;
            }
        }else{
            $this->url_programa =  $url_antigua;
        }

        // Realizar la operación de actualización
        $sql_actualizacion = "UPDATE `tbl_programas` SET `codigo` = '$this->codigo', `programa`= '$this->programa',  `version` = '$this->version',  `estado`='$this->estado', `url_programa` = '$this->url_programa'";
        $sql_actualizacion .= " WHERE id_Programa = $this->id_Programa";
        $response = $this->db->update($sql_actualizacion);
        if ($response) {
            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El programa se actualizó correctamente. ");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Error al actualizar, intente de nuevo.");
        }
    }

    public function eliminar_archivo($url_antigua)
    {
        if (empty($url_antigua)) {
            return false; // No se proporcionó ninguna URL válida
        }

        if (file_exists($url_antigua)) {
            if (unlink($url_antigua)) {
                return true; // El archivo se eliminó correctamente
            } else {
                return false; // No se pudo eliminar el archivo
            }
        } else {
            return true; // El archivo no existe, no hay necesidad de eliminarlo
        }
    }
}
