<?php
/*
	Ayrton Jhonny Guevara Montaño 14-09-2023
*/
	namespace App\Models\Ambientes;
	use CodeIgniter\Model;

	class M_clases_lista extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_clases(){
			$respuesta=$this->db->query(
				"select row_number() over(order by direccion) as nro, ac.id_clase,au.direccion, aa.nombre_aula, aa.cantidad_estudiantes ,am.nombre_materia,ach.id_horarios,ap.nombre
				from aca_clase ac,
				aca_materia am,
				(select ach.id_conf_horarios, ach.codigo_conf , (string_agg(concat(rc.detalle ,': ',replace(ah.horarios,'||','-')), ' || '))as id_horarios 
					from adm_conf_horarios ach,
					aca_horarios ah,
					ral_categoria rc 
					where ah.id_conf_horarios=ach.id_conf_horarios
					and rc.id_categoria = ah.dias 
					and rc.estado = 'activo'
					and ach.estado = 'activo'
					and ah.estado ='activo'
					group by ach.id_conf_horarios, ach.codigo_conf) as ach,
				aca_aula aa,
				(select ap.id_personal, concat(rp.nom_persona,' ',rp.ap_pat_persona ,' ',rp.ap_mat_persona) as nombre
					from adm_personal ap, ral_persona rp 
					where ap.id_persona = rp.id_persona
					and ap.estado = 'activo'
					and rp.estado = 'activo')as ap,
				adm_ubicacion au 
				where ac.id_materia = am.id_materia 
				and ac.id_horarios = ach.id_conf_horarios 
				and ac.id_aula = aa.id_aula 
				and ac.id_personal = ap.id_personal 
				and au.id_ubicacion = aa.id_ubicacion 
				and ac.estado ='activo'
				and am.estado='activo'
				and aa.estado = 'activo'
				and au.estado ='activo'
				order by direccion"
			);
			return $respuesta->getResult();
		}
	}
?>