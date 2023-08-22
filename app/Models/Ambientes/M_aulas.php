<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023
*/
	namespace App\Models\ubicacion;
	use CodeIgniter\Model;

	class M_aulas extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_aulas(){
			$respuesta=$this->db->query("
				select aa.id_aula, au.direccion, aa.nombre_aula, aa.descripcion from aca_aula aa, adm_ubicacion au where aa.id_ubicacion=au.id_ubicacion and aa.estado = 'activo' and au.estado = 'activo';
				");
			return $respuesta;
		}
		public function listar_ubicaciones(){
			$respuesta=$this->db->query("SELECT id_ubicacion, direccion FROM adm_ubicacion where estado = 'activo'");
			return $respuesta;
		}
		public function registrar_aula($ubicacion,$descripcion,$nombre){
			$respuesta=$this->db->query("
				INSERT INTO aca_aula (id_ubicacion,descripcion,nombre_aula,estado) VALUES ($ubicacion,'$descripcion','$nombre','activo');
			");
			return $respuesta;
		}
		public function mostrar_aulas($id){
			$respuesta=$this->db->query("select aa.id_aula, aa.id_ubicacion, au.direccion, aa.nombre_aula, aa.descripcion from aca_aula aa , adm_ubicacion au where aa.id_ubicacion=au.id_ubicacion and aa.estado = 'activo' and au.estado = 'activo' and aa.id_aula=$id;");
			return $respuesta->getResult();
		}
		public function modificar_aula($id,$ubicacion,$descripcion,$nombre){
			$respuesta=$this->db->query("UPDATE aca_aula SET id_ubicacion = $ubicacion, descripcion='$descripcion', nombre_aula= '$nombre' WHERE id_aula=$id ");
			return $respuesta;
		}
		public function eliminar_aulas($id){
			$respuesta=$this->db->query("UPDATE aca_aula SET estado='inactivo' WHERE id_aula=$id");
			return $respuesta;
		}
	}
?>