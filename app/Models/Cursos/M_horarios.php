<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Models\Cursos;
	use CodeIgniter\Model;

	class M_horarios extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_horarios(){
			$respuesta=$this->db->query("");
			return $respuesta;
		}
		public function lista_dia(){
			$respuesta=$this->db->query("SELECT id_categoria, detalle FROM ral_categoria WHERE tipo = 'dia' AND nombre_categoria='dia' AND estado='activo'");
			return $respuesta;
		}
	}
?>