<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	namespace App\Models\Login;
	use CodeIgniter\Model;

	class M_dasboard extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function funcion_inicio(){
			$respuesta=$this->db->query("select * from public.fn_inicio_sesion();");
			return $respuesta->getResult();
		}
	}
?>