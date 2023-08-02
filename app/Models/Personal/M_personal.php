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
				SELECT DISTINCT ON(ap.id_personal) GENERATE_SERIES(1,(SELECT count(id_personal) FROM adm_personal))AS nro, 
					   ap.id_personal, 
					   CONCAT(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)AS nombre, 
					   rp.celular,
					   (SELECT c.detalle FROM ral_categoria c WHERE c.id_categoria=ap.puesto)AS cargo 
				FROM adm_personal ap, ral_persona rp
				WHERE ap.id_persona = rp.id_persona
				AND ap.estado='activo'
				AND rp.estado='activo';
				");
			return $respuesta;
		}
		public function listar_cargos(){
			$respuesta=$this->db->query("SELECT id_categoria,detalle FROM ral_categoria rc where nombre_categoria  ilike 'cargo' AND estado = 'activo' ");
			return $respuesta;

		}
		public function registrar_personal($nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo){
			$respuesta=$this->db->query("
				INSERT INTO ral_persona (nom_persona,ap_pat_persona,ap_mat_persona,fec_nacimiento,celular,estado) VALUES ('$nombre','$apellidoP','$apellidoM','$fecnac',$celular,'activo'); 
				INSERT INTO adm_personal (id_persona,puesto,estado) VALUES((select id_persona from ral_persona order by id_persona desc limit 1),$cargo,'activo');");
			return $respuesta;
		}
		public function mostrar_personal($id){
			$respuesta=$this->db->query("
			SELECT ap.id_personal, rp.nom_persona,rp.ap_pat_persona ,rp.ap_mat_persona, rp.fec_nacimiento, rp.celular, ap.puesto, (SELECT detalle FROM ral_categoria WHERE id_categoria=ap.puesto) 
			FROM ral_persona rp, adm_personal ap WHERE ap.id_persona = rp.id_persona AND $id=ap.id_personal
			");
			return $respuesta->getResult();
		}
		public function modificar_personal($id, $nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo){
			$respuesta=$this->db->query("UPDATE ral_persona SET nom_persona='$nombre',ap_pat_persona='$apellidoP', ap_mat_persona='$apellidoM',fec_nacimiento='$fecnac',celular=$celular WHERE id_persona=(SELECT id_persona FROM adm_personal WHERE id_personal = $id);
				UPDATE adm_personal SET puesto=$cargo WHERE id_personal=$id;");
			return $respuesta;
		}
		public function eliminar_personal($id){
			$respuesta=$this->db->query("UPDATE ral_persona SET estado='inactivo' WHERE id_persona=(SELECT id_persona FROM adm_personal WHERE id_personal = $id);
				UPDATE adm_personal SET estado='inactivo' WHERE id_personal=$id;");
			return $respuesta;
		}
	}
?>