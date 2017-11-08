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
        $this->load->model('Usuarios');
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
            $usuario_pass = $this->post('contraseña');
            $usuario = $this->Usuarios->insertar_usuario($usuario_nombre,$usuario_pass);
            if(usuario != 1){
                $data = array('flag' => TRUE, 'mensaje' => "usuario registrado");
                $this->response($data, REST_Controller::HTTP_CREATED);
            } else{
                $data = array('flag' => FALSE, 'mensaje' => "Erro en el proceso");
                $this->response($data, REST_Controller::HTTP_NO_FOUND);
            }
        }
    }
}
