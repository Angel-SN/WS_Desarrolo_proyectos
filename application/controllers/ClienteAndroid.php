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

    public function usuario_contraseña_put()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_rules('Contraseña', 'contraseña', 'trim|required|min_length[5]|max_length[12]');
        if ($this->form_validation->run() == FALSE) {
            $data = array('flag' => FALSE);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->post('NombreUsuario');
            $usuario_pass = $this->post('Contraseña');
            $usuario = $this->Usuarios->actualizar_contrasena($usuario_nombre, $usuario_pass);
            if($usuario != FALSE){
                $data = array('flag' => TRUE, 'mensaje' => "contraseña actualizada");
                $this->response($data, REST_Controller::HTTP_OK);
            } else{
                $data = array('flag' => FALSE, 'mensaje' => "Error en el proceso");
                $this->response($data, REST_Controller::HTTP_NOT_FOUND);
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
                $this->response($resp, REST_Controller::HTTP_OK);
            }else{
                $resp = array('flag' => true ,'mensaje' => "Datos no encontrados");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function materias_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('Nombre_materia', 'nombre de la materia', 'trim|required');
        $this->form_validation->set_rules('Idperiodo', 'identificador de periodo', 'trim|required');
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

    public function materias_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El Usuario no Existe');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->get('NombreUsuario');
            $usuario = $this->Usuarios->buscar_usuario($usuario_nombre);
            $materias = $this->Materias->buscar_materia($usuario->idusuario);
            if ($materias != null) {
                $this->response($materias, REST_Controller::HTTP_OK);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function calificacion_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('Valor', 'valor de la calificacion', 'trim|required');
        $this->form_validation->set_rules('Porcentaje', 'Porcentaje total', 'trim|required');
        $this->form_validation->set_rules('Idmateria', 'Id de la materia', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
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

    public function calificaciones_get()
    {
        $this->form_validation->set_data($this->get());
        $this->form_validation->set_rules('NombreUsuario', 'Nombre de Usuario', 'trim|required|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El Usuario no Existe');
        $this->$this->form_validation->set_rules('NombreMateria', 'Nombre de la materia', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $usuario_nombre = $this->get('NombreUsuario');
            $usuario = $this->Usuarios->buscar_usuario($usuario_nombre);
            $materia_nombre = $this->post('NombreMateria');
            $calificaciones = $this->Materias->buscar_calificaciones($usuario->idusuario, $materia_nombre);
            if ($calificaciones != null) {
                $this->response($calificaciones, REST_Controller::HTTP_OK);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function eventos_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('tipo', 'tipo', 'trim|required');
        $this->form_validation->set_rules('fecha', 'fecha', 'trim|required');
        $this->form_validation->set_rules('Descripcion', 'Descripcion', 'trim|required');
        $this->form_validation->set_rules('idMateria', 'idMateria', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $resp = array('flag' => FALSE);
            $resp = array_merge($resp, $this->form_validation->error_array());
            $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $nombre = $this->post('nombre');
            $tipo = $this->post('tipo');
            $fecha = $this->post('fecha');
            $Descripcion = $this->post('Descripcion');
            $idMateria = $this->post('idMateria');
            $evento_id =  $this->Eventos->insertar_evento($nombre, $tipo, $fecha, $Descripcion, $idMateria);
            if ($evento_id != 0) {
                $resp = array('flag' => true ,'mensaje' => "Calificacion insertada", 'id' => $evento_id);
                $this->response($resp, REST_Controller::HTTP_CREATED);
            } else {
                $resp = array('flag' => true ,'mensaje' => "Error en el proceso");
                $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function eventos_get()
    {
        $nombre_usuario =$this->get('NombreUsuario');
        if ($nombre_usuario != null) {

            if(!usuarioExistente($nombre_usuario)){
                $resp = array(
                    'flag' => FALSE,
                    'mensaje' => "El usuario no Existe"
                );
                $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
            }

            $evento_nombre = $this->get('NombreEvento');

            if ($evento_nombre != null) {
                $resp = $this->Eventos->buscar_evento_por_nombre($evento_nombre, $nombre_usuario);
                if ($resp != null) {
                    $this->response($resp, REST_Controller::HTTP_OK);
                } else {
                    $resp = array(
                        'flag' => FALSE,
                        'mensaje' => "no se encuentra el evento"
                    );
                    $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
                }
            }

            $evento_materia = $this->get('Materia');

            if ($evento_materia != null) {
                $resp = $this->Eventos->buscar_evento_por_materia($evento_materia, $nombre_usuario);
                if ($resp != null) {
                    $this->response($resp, REST_Controller::HTTP_OK);
                } else {
                    $resp = array(
                        'flag' => FALSE,
                        'mensaje' => "no se encuentran eventos de esa materia"
                    );
                    $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
                }
            }

            $evento_fecha = $this->get('Fecha');

            if ($evento_fecha != null) {
                $resp = $this->Eventos->buscar_evento_por_fecha($evento_fecha, $nombre_usuario);
                if ($resp != null) {
                    $this->response($resp, REST_Controller::HTTP_OK);
                } else {
                    $resp = array(
                        'flag' => FALSE,
                        'mensaje' => "no se encuentran eventos de esa fecha"
                    );
                    $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
                }
            }

            $evento_tipo = $this->get('Tipo');

            if ($evento_tipo != null) {
                $resp = $this->Eventos->buscar_evento_por_tipo($evento_tipo, $nombre_usuario);
                if ($resp != null) {
                    $this->response($resp, REST_Controller::HTTP_OK);
                } else {
                    $resp = array(
                        'flag' => FALSE,
                        'mensaje' => "no se encuentran eventos de ese tipo"
                    );
                    $this->response($resp, REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
        else{
            $resp = array(
                'flag' => FALSE,
                'mensaje' => "Falta numero de usuario"
            );
            $this->response($resp, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
