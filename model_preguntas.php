<?php

// Definición de la clase "model_preguntas" para crear y manejar banco de preguntas
class model_preguntas
{
    // Atributos para la conexión a la base de datos y mensajes informativos
    private $conexion = null;
    private $mensaje = "";

    // Atributos para la información de tbl_preguntas
    private $id_pregunta  = 0;
    private $texto_pregunta  = null;
    private $tiene_imagen_pregunta = null;
    private $foto = null;
    private $estado_pregunta = 0;

    // Atributos para la información de tbl_respuestas
    private $id_respuesta  = -1;
    private $texto_respuesta = null;
    private $peso = null;



    // Constructor de la clase
    function __construct()
    {
        // Incluye el archivo de configuración de la conexión a la base de datos
        include_once '../../model/MDB/configCon.php';

        // Si la conexión aún no está establecida, la inicializa
        if (!isset($this->conexion)) {
            $this->conexion = $mysql_adapter_ptv;
        }
    }

    // Métodos setter y getter para atributos (proporcionados por ti)

    // ... (Aquí seguirían los métodos setter y getter para cada atributo)


    // Setters y Getters (proporcionados por ti)

    public function set_id_pregunta($id_pregunta)
    {
        $this->id_pregunta = $id_pregunta;
    }

    public function get_id_pregunta()
    {
        return $this->id_pregunta;
    }

    public function set_texto_pregunta($texto_pregunta)
    {
        $this->texto_pregunta = $texto_pregunta;
    }

    public function get_texto_pregunta()
    {
        return $this->texto_pregunta;
    }
    public function set_tiene_imagen_pregunta($tiene_imagen_pregunta)
    {
        $this->tiene_imagen_pregunta = $tiene_imagen_pregunta;
    }

    public function get_tiene_imagen_pregunta()
    {
        return $this->tiene_imagen_pregunta;
    }

    public function set_foto($foto)
    {
        $this->foto = $foto;
    }
    public function get_foto()
    {
        return $this->foto;
    }

    public function set_id_respuesta($id_respuesta)
    {
        $this->id_respuesta = $id_respuesta;
    }

    public function get_id_respuesta()
    {
        return $this->id_respuesta;
    }

    public function set_estado_pregunta($estado_pregunta)
    {
        $this->estado_pregunta = $estado_pregunta;
    }

    public function get_estado_pregunta()
    {
        return $this->estado_pregunta;
    }

    public function set_texto_respuesta($texto_respuesta)
    {
        $this->texto_respuesta = $texto_respuesta;
    }

    public function get_texto_respuesta()
    {
        return $this->texto_respuesta;
    }

    public function set_peso($peso)
    {
        $this->peso = $peso;
    }

    public function get_peso()
    {
        return $this->peso;
    }
    public function get_mensaje()
    {
        return $this->mensaje;
    }
    public function registrarPre($texto_pregunta, $tiene_imagen_pregunta, $estado_pregunta, $texto_respuesta)
    {
        // Comprueba si los campos requeridos están vacíos en la pregunta y la respuesta
        if (empty($texto_pregunta) || empty($texto_respuesta)) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Los campos deben estar completos");
            return;
        }
        //Asigna valores a atributos para los datos de la tbl_pregunta
        $this->set_texto_pregunta($texto_pregunta);
        $this->set_tiene_imagen_pregunta($tiene_imagen_pregunta);
        $this->set_estado_pregunta($estado_pregunta);
        //Con texto_respuesta donde está el array de respuesta, utilizamos trim para poder eliminar los últimos "##"
        $texto_respuesta = trim($texto_respuesta, '##');
        //Utilizo la función explode para separar el array por cada "##"
        $respuestas = explode("##", $texto_respuesta);
        //Almacenar las respuestas en un array
        $respuestasArray = array();
        //Recorremos las respuestas
        foreach ($respuestas as $respuesta) {
            //Aplico el explode para separar los valores con "@@"
            $elementos = explode("@@", $respuesta);
            //miro si hay al menos tres elementos en el array resultante
            if (count($elementos) == 2) {
                $texto_respuesta = $elementos[0];
                $peso = $elementos[1];
                //Almaceno cada respuesta como un array en conjunto , uno para todos 
                $respuestaData = array(
                    "texto_respuesta" => $texto_respuesta,
                    "peso" => $peso
                );
                //Agrega la respuesta al array de respuestas
                $respuestasArray[] = $respuestaData;
            }
        }
        // Realiza inserción en la tabla "tbl_pregunta" para la nueva pregunta
        $sql_insercion = "INSERT INTO tbl_pregunta(texto_pregunta, tiene_imagen, foto, estado) VALUES ('$this->texto_pregunta', $this->tiene_imagen_pregunta, '$this->foto', $this->estado_pregunta)";
        $response = $this->conexion->insert($sql_insercion);
        //Saco el ID de la pregunta basado en el texto de la pregunta usando LIKE
        $sql_seleccionar = "SELECT id_pregunta FROM tbl_pregunta WHERE texto_pregunta LIKE '%$this->texto_pregunta%'";
        $response_id = $this->conexion->select($sql_seleccionar);
        //Miro si se encontró un ID de pregunta
        if ($response_id && isset($response_id[0]['id_pregunta'])) {
            $idPregunta = $response_id[0]['id_pregunta'];
            // Existe la pregunta, procedemos con la inserción en tbl_respuesta para cada respuesta
            foreach ($respuestasArray as $respuestaData) {
                $texto_respuesta = $respuestaData["texto_respuesta"];
                $peso = $respuestaData["peso"];
                // Inserción en tbl_respuesta
                $sql_insercion2 = "INSERT INTO tbl_respuesta(id_pregunta, texto_respuesta, peso) VALUES ('$idPregunta', '$texto_respuesta', $peso)";
                $response2 = $this->conexion->insert($sql_insercion2);
                // Verifica si hubo algún error en la inserción
                if (!$response || !$response2) {
                    // Establece un mensaje de error si la inserción falló
                    $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al guardar la pregunta o respuesta, intente de nuevo.");
                    return;
                }
            }
        } else {
            // No se encontró la pregunta en la base de datos
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "La pregunta no se encontró en la base de datos.");
            return;
        }
        // Si todo se realizó con éxito, establece un mensaje de éxito
        $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "La pregunta y sus respuestas se guardaron correctamente.");
    }
    // el boton actualizar 
    public function actualizarPre($id_pregunta, $texto_pregunta, $tiene_imagen_pregunta, $estado_pregunta, $texto_respuesta)
    {
        $this->set_id_pregunta($id_pregunta);
        $this->set_texto_pregunta($texto_pregunta);
        $this->set_tiene_imagen_pregunta($tiene_imagen_pregunta);
        $this->set_estado_pregunta($estado_pregunta);
        // Verifica si el ID de la pregunta existe en la base de datos
        $sql_seleccionar = "SELECT id_pregunta FROM tbl_pregunta WHERE id_pregunta = $this->id_pregunta";
        $response = $this->conexion->select($sql_seleccionar);
        if ($response[0]['id_pregunta'] <= 0) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "La pregunta no existe en la base de datos." . $sql_seleccionar);
            return;
        } else {
            // Si el ID de la pregunta existe, procede con la actualización
            // Construye la parte de la consulta SQL que actualiza la imagen solo si se proporciona una nueva imagen
            $sql_actualizacion_foto = "";
            if ($this->foto !== "") {
                $sql_actualizacion_foto = ", `foto` = '$this->foto'";
            }
            //Con texto_respuesta donde está el array de respuesta, utilizamos trim para poder eliminar los últimos "##"
            $texto_respuesta = trim($texto_respuesta, '##');
            //Utilizo la función explode para separar el array por cada "##"
            $respuestas = explode("##", $texto_respuesta);
            //Almacenar las respuestas en un array
            $respuestasArray = array();
            //Recorremos las respuestas
            foreach ($respuestas as $respuesta) {
                //Aplico el explode para separar los valores con "@@"
                $elementos = explode("@@", $respuesta);
                //miro si hay al menos tres elementos en el array resultante
                if (count($elementos) == 3) {
                    $id_respuesta = $elementos[0];
                    $texto_respuesta = $elementos[1];
                    $peso = $elementos[2];
                    //Almaceno cada respuesta como un array en conjunto , uno para todos 
                    $respuestaData = array(
                        "id_respuesta" => $id_respuesta,
                        "texto_respuesta" => $texto_respuesta,
                        "peso" => $peso
                    );
                    //Agrega la respuesta al array de respuestas
                    $respuestasArray[] = $respuestaData;
                }
            }

            if ($response && isset($response[0]['id_pregunta'])) {
                $idPregunta = $response[0]['id_pregunta'];
                // Existe la pregunta, procedemos con la inserción en tbl_respuesta para cada respuesta
                foreach ($respuestasArray as $respuestaData) {
                    $id_respuesta = $respuestaData["id_respuesta"];
                    $texto_respuesta = $respuestaData["texto_respuesta"];
                    $peso = $respuestaData["peso"];
                    if ($id_respuesta > 0) {
                        // Actualiza la pregunta en la base de datos
                        $sql_actualizacionPre = "UPDATE `tbl_pregunta` SET `texto_pregunta` = '$this->texto_pregunta', `tiene_imagen` = '$this->tiene_imagen_pregunta', `estado` = '$this->estado_pregunta' $sql_actualizacion_foto WHERE `id_pregunta` = $this->id_pregunta";
                        $responsePre = $this->conexion->update($sql_actualizacionPre);
                    } else {
                        //Actualiza la respuesta en la base de datos 
                        $sql_actualizacionRes = "INSERT INTO tbl_respuesta(id_pregunta, texto_respuesta, peso) VALUES ('$idPregunta', '$texto_respuesta', $peso)";
                        $responseRes = $this->conexion->update($sql_actualizacionRes);
                    }
                }
            }
            if ($responsePre || $responseRes) {
                $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "La pregunta y respuesta se actualizó correctamente.");
            } else {
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al actualizar la pregunta y la respuesta, inténtalo de nuevo.");
            }
        }
    }
    public function obtenerRespuestas($id_pregunta)
    {
        $Respuesta_texto = "";
        //Asigna valores a atributos para los datos de la tbl_pregunta
        $this->set_id_pregunta($id_pregunta);
        //Esta zona es para mediante el id_pregunta obtenog toda la informacion que existe en la tbl_respuesta
        $sql_buscar = "SELECT * FROM tbl_respuesta WHERE id_pregunta = $this->id_pregunta";
        $responseID = $this->conexion->select($sql_buscar);
        if (sizeof($responseID) > 0) {
            foreach ($responseID as $row) {
                $id_respuesta = $row['id_respuesta'];
                $texto_respuesta = $row['texto_respuesta'];
                $peso = $row['peso'];
                $Respuesta_texto .= $id_respuesta . "@@" . $texto_respuesta . "@@" . $peso . "##";
            }
            $Respuesta_texto = trim($Respuesta_texto, '##');
            $this->mensaje = array("code" => 1, "title" => "Operación correcta", "mensaje" => $Respuesta_texto);
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Erronea", "mensaje" => "No hay nada que mostrar");
        }
    }
}
