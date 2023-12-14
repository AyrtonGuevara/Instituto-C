<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_lista_estudiantes extends Model{
		function __construct(){
			$this->db=db_connect();
		}
		public function lista_estudiantes(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, ae.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as estudiante, t1.tutor, concat(au.direccion ,' aula: ',aa.nombre_aula) as aula, am.nombre_materia, ai.fec_inicio::date, 
					case when ai.lapso = rc.id_categoria and rc.detalle='mes' then (ai.fec_inicio + (ai.cantidad||'month')::interval)::date
					when  ai.lapso = rc.id_categoria and rc.detalle='dia' then (ai.fec_inicio + (ai.cantidad||'day')::interval)::date 
					end as fec_fin
				from aca_estudiante ae, ral_persona rp, aca_inscripcion ai, aca_clase ac, aca_aula aa, adm_ubicacion au, aca_materia am, ral_categoria rc,
					(select ct.id_tutor, concat(rp2.nom_persona,' ',rp2.ap_pat_persona,' ',rp2.ap_mat_persona) as tutor
					from aca_estudiante ae2, com_tutor ct, ral_persona rp2
					where ae2.id_tutor=ct.id_tutor
					and ct.id_persona=rp2.id_persona
					and ae2.estado='activo'
					and ct.estado='activo'
					and rp2.estado='activo'
					) as t1
				where ae.id_persona = rp.id_persona
				and ae.id_estudiante = ai.id_estudiante
				and ae.id_tutor=t1.id_tutor
				and ac.id_clase=ai.id_clase
				and aa.id_aula=ac.id_aula
				and au.id_ubicacion=aa.id_ubicacion
				and am.id_materia=ac.id_materia
				and rc.id_categoria=ai.lapso
				and rc.estado='activo'
				and am.estado='activo'
				and au.estado='activo'
				and aa.estado='activo'
				and ac.estado='activo'
				and ae.estado='activo'
				and rp.estado='activo'
				and ai.estado='activo'
				");
			return $respuesta->getResult();
		}
	}
?>


