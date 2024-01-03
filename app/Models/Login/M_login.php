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
				select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as usuario,
				array_agg(concat(codigo_modulo,'-',codigo_submodulo)) as codigo_pagina,
				ac.cargo as nivel,
				ru.usuario as id_usuario
				from adm_paginas ap, adm_cargo ac, ral_usuario ru, ral_persona rp
				where ru.nivel=ac.id_cargo
				and ru.id_persona=rp.id_persona
				and rp.estado='activo'
				and ru.estado='activo'
				and ap.estado='activo'
				and ac.estado='activo'
				and ru.usuario='$usuario'
				and ap.id_paginas=any(permisos)
				group by ac.cargo, ru.usuario, rp.nom_persona, rp.ap_pat_persona, rp.ap_mat_persona
			");
			return $respuesta->getResult();
		}
	}
?>



