<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Models\Ambientes;
	use CodeIgniter\Model;

	class M_clases extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_materias(){
			$respuesta=$this->db->query("
				select am.id_materia, 
					concat((select rc.detalle from ral_categoria rc where rc.id_categoria=am.tipo_materia), ' || ' , am.nombre_materia) as materia 
				from aca_materia am 
				where am.estado='activo';
				");
			return $respuesta;
		}
		public function lista_horarios(){
			$respuesta=$this->db->query("
				select ach.id_conf_horarios, h.dias_horarios FROM adm_conf_horarios ach,(SELECT ac.id_conf_horarios, string_agg(concat((select rc.detalle from ral_categoria rc where rc.id_categoria=ac.dias and rc.estado='activo' ),': ' ,replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios from aca_horarios ac where ac.estado='activo' group by ac.id_conf_horarios) as h where ach.id_conf_horarios=h.id_conf_horarios and ach.estado='activo' group by ach.id_conf_horarios, h.dias_horarios;

				");
			return $respuesta;
		}
		public function lista_aulas(){
			$respuesta=$this->db->query("
				select aula.id_aula, concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula from adm_ubicacion au,(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula from aca_aula aa where aa.estado='activo') as aula where au.id_ubicacion=aula.id_ubicacion and au.estado='activo'  order by au.fec_creado desc;
				");
			return $respuesta;
		}
		public function lista_docentes(){
			$respuesta=$this->db->query("
				select ap.id_personal, (select concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',ap_mat_persona) from ral_persona rp where rp.id_persona=ap.id_persona) as persona from adm_personal ap where ap.estado='activo' and ap.puesto = (select rc.id_categoria from ral_categoria rc where nombre_categoria='cargo'  and detalle in ('docente','profesor') and estado='activo');

				");
			return $respuesta;
		}
		public function registrar_clase($materia,$horario,$aula,$personal,$usuario){
			$respuesta=$this->db->query("insert into aca_clase (id_materia,id_horarios,id_aula,id_personal, usu_creado,fec_creado,estado) VALUES ($materia,$horario,$aula,$personal,'$usuario',now(),'activo');");
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
		public function modificar_clase($id,$materia,$horario,$aula,$personal,$usuario){
			$respuesta=$this->db->query("UPDATE aca_clase SET id_materia=$materia, id_horarios=$horario,id_aula=$aula,id_personal=$personal, usu_modificado='$usuario', fec_modificado=now() WHERE id_clase=$id and estado='activo'");
			return $respuesta;
		}
		public function eliminar_clases($id,$usuario){
			$respuesta=$this->db->query("UPDATE aca_clase SET estado='inactivo',usu_modificado='$usuario',fec_modificado=now() WHERE id_clase=$id;");
			return $respuesta;
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
?>



