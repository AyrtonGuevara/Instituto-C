<?php
/*
	Ayrton Guevara Montaño 30-07-2023
*/
	namespace App\Models\Personal;
	use CodeIgniter\Model;
	class M_personal extends Model{	

		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_personal(){
			$respuesta=$this->db->query("
				select distinct on(ap.id_personal) row_number() over() as nro, 
					ap.id_personal, 
					concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)as nombre, 
					rp.celular,
					rc.detalle as cargo
				from adm_personal ap, ral_persona rp, ral_categoria rc 
				where ap.id_persona = rp.id_persona
				and ap.puesto = rc.id_categoria
				and ap.estado='activo'
				and rc.estado='activo'
				and rp.estado='activo';
				");
			return $respuesta;
		}
		public function listar_cargos(){
			$respuesta=$this->db->query("
				select id_categoria,detalle 
				from ral_categoria rc 
				where nombre_categoria ilike 'cargo' 
				and estado = 'activo' ");
			return $respuesta;
		}
		public function registrar_personal($nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo){
			$respuesta=$this->db->query("
				insert 
				into ral_persona (nom_persona,ap_pat_persona,ap_mat_persona,fec_nacimiento,celular,estado) 
				values ('$nombre','$apellidoP','$apellidoM','$fecnac',$celular,'activo'); 

				insert 
				into adm_personal (id_persona,puesto,estado) 
				values((select id_persona 
					from ral_persona 
					order by id_persona 
					desc limit 1),$cargo,'activo');");
			return $respuesta;
		}
		public function mostrar_personal($id){
			$respuesta=$this->db->query("
				select ap.id_personal, 
					rp.nom_persona,
					rp.ap_pat_persona ,
					rp.ap_mat_persona, 
					rp.fec_nacimiento, 
					rp.celular, 
					ap.puesto, 
					rc.detalle
				from ral_persona rp, 
					adm_personal ap,
					ral_categoria rc
				where ap.id_persona = rp.id_persona 
				and rc.id_categoria=ap.puesto
				and ap.estado='activo'
				and rp.estado='activo'
				and rc.estado='activo'
				and $id=ap.id_personal;
			");
			return $respuesta->getResult();
		}
		public function modificar_personal($id, $nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo){
			$respuesta=$this->db->query("
				update ral_persona 
				set nom_persona='$nombre',
					ap_pat_persona='$apellidoP', 
					ap_mat_persona='$apellidoM',
					fec_nacimiento='$fecnac',
					celular=$celular 
				where id_persona=(select id_persona 
					from adm_personal where id_personal = $id);

				update adm_personal 
				set puesto=$cargo 
				where id_personal=$id;
			");
			return $respuesta;
		}
		public function eliminar_personal($id){
			$respuesta=$this->db->query("
				update ral_persona 
				set estado='inactivo' 
				where id_persona=(select id_persona from adm_personal where id_personal = $id);

				update adm_personal 
				set estado='inactivo' 
				where id_personal=$id;
			");
			return $respuesta;
		}
	}
?>