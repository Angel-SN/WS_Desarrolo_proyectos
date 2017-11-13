<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materias extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function insertar_materia($nombre_materia, $idperiodo)
	{
		$datos = array('Nombre' => $nombre_materia, 'Periodo_escolar_idPeriodo_escolar' => $idperiodo);
		$query = $this->db->insert('materia', $datos);
		if ($query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function insertar_calificacion($valor,$porcentaje,$idmateria)
	{
		$datos = array('valor' => $valor, 'porcentaje' => $porcentaje, 'Materia_idMateria' => $idmateria);
		$this->db->insert('calificacion', $datos);
		if ($query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function buscar_materia($nombre_usuario='')
	{
		$this->db->from('materia');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('Periodo_escolar', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_materia);
		$query = $this->db->get();
		if ($query){
			return $query->last_row();
		} else{
			return 0;
		}
	}

	public function buscar_calificaciones($nombre_usuario='')
	{
		$this->db->from('calificaciones');
		$this->db->join('materia', 'calificaciones.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('Periodo_escolar', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_materia);
		$query = $this->db->get();
		if ($query){
			return $query->last_row();
		} else{
			return 0;
		}
	}
}

/* End of file Materias.php */
/* Location: ./application/models/Materias.php */
