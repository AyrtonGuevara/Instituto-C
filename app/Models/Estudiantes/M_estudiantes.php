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
			$respuesta=$this->db->query("
				select cp.id_precios, cp.precio, concat(replace(am.nombre_materia,'-',''),' - ',cp.detalle)as nombre_materia
				from aca_materia am, com_precios cp
				where am.id_materia = cp.id_materia
				and am.estado='activo'
				and cp.estado='activo';
				");
			return $respuesta->getResult();
		}
		public function lista_lapso(){
			$respuesta=$this->db->query("select id_categoria, detalle from ral_categoria where nombre_categoria='tiempo-inscripcion' and estado='activo'");
			return $respuesta->getResult();
		}
		public function horarios_materia($id){
			$respuesta=$this->db->query("
				select ach.id_conf_horarios, h.dias_horarios, cp.precio
				FROM adm_conf_horarios ach, aca_clase ac2, aca_materia am, com_precios cp,
				(SELECT ac.id_conf_horarios, string_agg(concat((select rc.detalle 
						from ral_categoria rc 
						where rc.id_categoria=ac.dias 
						and rc.estado='activo' ),': ' ,replace(ac.horarios,' || ',' - ')), ' || ') as dias_horarios 
					from aca_horarios ac 
					where ac.estado='activo' 
					group by ac.id_conf_horarios) as h 
				where ach.id_conf_horarios=h.id_conf_horarios
				and cp.id_materia=am.id_materia
				and ac2.id_horarios = ach.id_conf_horarios 
				and am.id_materia = ac2.id_materia 
				and cp.id_precios  = $id
				and ac2.estado = 'activo'
				and ach.estado= 'activo'
				and cp.estado='activo'
				group by ach.id_conf_horarios, h.dias_horarios,cp.precio;
				");
			return $respuesta->getResult();
		}
		public function aulas_materia($id_horarios,$id_precios){
			$respuesta=$this->db->query("
				select aula.id_aula, 
				concat('Direccion : ',au.direccion, ' || Aula :  ' ,aula.nombre_aula) as aula 
				from adm_ubicacion au,
				aca_clase ac,
				com_precios cp,
				(select aa.id_ubicacion,aa.id_aula, aa.nombre_aula 
					from aca_aula aa 
					where aa.estado='activo') as aula 
				where au.id_ubicacion=aula.id_ubicacion 
				and ac.id_aula = aula.id_aula
				and cp.id_materia=ac.id_materia
				and ac.id_horarios=$id_horarios
				and cp.id_precios=$id_precios
				and ac.estado ='activo'
				and au.estado='activo'
				order by au.fec_creado desc;
			");
			return $respuesta->getResult();
		}
		public function horarios_materia_esp($id){
			$respuesta=$this->db->query("
				select ah.id_horarios, concat(rc.detalle ,': ', replace(ah.horarios,'||','-'))as horario, cp.precio
				from aca_horarios ah,
				com_precios cp,
				ral_categoria rc,
				adm_conf_horarios ach,
				aca_clase ac,
				aca_materia am 
				where ah.dias = rc.id_categoria 
				and ach.id_conf_horarios  = ah.id_conf_horarios 
				and ac.id_horarios = ach.id_conf_horarios 
				and ac.id_materia = am.id_materia 
				and cp.id_materia = am.id_materia
				and cp.id_precios = $id
				and am.estado='activo'
				and ac.estado = 'activo'
				and ach.estado = 'activo'
				and ah.estado = 'activo'
				and cp.estado='activo'
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
		public function ver_estudiante($id,$tipo){
			$respuesta=$this->db->query("
select ae.id_estudiante, 
	rp.nom_persona,
	rp.ap_pat_persona,
	rp.ap_mat_persona, 
	rp.fec_nacimiento,
	extract(year from age(current_date,rp.fec_nacimiento::date)) as edad,
	rp.celular, 
	ae.unid_educativa, 
	ae.zona,ae.direccion, 
	ae.grado, 
	tutor.*, 
	turno.id_categoria as id_turno, 
	turno.detalle as turno, 
	nivel.id_categoria as id_nivel , 
	nivel.detalle as nivel,
	fuente.id_categoria as id_fuente,
	fuente.detalle as fuente
from aca_estudiante ae, 
	ral_persona rp,
	(select id_categoria, detalle from ral_categoria where nombre_categoria='fuente-informacion' and estado='activo') as fuente,
	(select id_categoria, detalle from ral_categoria where nombre_categoria='turno-estudiante' and estado='activo')as turno,
	(select id_categoria, detalle from ral_categoria where nombre_categoria='nivel-estudiante' and estado='activo')as nivel,
	(select ct.id_tutor, 
			rp2.nom_persona as nom_tutor, 
			rp2.ap_pat_persona as pat_tutor,
			rp2.ap_mat_persona as mat_tutor, 
			ct.act_tutor, 
			ct.trab_tutor, 
			ct.telefono_tutor, 
			rp2.celular as celular_tutor,
			ct.fuente as fuente_tutor
		from com_tutor ct, 
			ral_persona rp2
		where ct.id_persona=rp2.id_persona
		and case when $tipo=2 then
			(rp2.estado='inactivo'
			and ct.estado='inactivo')
		else 
			(rp2.estado='activo'
			and ct.estado='activo')
		end
)as tutor
where ae.id_persona=rp.id_persona
and fuente.id_categoria=tutor.fuente_tutor
and turno.id_categoria=ae.turno
and nivel.id_categoria=ae.nivel
and tutor.id_tutor=ae.id_tutor
and case when $tipo=2 then
	(rp.estado ='inactivo'
	and ae.estado ='inactivo')
else 
	(rp.estado ='activo'
	and ae.estado ='activo')
end
and ae.id_estudiante=$id;
				");
			return $respuesta->getResult();
		}
		public function modificar_estudiante_tutor($id, $array, $usuario){
			print_r($array);
			echo "<br>".$array['ue'];
			$respuesta=$this->db->query("
				update aca_estudiante 
				set unid_educativa='$array[ue]',grado=$array[grado], nivel=$array[nivel], turno=$array[turno], zona='$array[zona]' , direccion='$array[calle]', usu_modificado='$usuario', fec_modificado=now() 
				where id_estudiante=$id;

				update ral_persona 
				set nom_persona='$array[nombre]', ap_pat_persona='$array[apellido_paterno]', ap_mat_persona='$array[apellido_materno]', fec_nacimiento='$array[fecha_nac]', celular=$array[celular], usu_modificado='$usuario', fec_modificado=now() 
				where id_persona=(select id_persona from aca_estudiante where id_estudiante=$id);

				update com_tutor 
				set act_tutor='$array[t_actividad]', trab_tutor='$array[t_trabajo]', telefono_tutor=$array[t_telefono], usu_modificado='$usuario', fec_modificado=now() 
				where id_tutor=(select id_tutor from aca_estudiante where id_estudiante=$id);

				update ral_persona
				set nom_persona='$array[t_nombre]', ap_pat_persona='$array[t_apellido_paterno]', ap_mat_persona='$array[t_apellido_materno]', celular=$array[t_celular], usu_modificado='$usuario', fec_modificado=now() 
				where id_persona=(select ct.id_persona from com_tutor ct, aca_estudiante ae where ct.id_tutor=ae.id_tutor and ae.id_estudiante=$id);
				");
			return $respuesta;
		}
	}
?>