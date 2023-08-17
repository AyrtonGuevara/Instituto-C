<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	namespace App\Models\ubicacion;

	use CodeIgniter\Model;

	class M_ubicacion extends Model{
		public function __constructs(){
			$this->db=db_connect();
		}

		public function agregar_ubicacion($usuario,$j_envio)
		{
			$respuesta=$this->db->query("SELECT * FROM public.fn_agregar_ubicacion('$usuario','$j_envio'::JSON);");

			return $respuesta->getResult();
			
			/*
			$res=$respuesta->getRow();
			return $res;*/
		}
		public function listar_ubicacion()
		{
			$respuesta=$this->db->query("SELECT * FROM adm_ubicacion WHERE estado='activo' ORDER BY id_ubicacion;");
			return $respuesta;
			//$respuesta=$->this->db->query('query');
			//return $respuesta->getResults();
		}
		public function mostrar_ubicacion($id)
		{
			$respuesta=$this->db->query("SELECT * FROM adm_ubicacion WHERE id_ubicacion=$id;");
			return $respuesta->getResult();
		}

		public function modificar_ubicacion($id,$zona,$direccion,$detalle,$descripcion){
			$respuesta=$this->db->query("UPDATE adm_ubicacion SET zona='$zona',direccion='$direccion',detalle ='$detalle',descripcion='$descripcion' WHERE id_ubicacion=$id;");
			return $respuesta;
		}

		public function eliminar_ubicacion($id)
		{
			$respuesta=$this->db->query("DELETE FROM adm_ubicacion WHERE id_ubicacion=$id");
			return $respuesta;
		}
	}
?>