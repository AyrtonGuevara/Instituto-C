<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Models\Cursos;
	use CodeIgniter\Model;

	class M_horarios extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_horarios(){
			$respuesta=$this->db->query("select * from public.fn_listar_horarios()");
			return $respuesta;
		}
		public function lista_dia(){
			$respuesta=$this->db->query("SELECT id_categoria, detalle FROM ral_categoria WHERE tipo = 'dia' AND nombre_categoria='dia' AND estado='activo'");
			return $respuesta;
		}
		public function registrar_horarios($usuario,$valores_horarios){
			//echo "select * from public.fn_agregar_horarios('$usuario','$valores_horarios'::JSON)";
			$respuesta=$this->db->query("select * from public.fn_agregar_horarios('$usuario','$valores_horarios'::JSON)");
			return $respuesta->getResult();
		}
		public function mostrar_horarios($id){
			$respuesta=$this->db->query("
						SELECT ah.id_horarios,
						    ah.id_conf_horarios as id_conf,
							ah.dias,
							(select rc.detalle from ral_categoria rc where rc.id_categoria=ah.dias)as detalle_dias,
							split_part(ah.horarios,' || ',1) as hora_inicio, 
							split_part(ah.horarios,' || ',2) as hora_fin 
						from aca_horarios ah  
						where ah.estado='activo'
						and ah.id_conf_horarios = $id
						");
			return $respuesta->getResult();
		}
		public function modificar_horarios($usuario,$valores_horarios){
			//echo "select * from public.fn_modificar_horarios('$usuario','$valores_horarios'::JSON);";
			$respuesta=$this->db->query("select * from public.fn_modificar_horarios('$usuario','$valores_horarios'::JSON);");
			return $respuesta->getResult();
		}
		public function eliminar_horarios($id,$usuario){
			$respuesta=$this->db->query("update adm_conf_horarios set estado='inactivo', usu_modificado='$usuario', fec_modificado = now() where id_conf_horarios=$id;
				update aca_horarios set estado='inactivo', usu_modificado='$usuario', fec_modificado = now()		 where id_conf_horarios=$id;");
			return $respuesta;
		}
	}
?>