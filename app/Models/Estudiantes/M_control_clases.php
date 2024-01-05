<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_control_Clases extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_aulas(){
			$respuesta=$this->db->query("
				select aula.id_aula, concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula 
				from adm_ubicacion au,
					(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula 
					from aca_aula aa 
					where aa.estado='activo') as aula 
				where au.id_ubicacion=aula.id_ubicacion 
				and au.estado='activo'  
				order by au.fec_creado desc;
			");
			return $respuesta;
		}
		public function cronograma_clases($id){
			$respuesta=$this->db->query("
				select ac.id_clase, 
					am.nombre_materia as materia, 
					string_agg(concat(rc.detalle,': ',replace(ah.horarios,' || ',' - ')),' || ') as horarios
				from aca_clase ac, aca_materia am, adm_conf_horarios ach , aca_horarios ah , ral_categoria rc 
				where ac.id_materia = am.id_materia 
				and ac.id_horarios = ach.id_conf_horarios 
				and ach.id_conf_horarios = ah.id_conf_horarios 
				and ah.dias = rc.id_categoria 
				and ac.estado ='activo'
				and am.estado ='activo'
				and ach.estado ='activo'
				and ah.estado ='activo'
				and rc.estado ='activo'
				and ac.id_aula=$id
				group by id_clase , am.nombre_materia 
				");
			return $respuesta->getResult();
		}
		public function mostrar_clases($id){
			$respuesta=$this->db->query("
				select ac.id_clase, 
					aa.id_aula, 
					concat(au.direccion,' - ',aa.nombre_aula)as nombre_aula,
					cantidad_estudiantes as capacidad, 
					ac.id_materia, 
					concat (rc.detalle,' - ',am.nombre_materia) as materia, 
					ac.id_personal, 
					concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as docente, 
					ac.id_horarios, 
					t1.dias_horarios as horarios
				from aca_clase ac , aca_aula aa , adm_ubicacion au , adm_personal ap , ral_persona rp , adm_conf_horarios ach, ral_categoria rc , aca_materia am,
				(SELECT ah.id_conf_horarios, 
					string_agg(concat((rc.detalle ),': ' ,replace(ah.horarios,' || ',' - ')), ' || ') as dias_horarios
					from aca_horarios ah , ral_categoria rc
					where ah.dias = rc.id_categoria
					and ah.estado='activo'
					and ah.estado='activo'
					group by ah.id_conf_horarios
					order by ah.id_conf_horarios )as t1
				where ac.id_aula = aa.id_aula 
				and aa.id_ubicacion = au.id_ubicacion 
				and ac.id_personal =ap.id_personal 
				and ap.id_persona = rp.id_persona 
				and ac.id_horarios = ach.id_conf_horarios 
				and ach.id_conf_horarios = t1.id_conf_horarios 
				and rc.id_categoria = am.tipo_materia
				and am.id_materia = ac.id_materia 
				and am.estado = 'activo'
				and rc.estado = 'activo'
				and ac.estado = 'activo'
				and aa.estado = 'activo'
				and au.estado = 'activo'
				and ap.estado = 'activo'
				and rp.estado = 'activo'
				and ach.estado = 'activo'
				and ac.id_clase=$id
				;");
			return $respuesta->getResult();
		}
		public function lista_estudiantes($id){
			$respuesta=$this->db->query("
				select ai.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)as nombre, ai.fec_inicio::date,  
				case when ai.lapso = rc.id_categoria and rc.detalle='mes' then (ai.fec_inicio + (ai.cantidad||'month')::interval)::date
					when  ai.lapso = rc.id_categoria and rc.detalle='dia' then (ai.fec_inicio + (ai.cantidad||'day')::interval)::date 
					end as fec_fin
				from aca_inscripcion ai, aca_estudiante ae, ral_persona rp, ral_categoria rc
				where rp.id_persona=ae.id_persona 
				and ai.id_estudiante = ae.id_estudiante 
				and rc.id_categoria=ai.lapso
				and rc.estado='activo'
				and ae.estado='activo' 
				and rp.estado='activo' 
				and ai.estado='activo' 
				and ai.id_clase=$id;
				");
			return $respuesta->getResult();
		}
	}
?>