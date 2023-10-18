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
				select aula.id_aula, concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula from adm_ubicacion au,(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula from aca_aula aa where aa.estado='activo') as aula where au.id_ubicacion=aula.id_ubicacion and au.estado='activo'  order by au.fec_creado desc;
				");
			return $respuesta;
		}
		public function cronograma_clases($id){
			$respuesta=$this->db->query("
				select 
				 	ac.id_clase,
				 	(select am.nombre_materia 
						from aca_materia am 
						where am.id_materia=ac.id_materia)as materia,
				 	(select h.dias_horarios 
				  		FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, 
				    		string_agg(concat((select rc.detalle 
				     			from ral_categoria rc 
				     			where rc.id_categoria=ac.dias 
				     			and rc.estado='activo' ),': ' ,
				    		replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
				   		from aca_horarios ac 
				   		where ac.estado='activo' 
				   		group by ac.id_conf_horarios) as h 
				  	where ach.id_conf_horarios=h.id_conf_horarios 
				  	and ach.id_conf_horarios=ac.id_horarios 
				  	and ach.estado='activo')as horarios 
				from aca_clase ac 
				where ac.estado='activo'
				and ac.id_aula=$id;

				");
			return $respuesta->getResult();
		}
		public function mostrar_clases($id){
			$respuesta=$this->db->query("
				select 
					ac.id_clase,
					ac.id_aula,
					(select concat((select au.direccion 
							from adm_ubicacion au 
							where au.id_ubicacion=aa.id_ubicacion),' - ',aa.nombre_aula)
						from aca_aula aa 
						where ac.id_aula=aa.id_aula)as nombre_aula,
					(select aa.cantidad_estudiantes 
						from aca_aula aa 
						where aa.id_aula=ac.id_aula)as capacidad,
					ac.id_materia,
					(select am.nombre_materia 
						from aca_materia am 
						where am.id_materia=ac.id_materia)as materia,
					ac.id_personal,
					(select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) 
						from adm_personal ap,ral_persona rp 
						where ap.id_persona=rp.id_persona 
						and ap.id_personal=ac.id_personal)as docente,
					ac.id_horarios,
					(select h.dias_horarios 
						FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, 
								string_agg(concat((select rc.detalle 
									from ral_categoria rc 
									where rc.id_categoria=ac.dias 
									and rc.estado='activo' ),': ' ,
								replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
							from aca_horarios ac 
							where ac.estado='activo' 
							group by ac.id_conf_horarios) as h 
						where ach.id_conf_horarios=h.id_conf_horarios 
						and ach.id_conf_horarios=ac.id_horarios 
						and ach.estado='activo')as horarios 
				from aca_clase ac
				where ac.estado='activo'
				and ac.id_clase=$id
				;");
			return $respuesta->getResult();
		}
		public function lista_estudiantes($id){
			$respuesta=$this->db->query("
				select ai.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)as nombre, ai.fec_inicio::date from aca_inscripcion ai, aca_estudiante ae, ral_persona rp where rp.id_persona=ae.id_persona and ai.id_estudiante = ae.id_estudiante and ae.estado='activo' and rp.estado='activo' and ai.estado='activo' and ai.id_clase=$id;
				");
			return $respuesta->getResult();
		}
	}
	//se altero la tabla clase para que haga referencia a adm_conf_horarios y no a conf_horarios
	/*alter table aca_clase drop constraint aca_clase_id_horarios_fkey;
alter table aca_clase drop constraint aca_clase_id_horarios_aca_horarios_id_horarios;

alter table aca_clase add constraint aca_clase_id_horarios_fkey FOREIGN KEY (id_horarios) REFERENCES adm_conf_horarios(id_conf_horarios) ON DELETE CASCADE;
alter table aca_clase add constraint aca_clase_id_horarios_adm_conf_horarios_id_conf_horarios FOREIGN KEY (id_horarios) REFERENCES adm_conf_horarios(id_conf_horarios) ON DELETE CASCADE;*/

/*
select ac.id_aula,(select concat((select au.direccion from adm_ubicacion au where au.id_ubicacion=aa.id_ubicacion),' - ',aa.descripcion)from aca_aula aa where ac.id_aula=aa.id_aula)as nombre_aula,id_clase,(select am.nombre_materia from aca_materia am where am.id_materia=ac.id_materia)as materia,(select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) from adm_personal ap,ral_persona rp where ap.id_persona=rp.id_persona and ap.id_personal=ac.id_personal)as docente,( select h.dias_horarios FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, string_agg(concat((select rc.detalle from ral_categoria rc where rc.id_categoria=ac.dias and rc.estado='activo' ),': ' ,replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios from aca_horarios ac where ac.estado='activo' group by ac.id_conf_horarios) as h where ach.id_conf_horarios=h.id_conf_horarios and ach.id_conf_horarios=ac.id_horarios and ach.estado='activo')as horarios from aca_clase ac;


-general-
select 
	ac.id_clase,
	ac.id_aula,
	(select concat((select au.direccion 
			from adm_ubicacion au 
			where au.id_ubicacion=aa.id_ubicacion),' - ',aa.descripcion)
		from aca_aula aa 
		where ac.id_aula=aa.id_aula)as nombre_aula,
	ac.id_materia,
	(select am.nombre_materia 
		from aca_materia am 
		where am.id_materia=ac.id_materia)as materia,
	ac.id_personal,
	(select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) 
		from adm_personal ap,ral_persona rp 
		where ap.id_persona=rp.id_persona 
		and ap.id_personal=ac.id_personal)as docente,
	ac.id_horarios,
	(select h.dias_horarios 
		FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, 
				string_agg(concat((select rc.detalle 
					from ral_categoria rc 
					where rc.id_categoria=ac.dias 
					and rc.estado='activo' ),': ' ,
				replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
			from aca_horarios ac 
			where ac.estado='activo' 
			group by ac.id_conf_horarios) as h 
		where ach.id_conf_horarios=h.id_conf_horarios 
		and ach.id_conf_horarios=ac.id_horarios 
		and ach.estado='activo')as horarios 
from aca_clase ac;





select 
 	ac.id_clase,
 	(select am.nombre_materia 
		from aca_materia am 
		where am.id_materia=ac.id_materia)as materia,
 	(select h.dias_horarios 
  		FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, 
    		string_agg(concat((select rc.detalle 
     			from ral_categoria rc 
     			where rc.id_categoria=ac.dias 
     			and rc.estado='activo' ),': ' ,
    		replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
   		from aca_horarios ac 
   		where ac.estado='activo' 
   		group by ac.id_conf_horarios) as h 
  	where ach.id_conf_horarios=h.id_conf_horarios 
  	and ach.id_conf_horarios=ac.id_horarios 
  	and ach.estado='activo')as horarios 
from aca_clase ac where ac.id_aula=82;

*/




/*select ai.id_estudiante, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)as nombre, ai.fec_inicio 
from aca_inscripcion ai, aca_estudiante ae, ral_persona rp 
where rp.id_persona=ae.id_persona 
and ai.id_estudiante = ae.id_estudiante 
and ae.estado='activo' 
and rp.estado='activo' 
and ai.estado='activo' 
and ai.id_clase=$id;


estudiante 7 persona 2*/
?>