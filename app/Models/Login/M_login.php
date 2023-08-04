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
				SELECT concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as usuario,
				(SELECT rc.detalle FROM ral_categoria rc WHERE ru.nivel=rc.id_categoria)as nivel
				FROM ral_usuario ru, ral_persona rp 
				WHERE ru.estado='activo' 
				AND rp.estado ='activo'
				AND ru.usuario = 'admin'
				AND ru.id_persona = rp.id_persona 
			");
			return $respuesta;
		}
	}
?>