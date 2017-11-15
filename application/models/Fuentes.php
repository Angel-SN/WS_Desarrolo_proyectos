<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fuentes extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function insertar_fuente($nombre_fuente='',$tipo_recurso='', $enlace='',$categoria='')
	{
		$datos = array('nombre_Fuente' => $nombre_fuente , 'tipo_Recurso' => $tipo_recurso , 'enlace' => $enlace ,'categoria' =>$categoria);
		$query = $this->db->insert('Fuentes', $datos);
		if (query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function buscar_fuente_por_tipo_recurso($tipo_recurso)
	{
		$this->db->where('tipo_Recurso', $tipo_recurso);
		$fuentes = $this->db->get('Fuentes')->row();
		return $fuentes;
	}


}

/* End of file Fuentes.php */
/* Location: ./application/models/Fuentes.php */
