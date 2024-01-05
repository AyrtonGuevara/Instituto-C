<?php
/*
	Ayrton Jhonny Guevara Montaño 14-09-2023
*/
	namespace App\Models\Ambientes;
	use CodeIgniter\Model;

	class M_clases extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_clases(){
			$respuesta=$this->db->query(
				"select * from public.fn_listar_clases();"
			);
			return $respuesta->getResult();
		}
		public function lista_materias(){
			$respuesta=$this->db->query("
				select id_materia, concat(rc.detalle,' || ',am.nombre_materia) as materia 
				from aca_materia am,
					ral_categoria rc 
				where rc.id_categoria = am.tipo_materia 
				and am.estado='activo'
				and rc.estado ='activo'
				");
			return $respuesta;
		}
		public function lista_horarios(){
			$respuesta=$this->db->query("
				select ach.id_conf_horarios, t1.dias_horarios 
				from adm_conf_horarios ach,
					(SELECT ah.id_conf_horarios, string_agg(concat((
						rc.detalle ),': ' ,replace(ah.horarios,' || ',' - ')), ' || ') as dias_horarios 
					from aca_horarios ah , ral_categoria rc
					where ah.dias = rc.id_categoria
					and ah.estado='activo' 
					and ah.estado='activo'
					group by ah.id_conf_horarios
					order by ah.id_conf_horarios) as t1
				where ach.id_conf_horarios=t1.id_conf_horarios 
				and ach.estado='activo' 
				group by ach.id_conf_horarios, t1.dias_horarios;
				");
			return $respuesta;
		}
		public function lista_aulas(){
			$respuesta=$this->db->query("
				select aula.id_aula, concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula 
				from adm_ubicacion au,(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula from aca_aula aa where aa.estado='activo') as aula 
				where au.id_ubicacion=aula.id_ubicacion 
				and au.estado='activo'  
				order by au.fec_creado desc;
				");
			return $respuesta;
		}
		public function lista_docentes(){
			$respuesta=$this->db->query("
				select ap.id_personal , concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',ap_mat_persona)as persona
				from adm_personal ap , ral_persona rp , ral_categoria rc 
				where ap.puesto =rc.id_categoria 
				and ap.id_persona =rp.id_persona 
				and rc.detalle in ('docente','profesor')
				and ap.estado ='activo'
				and rp.estado ='activo'
				and rc.estado ='activo';
				");
			return $respuesta;
		}
		public function registrar_clase($usuario,$valores_clase){
			$respuesta=$this->db->query("
				SELECT * FROM fn_agregar_clases('$usuario','$valores_clase'::JSON);
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
		public function modificar_clase($id,$materia,$horario,$aula,$personal,$usuario){
			$respuesta=$this->db->query("
				update aca_clase 
				set id_materia=$materia, 
					id_horarios=$horario,
					id_aula=$aula,
					id_personal=$personal, 
					usu_modificado='$usuario', 
					fec_modificado=now() 
				where id_clase=$id and estado='activo'
				");
			return $respuesta;
		}
		public function eliminar_clases($id,$usuario){
			$respuesta=$this->db->query("
				update aca_clase 
				set estado='inactivo',
					usu_modificado='$usuario',
					fec_modificado=now() 
				where id_clase=$id;
				");
			return $respuesta;
		}
	}
?>