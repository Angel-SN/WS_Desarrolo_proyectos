<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periodo extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function insertar_periodo($fecha_inicio,$fecha_fin, $idusuario){
		$datos = array('fecha_inicio' => $fecha_inicio , 'fecha_fin' => $fecha_fin, 'Usuario_idusuario' => $idusuarios);
		$query = $this->db->insert('Periodo_escolar', $datos);
		if (query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function insertar_subperiodo($inicio_evaluaciones,$fin_evaluaciones, $idPeriodo_escolar){
		$datos = array('inicio_evaluaciones' => $inicio_evaluaciones , 'fin_evaluaciones' => $fin_evaluaciones, 'Periodo_escolar_idPeriodo_escolar' => $idPeriodo_escolar);
		$query = $this->db->insert('Sub_periodos', $datos);
		if (query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}
	public function buscar_periodo($nombre_usuario)
	{
		$this->db->select('usuario.nombre','Periodo_escolar.fecha_inicio','Periodo_escolar.fecha_fin','Sub_periodos.inicio_evaluaciones','Sub_periodos.fin_evaluaciones');
		$this->db->from('Usuario');
		$this->db->join('Periodo_escolar', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->join('Sub_periodos', 'Periodo_escolar.idPeriodo_escolar = P
		Sub_periodos.Periodo_escolar_idPeriodo_escolar', 'inner');
		$this->db->where('usuario.nombre', $nombre_usuario);
		$query = $this->db->get();
		if ($query) {
			return query->row_array();
		} else {
			return 0;
		}


	}
}

/* End of file Periodo.php */
/* Location: ./application/models/Periodo.php */
