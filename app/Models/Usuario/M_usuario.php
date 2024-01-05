<?php
	/*
	Ayrton Jhonny Guevara Montaño 02-08-2023
	*/


	namespace App\Models\Usuario;
	use CodeIgniter\Model;
	class M_usuario extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_usuario(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, ru.id_usuario,concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as nombre,ru.usuario,ac.cargo
				from ral_usuario ru, ral_persona rp, adm_cargo ac
				where ru.id_persona=rp.id_persona
				and ru.nivel=ac.id_cargo
				and ac.estado='activo'
				and ru.estado='activo'
				and rp.estado='activo';
				");
			return $respuesta;
		}
		public function listar_personas_pusuario(){
			$respuesta=$this->db->query("	
				select ru.id_persona, concat(ru.nom_persona,' ',ru.ap_pat_persona,' ',ru.ap_mat_persona) as nombre
				from ral_persona ru
				left join ral_usuario rp
				on ru.id_persona = rp.id_persona
				where rp.id_persona is null
				and ru.estado = 'activo';
			");
			return $respuesta;
		}
		public function listar_nivel(){
			$respuesta=$this->db->query("
				select id_cargo,cargo 
				from adm_cargo 
				where estado='activo' 
				order by id_cargo; 
			");
			return $respuesta;
		}
		public function agregar_usuario($usuario,$id,$psswd,$salt,$lvl){
			$respuesta=$this->db->query("
				insert into ral_usuario (id_persona,usuario,psswd,salt,nivel,estado) 
				values ($usuario,'$id','$psswd','$salt',$lvl,'activo');
			");
			return $respuesta;
		}
		public function mostrar_usuario($id){
			$respuesta=$this->db->query("
				select ru.id_usuario,
				ru.id_persona ,
				concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona),
				ru.usuario,
				ru.nivel,
				ac.cargo
				from ral_usuario ru, ral_persona rp, adm_cargo ac
				where ru.id_persona = rp.id_persona
				and ru.nivel = ac.id_cargo
				and ru.estado='activo'
				and rp.estado ='activo'
				and ac.estado ='activo'
				and ru.id_usuario = $id
				");
			return $respuesta->getResult();
		}
		public function modificar_usuario($id,$persona,$usuario,$psswd,$salt,$lvl){
			$respuesta=$this->db->query("
				update ral_usuario 
				set id_persona=$persona, 
					usuario='$usuario' , 
					psswd= '$psswd',
					salt='$salt',
					nivel=$lvl
				where id_usuario = $id
				and estado='activo';
			");
			return $respuesta;
		}
		public function eliminar_usuario($id){
			$respuesta=$this->db->query("
				update ral_usuario 
				set estado='inactivo' 
				where id_usuario=$id;
			");
			return $respuesta;
		}
	}
?>