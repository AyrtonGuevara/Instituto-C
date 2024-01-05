<?php
/*
	Ayrton Jhonny Guevara Montaño 13-10-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_asistencia_estudiantes extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function clases_activas(){
			$respuesta=$this->db->query("
				select * from public.fn_asistencia_estudiantes();
			");
			return $respuesta->getResult();
		}
		public function registrar_asistencia($nombre,$valor){
			$respuesta=$this->db->query("
				select * from public.fn_registrar_asistencia('$nombre','$valor');
			");
			return $respuesta->getResult();
		}
		public function asistencias_guardadas($id){
			$respuesta=$this->db->query("
				select distinct on(aa.id_estudiante)
					concat('asistencia-',aa.id_estudiante,'-',aa.id_clase)as estudiante,
					rc.detalle 
				from aca_asistencia aa, ral_categoria rc 
			    where aa.valor_asistencia = rc.id_categoria 
			    and rc.estado ='activo'
			    and fec_asistencia::date = now()::date
			    order by aa.id_estudiante,aa.fec_asistencia desc");
			return $respuesta->getResult();	
		}
	}
?>


