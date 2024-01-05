<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_permisos extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function autocompletar_estudiantes($nombre){
			$respuesta=$this->db->query("
				select t1.nombre
				from aca_estudiante ae, 
				(select id_persona, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as nombre, estado 
						from ral_persona rp 
						where rp.estado='activo') as t1 
				where ae.id_persona=t1.id_persona 
				and ae.estado='activo' 
				and t1.estado='activo'
				and t1.nombre ilike '%$nombre%';

				");
			return $respuesta->getResult();
		}
		public function buscar_clase($nombre){
			$respuesta=$this->db->query("
				select am.id_materia, am.nombre_materia 
				from aca_clase ac, aca_estudiante ae, aca_inscripcion ai, aca_materia am, ral_persona rp 
				where ai.id_estudiante=ae.id_estudiante 
				and rp.id_persona=ae.id_persona
				and ac.id_clase = ai.id_clase 
				and am.id_materia=ac.id_materia 
				and ae.estado='activo' 
				and ae.estado='activo' 
				and ai.estado='activo' 
				and am.estado='activo' 
				and rp.estado='activo'
				and concat(nom_persona,' ',ap_pat_persona,' ',ap_mat_persona) ilike '$nombre'
				UNION ALL
				select am.id_materia, am.nombre_materia
				from aca_clase ac, aca_estudiante ae, aca_inscripcion ai, aca_materia am, ral_persona rp
				where ai.id_estudiante=ae.id_estudiante
				and rp.id_persona=ae.id_persona
				and ac.id_clase::text = coalesce(split_part(ai.id_clase_esp,'||',1),ai.id_clase::text)
				and am.id_materia=ac.id_materia
				and ae.estado='activo'
				and ae.estado='activo'
				and ai.estado='activo'
				and am.estado='activo'
				and rp.estado='activo'
				and concat(nom_persona,' ',ap_pat_persona,' ',ap_mat_persona) ilike '$nombre';
				");
			return $respuesta->getResult();
		}
		public function buscar_clase_reemplazo($fecha,$id){
			$respuesta=$this->db->query("
				select ac.id_clase, concat(au.direccion,' - ',aa.nombre_aula,' - ',replace(ah.horarios,'||','a'))
					from aca_materia am, aca_clase ac, aca_horarios ah, adm_conf_horarios ach, ral_categoria rc, adm_ubicacion au, aca_aula aa,
					(select case 
						when extract (dow from '$fecha'::date) = 1 then 'lunes'
						when extract (dow from '$fecha'::date) = 2 then 'martes'
						when extract (dow from '$fecha'::date) = 3 then 'miercoles'
						when extract (dow from '$fecha'::date) = 4 then 'jueves'
						when extract (dow from '$fecha'::date) = 5 then 'viernes'
						when extract (dow from '$fecha'::date) = 6 then 'sabado'
						when extract (dow from '$fecha'::date) = 0 then 'domingo'
					end as dia)as t1
					where am.id_materia=ac.id_materia
					and ac.id_horarios=ach.id_conf_horarios
					and ach.id_conf_horarios=ah.id_conf_horarios
					and rc.id_categoria=ah.dias
					and ac.id_aula=aa.id_aula
					and aa.id_ubicacion=au.id_ubicacion
					and t1.dia=rc.detalle
					and au.estado='activo'
					and aa.estado='activo'
					and am.estado='activo'
					and ac.estado='activo'
					and ah.estado='activo'
					and ach.estado='activo'
					and rc.estado='activo'
					and am.id_materia=$id;
				");
			return $respuesta->getResult();
		}
		public function registrar_permiso($nombre,$f_permiso,$f_reemplazo,$usuario,$clase_remp,$id_mod){
			$respuesta=$this->db->query("
				select * from public.fn_crear_reprogramacion('$nombre','$f_permiso','$f_reemplazo','$usuario',$clase_remp, $id_mod);
				");
			return $respuesta->getResult();
		}
		public function lista_permisos(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, 
					arh.id_reprogramacion_horario as id_rep, 
					concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',ap_mat_persona) as estudiante, 
					arh.fec_reprogramacion, 
					arh.fec_reemplazo, 
					t1.dia ,concat(au.direccion,' ',aa.nombre_aula,' ',replace(ah.horarios,'||','-')) as detalle
				from aca_reprogramacion_horario arh, aca_inscripcion ai, aca_estudiante ae, ral_persona rp, aca_clase ac, aca_aula aa, adm_ubicacion au, aca_horarios ah, adm_conf_horarios ach, ral_categoria rc,
					(select arh1.id_reprogramacion_horario, case 
							when extract (dow from arh1.fec_reemplazo::date) = 1 then 'lunes'
							when extract (dow from arh1.fec_reemplazo::date) = 2 then 'martes'
							when extract (dow from arh1.fec_reemplazo::date) = 3 then 'miercoles'
							when extract (dow from arh1.fec_reemplazo::date) = 4 then 'jueves'
							when extract (dow from arh1.fec_reemplazo::date) = 5 then 'viernes'
							when extract (dow from arh1.fec_reemplazo::date) = 6 then 'sabado'
							when extract (dow from arh1.fec_reemplazo::date) = 0 then 'domingo'
						end as dia from aca_reprogramacion_horario arh1 where estado='activo')as t1
				where arh.id_inscripcion=ai.id_inscripcion
				and t1.id_reprogramacion_horario=arh.id_reprogramacion_horario
				and ai.id_estudiante=ae.id_estudiante
				and ae.id_persona=rp.id_persona
				and arh.id_clase=ac.id_clase
				and ac.id_aula=aa.id_aula
				and aa.id_ubicacion=au.id_ubicacion
				and ac.id_horarios=ach.id_conf_horarios
				and ach.id_conf_horarios=ah.id_conf_horarios
				and ah.dias=rc.id_categoria
				and t1.dia=rc.detalle
				and rc.estado = 'activo'
				and arh.estado = 'activo'
				and ai.estado = 'activo'
				and ae.estado = 'activo'
				and rp.estado = 'activo'
				and ac.estado = 'activo'
				and aa.estado = 'activo'
				and au.estado = 'activo'
				and ah.estado = 'activo'
				and ach.estado = 'activo';
				");
			return $respuesta->getResult();
		}
		public function mostrar_permiso($id){
			$respuesta=$this->db->query("
				select arh.id_reprogramacion_horario as id_rep, 
				concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',ap_mat_persona) as estudiante, 
				am.id_materia, 
				am.nombre_materia, 
				arh.fec_reprogramacion, 
				arh.fec_reemplazo, 
				arh.id_clase,concat(au.direccion,' ',aa.nombre_aula,' ',ah.horarios) as detalle
				from aca_reprogramacion_horario arh, aca_inscripcion ai, aca_estudiante ae, ral_persona rp, aca_clase ac, aca_aula aa, adm_ubicacion au, aca_horarios ah, adm_conf_horarios ach, ral_categoria rc,aca_materia am,
					(select arh1.id_reprogramacion_horario, case 
							when extract (dow from arh1.fec_reemplazo::date) = 1 then 'lunes'
							when extract (dow from arh1.fec_reemplazo::date) = 2 then 'martes'
							when extract (dow from arh1.fec_reemplazo::date) = 3 then 'miercoles'
							when extract (dow from arh1.fec_reemplazo::date) = 4 then 'jueves'
							when extract (dow from arh1.fec_reemplazo::date) = 5 then 'viernes'
							when extract (dow from arh1.fec_reemplazo::date) = 6 then 'sabado'
							when extract (dow from arh1.fec_reemplazo::date) = 0 then 'domingo'
						end as dia from aca_reprogramacion_horario arh1 where estado='activo')as t1
				where arh.id_inscripcion=ai.id_inscripcion
				and am.id_materia=ac.id_materia
				and t1.id_reprogramacion_horario=arh.id_reprogramacion_horario
				and ai.id_estudiante=ae.id_estudiante
				and ae.id_persona=rp.id_persona
				and arh.id_clase=ac.id_clase
				and ac.id_aula=aa.id_aula
				and aa.id_ubicacion=au.id_ubicacion
				and ac.id_horarios=ach.id_conf_horarios
				and ach.id_conf_horarios=ah.id_conf_horarios
				and ah.dias=rc.id_categoria
				and t1.dia=rc.detalle
				and rc.estado = 'activo'
				and am.estado = 'activo'
				and arh.estado = 'activo'
				and ai.estado = 'activo'
				and ae.estado = 'activo'
				and rp.estado = 'activo'
				and ac.estado = 'activo'
				and aa.estado = 'activo'
				and au.estado = 'activo'
				and ah.estado = 'activo'
				and ach.estado = 'activo'
				and arh.id_reprogramacion_horario=$id;
				");
			return $respuesta->getResult();
		}
		public function eliminar_permiso($id){
			$respuesta=$this->db->query("
				update aca_reprogramacion_horario 
				set estado='inactivo' 
				where id_reprogramacion_horario=$id;
				");
			return $respuesta;
		}
	}
?>