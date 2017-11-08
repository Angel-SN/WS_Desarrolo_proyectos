<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}

	public function insertar_usuario($nombre, $contrasenia )
	{
		$datos = array(
			'nombre' => $nombre,
			'contraseña' => $contrasenia
		);
		$query = $this->db->insert('Usuario', $datos);
		if($query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function actualizar_contrasena($nombre_usuario='',$contraseña)
	{
		$this->db->set('contraseña', $contraseña);
        $this->db->where('nombre', $nombre_usuario);
        $Query = $this->db->update('Usuario');
        return $Query;
	}

	public function buscar_usuario($nombre_usuario)
	{
		$this->db->where('nombre', $nombre_usuario);
		$usuario = $this->db->get('Usuario')->row();
	}
}

/* End of file Usuarios.php */
/* Location: ./application/models/Usuarios.php */
