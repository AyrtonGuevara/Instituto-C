<?php
/*
	Ayrton Jhonny Guevara Montaño 03-08-2023
*/
	namespace App\Models\Login;
	use CodeIgniter\Model;

	class M_login extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function verificar_usuario($usuario){
			$respuesta=$this->db->query("SELECT salt, psswd FROM ral_usuario WHERE usuario='$usuario'");
			return $respuesta;
		}
		public function autenticar($usuario, $psswd){
			echo "SELECT count(id_usuario), usuario, psswd FROM ral_usuario ru WHERE usuario='$usuario' AND estado = 'activo' group by id_usuario ";
			$respuesta=$this->db->query("SELECT count(id_usuario), usuario, psswd FROM ral_usuario ru WHERE usuario='$usuario' AND estado = 'activo' group by id_usuario ");
			return $respuesta;
		}
	}
?>