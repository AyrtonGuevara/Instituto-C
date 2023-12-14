<?php
/*
	Ayrton Jhonny Guevara Montaño 28-10-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_control_asistencia extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function buscar_fechas_clase($id,$comp_mes){
			$respuesta=$this->db->query("
				select t2.dia, to_char(t2.fec,'DD/MM') as fecha
				from aca_clase ac , 
				adm_conf_horarios ach , 
				aca_horarios ah , 
				ral_categoria rc ,
				(select t1.fec , case 
					when extract (dow from t1.fec) = 1 then 'lunes'
					when extract (dow from t1.fec) = 2 then 'martes'
					when extract (dow from t1.fec) = 3 then 'miercoles'
					when extract (dow from t1.fec) = 4 then 'jueves'
					when extract (dow from t1.fec) = 5 then 'viernes'
					when extract (dow from t1.fec) = 6 then 'sabado'
					when extract (dow from t1.fec) = 0 then 'domingo'
					end as dia
				from
				(select generate_series(
					(date_trunc('month',('$comp_mes'||'-01')::date)),
					(date_trunc('month',('$comp_mes'||'-01')::date) +interval '1 month'-interval '1 day'),
					'1 day'::interval)::date as fec)as t1)as t2
				where ac.id_clase = $id --
				and ac.id_horarios = ach.id_conf_horarios 
				and ach.id_conf_horarios = ah.id_conf_horarios 
				and rc.id_categoria = ah.dias 
				and rc.detalle = t2.dia
				and ah.estado ='activo'
				and ach.estado = 'activo'
				and ac.estado ='activo' 
				and rc.estado ='activo'
				order by t2.fec;
				");
			return $respuesta->getResult();
		}
		public function buscar_estudiantes_asistencias($id,$comp_mes){
			$respuesta=$this->db->query("
				select distinct on(aa.id_estudiante,aa.fec_asistencia::date) ae.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as nombre, aa.id_clase, rc.detalle , to_char(aa.fec_asistencia,'DD/MM') as fecha
						from aca_asistencia aa, aca_estudiante ae, ral_persona rp, ral_categoria rc 
						where aa.id_clase = $id
						and ae.id_estudiante = aa.id_estudiante 
						and rp.id_persona = ae.id_persona 
						and rc.id_categoria = aa.valor_asistencia 
						and ae.estado = 'activo'
						and rp.estado = 'activo'
						and rc.estado = 'activo'
						and to_char(aa.fec_asistencia,'MM') = to_char(('$comp_mes'||'-03')::date,'MM')
						and to_char(aa.fec_asistencia,'YYYY') = to_char(('$comp_mes'||'-03')::date,'YYYY')
						order by aa.id_estudiante , aa.fec_asistencia::date;
				");
			return $respuesta->getResult();
		}
		public function lista_cursos(){
			$respuesta=$this->db->query("
				select aula.id_aula, concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula from adm_ubicacion au,(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula from aca_aula aa where aa.estado='activo') as aula where au.id_ubicacion=aula.id_ubicacion and au.estado='activo'  order by au.fec_creado desc;
				");
			return $respuesta;
		}
		public function lista_clases_aula($id){
			$respuesta=$this->db->query("
				select distinct on (ac.id_clase) ac.id_clase, am.nombre_materia, string_agg(rc.detalle||' '||split_part(ah.horarios,'||',1),'- ') as dias
				from aca_clase ac, aca_materia am, adm_conf_horarios ach, aca_horarios ah, aca_aula aa,ral_categoria rc
				where ac.id_aula=$id
				and ac.id_materia=am.id_materia
				and ac.id_horarios= ach.id_conf_horarios
				and ach.id_conf_horarios=ah.id_conf_horarios
				and ac.id_aula=aa.id_aula
				and rc.id_categoria=ah.dias
				and rc.estado='activo'
				and ac.estado='activo'
				and ach.estado='activo'
				and ah.estado='activo'
				and aa.estado='activo'
				group by ac.id_clase, am.nombre_materia;
				");
			return $respuesta->getResult();
		}
	}
?>