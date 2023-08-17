<?php
	/*
		Ayrton Jhonny Guevara Montaño 10-08-2023
	*/
	namespace App\Models\Clases;
	use CodeIgniter\Model;

	class M_horarios extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_dia(){
			$respuesta=$this->db->query("SELECT id_categoria, detalle FROM ral_categoria WHERE tipo = 'dia' AND nombre_categoria='dia' AND estado='activo'");
			return $respuesta;
		}
		/*public function registrar_horarios($j_envio){
			$respuesta=$this->db->query("");
			return $respuesta;
		}*/
		public function registrar_horarios($dias,$hora_inicio,$hora_fin){
			$contador=0;
			//se registra primero los horarios de forma individual
			try {
				foreach ($dias as $key => $value) {
					$respuesta=$this->db->query("INSERT INTO aca_horarios (dias,horarios,estado) VALUES ($dias[$contador], concat('$hora_inicio[$contador]',' || ','$hora_fin[$contador]'),'activo');");
					$contador++;
				}
				//ahora se hace una consulta para ver obtener los id de los horarios adicionados
				$respuesta=$this->db->query("select string_agg(id_horarios::text,' || ') as horarios from (select id_horarios from aca_horarios order by id_horarios desc limit $contador) as a;");
				//por ultimo se registra la configuracion de horarios
				$horario=$respuesta->getRow()->horarios;

				$respuesta=$this->db->query("INSERT INTO adm_conf_horarios (codigo_conf, id_horarios,estado) VALUES ('chuchas','$horario','activo');");

			} catch (Exception $e) {
				echo $e;
			}
			//return $respuesta;
		}
		public function aulas_direccion($id){
			$respuesta=$this->db->query("
				select id_aula, nombre_aula  
				from aca_aula aa, adm_ubicacion au 
				where aa.id_ubicacion = au.id_ubicacion
				and aa.estado = 'activo'
				and au.estado = 'activo'
				and au.id_ubicacion = $id
				");
			return $respuesta;
		}
		public function lista_cursos(){
			$respuesta=$this->db->query("
				select id_categoria,detalle from ral_categoria where cod_categoria ='MAT-CRE-04'and nombre_categoria='materia-curso' and estado = 'activo';
				");
			return $respuesta;
		}
		public function mostrar_horarios(){

			return $respuesta;
		}
	}
?>