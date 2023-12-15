<?php
/*
	Ayrton Jhonny Guevara Montaño 15-12-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_reinscripcion extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_reinscripcion(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, ae.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as estudiante, ae.unid_educativa
				from aca_estudiante ae, ral_persona rp
				where ae.id_persona=rp.id_persona
				and ae.estado='inactivo'
				and rp.estado='inactivo'; 
				"); 
			return $respuesta->getResult();
		}
	}
?>