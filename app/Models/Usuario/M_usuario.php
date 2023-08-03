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
				SELECT generate_series(1,(SELECT count(id_usuario) FROM ral_usuario ru2 )) as nro ,
				ru.id_usuario,
					(SELECT concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) 
						FROM ral_persona rp
						WHERE rp.id_persona=ru.id_persona),
					ru.usuario,
					(SELECT rc.detalle FROM ral_categoria rc WHERE rc.id_categoria=ru.nivel) 
				FROM ral_usuario ru
				WHERE ru.estado='activo'
				");
			return $respuesta;
		}
		public function listar_personas_pusuario(){
			$respuesta=$this->db->query("	
				SELECT ru.id_persona, CONCAT(ru.nom_persona,' ',ru.ap_pat_persona,' ',ru.ap_mat_persona) as nombre
				FROM ral_persona ru
				LEFT JOIN ral_usuario rp
				ON ru.id_persona = rp.id_persona
				WHERE rp.id_persona is NULL
				AND ru.estado = 'activo';
			");
			return $respuesta;
		}
		public function listar_nivel(){
			$respuesta=$this->db->query("
				SELECT id_categoria, detalle 
				FROM ral_categoria rc 
				WHERE nombre_categoria = 'nivel' 
				AND estado='activo'
				ORDER BY id_categoria;
			");
			return $respuesta;
		}
		public function agregar_usuario($usuario,$id,$psswd,$lvl){
			$respuesta=$this->db->query("
				INSERT INTO ral_usuario (id_persona,usuario,psswd,nivel,estado) 
				VALUES ($usuario,'$id','$psswd',$lvl,'activo');");
			return $respuesta;
		}
		public function mostrar_usuario($id){
			$respuesta=$this->db->query("
				SELECT ru.id_usuario,
					ru.id_persona ,
					(select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) 
						from ral_persona rp
						where rp.id_persona=ru.id_persona),
					ru.usuario,
					ru.nivel,
					(select rc.detalle from ral_categoria rc where rc.id_categoria=ru.nivel) 
				FROM ral_usuario ru
				WHERE ru.estado='activo'
				AND ru.id_usuario = $id
				");
			return $respuesta->getResult();
		}
		public function modificar_usuario($id,$persona,$usuario,$psswd,$lvl){
			$respuesta=$this->db->query("
				UPDATE ral_usuario SET id_persona=$persona, usuario='$usuario' , psswd= '$psswd',nivel=$lvl
				WHERE id_usuario = $id
				AND estado='activo';");
			return $respuesta;
		}
		public function eliminar_usuario($id){
			$respuesta=$this->db->query("UPDATE ral_usuario SET estado='inactivo' WHERE id_usuario=$id");
			return $respuesta;
		}
	}
?>