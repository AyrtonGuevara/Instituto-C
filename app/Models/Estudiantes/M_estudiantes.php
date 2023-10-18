<?php
/*
	Ayrton Jhonny Guevara Montaño 03-08-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_estudiantes extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_fuentes(){
			$respuesta=$this->db->query("select id_categoria, detalle from ral_categoria where nombre_categoria='fuente-informacion' and estado='activo'");
			return $respuesta->getResult();
		}
		public function lista_turno(){
			$respuesta=$this->db->query("select id_categoria, detalle from ral_categoria where nombre_categoria='turno-estudiante' and estado='activo'");
			return $respuesta->getResult();
		}
		public function lista_nivel(){
			$respuesta=$this->db->query("select id_categoria, detalle from ral_categoria where nombre_categoria='nivel-estudiante' and estado='activo'");
			return $respuesta->getResult();
		}
		public function lista_materias(){
			$respuesta=$this->db->query("select id_materia,nombre_materia from aca_materia am where estado = 'activo'");
			return $respuesta->getResult();
		}
		public function lista_lapso(){
			$respuesta=$this->db->query("select id_categoria, detalle from ral_categoria where nombre_categoria='tiempo-inscripcion' and estado='activo'");
			return $respuesta->getResult();
		}
		public function horarios_materia($id){
			$respuesta=$this->db->query("select ach.id_conf_horarios, h.dias_horarios, am.precio
				FROM adm_conf_horarios ach, aca_clase ac2, aca_materia am,
				(SELECT ac.id_conf_horarios, string_agg(concat((select rc.detalle 
						from ral_categoria rc 
						where rc.id_categoria=ac.dias 
						and rc.estado='activo' ),': ' ,replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
					from aca_horarios ac 
					where ac.estado='activo' 
					group by ac.id_conf_horarios) as h 
				where ach.id_conf_horarios=h.id_conf_horarios
				and ac2.id_horarios = ach.id_conf_horarios 
				and am.id_materia = ac2.id_materia 
				and ac2.id_materia  = $id
				and ac2.estado = 'activo'
				and ach.estado= 'activo'
				group by ach.id_conf_horarios, h.dias_horarios,am.precio;");
			return $respuesta->getResult();
		}
		public function aulas_materia($id_horarios,$id_materia){
			$respuesta=$this->db->query("
				select aula.id_aula, 
				concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula 
				from adm_ubicacion au,
				aca_clase ac ,
				(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula 
					from aca_aula aa 
					where aa.estado='activo') as aula 
				where au.id_ubicacion=aula.id_ubicacion 
				and ac.id_aula = aula.id_aula
				and ac.id_horarios=$id_horarios
				and ac.id_materia=$id_materia
				and ac.estado ='activo'
				and au.estado='activo'
				order by au.fec_creado desc;
			");
			return $respuesta->getResult();
		}
		public function horarios_materia_esp($id){
			$respuesta=$this->db->query("
				select ah.id_horarios, concat(rc.detalle ,': ', replace(ah.horarios,'||','-'))as horario, am.precio
				from aca_horarios ah,
				ral_categoria rc,
				adm_conf_horarios ach,
				aca_clase ac,
				aca_materia am 
				where ah.dias = rc.id_categoria 
				and ach.id_conf_horarios  = ah.id_conf_horarios 
				and ac.id_horarios = ach.id_conf_horarios 
				and ac.id_materia = am.id_materia 
				and ac.id_materia = $id
				and am.estado='activo'
				and ac.estado = 'activo'
				and ach.estado = 'activo'
				and ah.estado = 'activo'
				");
			return $respuesta->getResult();
		}
		public function horarios_conf_materia_esp($id_horarios){
			$respuesta=$this->db->query("
				select ach.id_conf_horarios from adm_conf_horarios ach, aca_horarios ah  
				where ach.id_conf_horarios = ah.id_conf_horarios
				and ah.id_horarios = $id_horarios
				and ah.estado ='activo' 
				and ach.estado = 'activo';
				");
			$respuesta=$respuesta->getRow()->id_conf_horarios;
			return $respuesta;
		}
		public function registrar_estudiante($usuario,$inscripcion_estudiante){
			//echo "select * from public.fn_agregar_estudiante('$usuario','$inscripcion_estudiante'::JSON)";
			$respuesta=$this->db->query("select * from public.fn_agregar_estudiante('$usuario','$inscripcion_estudiante'::JSON)");
			return $respuesta->getResult();
		}
		
	}
?>