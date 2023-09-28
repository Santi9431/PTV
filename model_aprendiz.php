<?php

// modelo para gestionar las conexiones

class model_aprendiz
{

    // variables de la clase 
    //coexion

    private $conexion = null;
    private $mensaje = "";

    // variables que mapean la tabla

    private $id_registro_usuarios  = -1;
    private $id_usuario  = -1;
    private $documento = "";
    private $nombre = "";
    private $email  = "";
    private $direccion = "";
    private $foto = "";
    private $celular = "";
    private $id_ficha  = -1;
    private $numeroFicha  = "";
    private $id_programa  = -1;
    private $clave = "";
    private $id_rol  = -1;
    private $estado = 0;
    



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

    public function set_id_registro_usuarios($miregistrousuarios)
    {
        $this->id_registro_usuarios = $miregistrousuarios;
    }
    public function get_id_registro_usuarios()
    {
        return $this->id_registro_usuarios;
    }
    public function set_id_usuario($miusuario)
    {
        $this->id_usuario = $miusuario;
    }
    public function get_id_usuario()
    {
        return $this->id_usuario;
    }

    public function set_documento($midocumento)
    {
        $this->documento  = $midocumento;
    }
    public function get_documento()
    {
        return $this->documento;
    }
    public function set_nombre($minombre)
    {
        $this->nombre = $minombre;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function set_email($miemail)
    {
        $this->email = $miemail;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function set_direccion($midireccion)
    {
        $this->direccion = $midireccion;
    }
    public function get_direccion()
    {
        return $this->direccion;
    }
    public function set_foto($mifoto)
    {
        $this->foto = $mifoto;
    }
    public function get_foto()
    {
        return $this->foto;
    }
    public function set_celular($micelular)
    {
        $this->celular= $micelular;
    }
    public function get_celular()
    {
        return $this->celular;
    }
    public function set_id_ficha($mificha)
    {
        $this->id_ficha = $mificha;
    }
    public function get_id_ficha()
    {
        return $this->id_ficha;
    }
    public function set_numeroFicha($minumeroFicha)
    {
        $this->numeroFicha = $minumeroFicha;
    }
    public function get_numeroFicha()
    {
        return $this->numeroFicha;
    }
    public function set_id_programa($miprograma)
    {
        $this->id_programa = $miprograma;
    }
    public function get_id_programa()
    {
        return $this->id_programa;
    }
    public function set_clave($miclave)
    {
        $this->clave = $miclave;
    }
    public function get_clave()
    {
        return $this->clave;
    }
    public function set_estado($estado)
    {
        $this->estado = $estado;
    }
    public function set_id_rol($miidrol)
    {
        $this->id_rol = $miidrol;
    }
    public function get_id_rol()
    {
        return $this->id_rol;
    }
    public function get_estado()
    {
        return $this->estado;
    }
    public function get_mensaje()
    {
        return $this->mensaje;
    }


    
    //para que me carge todas fichas ingresadas
    public function cargarComboFichas()
    {
        // Realiza consulta para obtener los programas disponibles
        $sql_verificacion = "SELECT id_ficha AS idFicha, numeroFicha AS numeroFicha , id_programa AS idPrograma FROM `ficha` WHERE (`estado` = '1')";
        $respuesta = $this->conexion->select($sql_verificacion);

        // Crea un arreglo con datos para el combo de programas
        $combo = array();
        foreach ($respuesta as $row) {
            $datosCombo = array("id" => $row['idFicha'], "value" => $row['numeroFicha'], "idP" => $row['idPrograma']);
            array_push($combo, $datosCombo);
        }

        // Establece un mensaje con los datos del combo
        $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => $combo);
    }

   //el boton guardar
    public function guardarUsuario()
    {
        $sql_seleccionar = ("SELECT COUNT(`id_usuarios`) as total FROM usuarios   WHERE email = '$this->email'");
        $response = $this->conexion->select($sql_seleccionar);
        if($response[0]['total'] > 0) {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Ya existe el usuario");
            return;
        }else{
            //insertamos
            $clave_hash = hash('sha256', $this->clave);
            $sql_insercion = "INSERT INTO `usuarios` (`documento`,`email`,`clave`,`estado`) 
            VALUES ('$this->documento','$this->email', '$clave_hash', '$this->estado')";
            $response = $this->conexion->insert($sql_insercion);
            if($response){

                //consultamos el id del usuario que se acabo de insertar
                $sql_verificacion = "SELECT id_usuarios FROM `usuarios` WHERE (`documento` = '$this->documento')";
                $respuesta = $this->conexion->select($sql_verificacion);
                $id_user = $respuesta[0]['id_usuarios'];
                if(intval($id_user) < 0) {
                    $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error");
                    return;
                }
                $sql_insercion = "INSERT INTO `registro_usuarios` (`id_usuario`,`nombre`,`direccion`,`celular`,`foto`,`id_ficha`,`numeroFicha`,`id_programa`) 
                VALUES ($id_user,'$this->nombre', '$this->direccion', '$this->celular', '$this->foto', '$this->id_ficha', '$this->numeroFicha', '$this->id_programa')";
                $response = $this->conexion->insert($sql_insercion);

                $sql_insercion = "INSERT INTO `mtvo_rol_usuario` (`id_rol`,`id_usuario`,`estado`) 
                VALUES ('$this->id_rol', $id_user, '$this->estado')";
                $response = $this->conexion->insert($sql_insercion);

                $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El usuario se creo correctamente");
            } else {
                $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al insertar, intente de nuevo ");
            }
        }
    }

    // el boton actualizar 
    public function actualizarUsuario()
{
    $sql_seleccionar = "SELECT COUNT(`id_usuarios`) as total FROM usuarios WHERE id_usuarios = $this->id_usuario";
    $response = $this->conexion->select($sql_seleccionar);
    
    if ($response[0]['total'] <=0) {
        $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "No existe un usuario con este correo ");
        return;
    } else {
        // Actualizamos el usuario
        $sql_actualizacion = "UPDATE `usuarios` SET `email` = '$this->email', `documento` = '$this->documento', `estado` = '$this->estado' WHERE `id_usuarios` = $this->id_usuario";
        $response = $this->conexion->update($sql_actualizacion);
        
        if ($response) {
            // Actualizamos los detalles del usuario
            $sql_actualizacion_detalles = "";
            if($this->foto ===""){
                $sql_actualizacion_detalles = "UPDATE `registro_usuarios` SET `nombre` = '$this->nombre', `direccion` = '$this->direccion', `celular` = '$this->celular', `id_ficha` = '$this->id_ficha', `numeroFicha` = '$this->numeroFicha', `id_programa` = '$this->id_programa' WHERE `id_usuario` = $this->id_usuario";
            }else{
                $sql_actualizacion_detalles = "UPDATE `registro_usuarios` SET `nombre` = '$this->nombre', `direccion` = '$this->direccion', `celular` = '$this->celular', `foto` = '$this->foto', `id_ficha` = '$this->id_ficha', `numeroFicha` = '$this->numeroFicha', `id_programa` = '$this->id_programa' WHERE `id_usuario` = $this->id_usuario";
            }

            $response = $this->conexion->update($sql_actualizacion_detalles);


            $this->mensaje = array("code" => 1, "title" => "Operación Correcta", "mensaje" => "El usuario se actualizó correctamente ");
        } else {
            $this->mensaje = array("code" => 0, "title" => "Operación Incorrecta", "mensaje" => "Se generó un error al actualizar, intente de nuevo ");
        }

        
        }
    }
    
}