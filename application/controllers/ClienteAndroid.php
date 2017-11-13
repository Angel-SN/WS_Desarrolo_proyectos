<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class ClienteAndroid extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(array('Usuarios','Materias','Eventos','Periodo'));
    }

    // form_validation callback Function
    public function usuarioExistente($str)
    {
        $resultado = ($this->Usuarios->buscar_usuario($str) != null) ? true : false ;
        return $resultado;
    }

    //registro
    public function usuario_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|is_unique[Usuario.nombre]');
        $this->form_validation->set_rules('Contraseña', 'contraseña', 'trim|required|min_length[5]|max_length[12]');
        if ($this->form_validation->run() == FALSE) {
            $data = array('flag' => FALSE);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->post('NombreUsuario');
            $usuario_pass = $this->post('Contraseña');
            $usuario = $this->Usuarios->insertar_usuario($usuario_nombre,$usuario_pass);
            if($usuario != 0){
                $data = array('flag' => TRUE, 'mensaje' => "usuario registrado");
                $this->response($data, REST_Controller::HTTP_CREATED);
            } else{
                $data = array('flag' => FALSE, 'mensaje' => "Error en el proceso");
                $this->response($data, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function login_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_rules('Contraseña', 'contraseña', 'trim|required|min_length[5]|max_length[12]');
        if ($this->form_validation->run() == false) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->post('NombreUsuario');
            $usuario_pass = $this->post('Contraseña');
            $usuario = $this->Usuarios->buscar_usuario($usuario_nombre);
            if ($usuario->contraseña == $ussuario_pass) {
                $resp = array('flag' => true ,'mensaje' => "Inicio de sesion correcto");
                $this->response($resp, REST_Controller::HTTP_OK);
            } else {
                $resp = array('flag' => true ,'contraseña' => "Contraseña incorrecta");
                $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function periodo_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_rules('Fecha_inicio', 'Fecha de inicio', 'trim|required');
        $this->form_validation->set_rules('Fecha_fin', 'Fecha de terminacion', 'trim|required');
        if ($this->form_validation->run() == false) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->post('NombreUsuario');
            $fecha_inicio = $this->post('Fecha_inicio');
            $fecha_fin = $this->post('Fecha_fin');
            $usuario_id = $this->Usuarios->buscar_usuario($usuario_nombre)->idusuario;
            $periodo_id = $this->Periodo->insertar_periodo($fecha_inicio,$fecha_fin, $usuario_id);
            if ($periodo_id != 0) {
                $resp = array('flag' => true ,'mensaje' => "Periodo creado", 'id' => $periodo_id);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function subPeriodo_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('Inicio_evaluaciones', 'Inicio del Subperiodo', 'trim|required');
        $this->form_validation->set_rules('Fin_evaluaciones', 'Fin del Subperiodo', 'trim|required');
        $this->form_validation->set_rules('IdPeriodo', 'Identifidor del periodo', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $inicio_evaluaciones = $this->post('Inicio_evaluaciones');
            $fin_evaluaciones = $this->post('Fin_evaluaciones');
            $idPeriodo_escolar = $this->post('IdPeriodo');
            $subPeriodos_id = $this->Periodo->insertar_subperiodo($inicio_evaluaciones,$fin_evaluaciones, $idPeriodo_escolar);
            if ($subPeriodos_id != 0) {
                $resp = array('flag' => true ,'mensaje' => "Periodo creado", 'id' => $subPeriodos_id);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function periodos_subperiodos_get()
    {
        $this->form_validation->setdata($this->get());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El Usuario no Existe');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario = $this->get('usuario');
            $resp = $this->Periodo->buscar_periodo($usuario);
            if($resp != null){
                $msj = array('flag' => true ,'mensaje' => "datos encontados");
                $resp = array_merge($msj,$resp);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            }else{
                $resp = array('flag' => true ,'mensaje' => "Datos no encontrados");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function materias_post()
    {
        $this->form_validation->setdata($this->post());
        $this->form_validation->set_rules('Nombre_materia', 'fieldlabel', 'trim|required');
        $this->form_validation->set_rules('Idperiodo', 'fieldlabel', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $nombre_materia = $this->post('Nombre_materia');
            $idperiodo = (int) $this->post('Idperiodo');
            $materia_id = $this->Materias->insertar_materia($nombre_materia, $idperiodo);
            if ($materia_id != 0) {
                $resp = array('flag' => true ,'mensaje' => "Materia creada", 'id' => $materia_id);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }



    public function calificacion_post()
    {
        $this->form_validation->setdata($this->post());
        $this->form_validation->set_rules('Valor', 'Valor', 'trim|required');
        $this->form_validation->set_rules('Porcentaje', 'Porcentaje', 'trim|required');
        $this->form_validation->set_rules('Idmateria', 'Idmateria', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $valor = $this->post('Valor');
            $porcentaje = $this->post('Porcentaje');
            $idmateria = $this->post('Idmateria');
            $calificacion_id =  $this->Materias->insertar_calificacion($valor, $porcentaje, $idmateria);
            if ($calificacion_id != 0) {
                $resp = array('flag' => true ,'mensaje' => "Calificacion insertada", 'id' => $calificacion_id);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}
