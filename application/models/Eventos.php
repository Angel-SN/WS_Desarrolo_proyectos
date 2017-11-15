<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function insertar_evento($nombre, $tipo, $fecha, $Descripcion, $idMateria)
	{
		$nuevo = array(
			'nombre' => $nombre,
			'tipo' => $tipo,
			'fecha' => $fecha,
			'Descripcion' => $Descripcion,
			'Materia_idMateria' => $idMateria
		);
		$query = $this->db->insert('Eventos', $nuevo);
		if ($query){
			return $this->db->insert_id();
		} else{
			return 0;
		}
	}

	public function actualizar_evento_nombre($nombre,$nombre_usuario)
	{
		$this->db->set('nombre', $nombre);
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_usuario);
		$query = $this->db->update();
		$respuesta = ($query) ? true : false ;
		return respuesta;
	}
	public function actualizar_evento_tipo($tipo,$nombre_usuario)
	{
		$this->db->set('tipo', $tipo);
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_usuario);
		$query = $this->db->update();
		$respuesta = ($query) ? true : false ;
		return respuesta;
	}

	public function actualizar_evento_fecha($fecha='',$nombre_usuario)
	{
		$this->db->set('fecha', $fecha);
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_usuario);
		$query = $this->db->update();
		$respuesta = ($query) ? true : false ;
		return respuesta;
	}

	public function actualizar_evento_descripcion($descripcion,$nombre_usuario)
	{
		$this->db->set('Descripcion', $descripcion);
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('idusuario', $nombre_usuario);
		$query = $this->db->update();
		$respuesta = ($query) ? true : false ;
		return respuesta;
	}

	public function borrar_evento($nombre,$nombre_usuario)
	{
		$evento = $this->buscar_evento_por_usuario($nombre,$nombre_usuario);
		$this->db->where('idEventos', $evento -> idEvento);
		$query = $this->db->delete('Eventos') ;
		if($query){
			return true;
		}
		else{
			return false;
		}
	}

	public function buscar_eventos($nombre_usuario)
	{
		$this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('usuario.idusuario', $nombre_usuario);
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function buscar_evento_por_nombre($nombre, $nombre_usuario)
	{
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('usuario.idusuario', $nombre_usuario);
		$this->db->where('evento.nombre', $nombre);
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function buscar_evento_por_materia($materia, $nombre_usuario)
	{
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('usuario.idusuario', $nombre_usuario);
		$this->db->where('evento.nombre', $materia);
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function buscar_evento_por_fecha($fecha, $nombre_usuario)
	{
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('usuario.idusuario', $nombre_usuario);
		$this->db->where('evento.fecha', $fecha);
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function buscar_evento_por_tipo($tipo, $nombre_usuario)
	{
        $this->db->from('Eventos');
		$this->db->join('materia', 'Eventos.materia_idMateria = materia.idMateria', 'inner');
		$this->db->join('periodo_escolar', 'materia.periodo_escolar_idperiodo_escolar_id = Periodo_escolar.idPeriodo_escolar', 'inner');
		$this->db->join('usuario', 'usuario.idusuario = Periodo_escolar.usuario_idusuario', 'inner');
		$this->db->where('usuario.idusuario', $nombre_usuario);
		$this->db->where('evento.nombre', $tipo);
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		} else {
			return null;
		}
	}

}

/* End of file Eventos.php */
/* Location: ./application/models/Eventos.php */
