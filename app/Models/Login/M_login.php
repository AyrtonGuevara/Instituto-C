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
		public function iniciar_sesion($usuario){
			$respuesta=$this->db->query("
				select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as usuario,ru.usuario as id_usuario,ac.cargo as nivel
				from ral_usuario ru, ral_persona rp, adm_cargo ac
				where ru.nivel = ac.id_cargo 
				and ru.id_persona = rp.id_persona
				and ru.usuario='$usuario'
				and ru.estado ='activo'
				and rp.estado ='activo'
				and ac.estado ='activo'
			");
			return $respuesta;
		}
	}
?>