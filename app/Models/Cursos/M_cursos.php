<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Models\Cursos;
	use CodeIgniter\Model;

	class M_cursos extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function listar_cursos(){
			$respuesta=$this->db->query("select row_number()over() as nro, id_categoria,detalle from ral_categoria where cod_categoria='CUR-CRE-04' and estado = 'activo';");
			return $respuesta->getResult();
		}
		public function registrar_curso($curso){
			$respuesta=$this->db->query("insert into ral_categoria (cod_categoria,tipo,nombre_categoria,detalle,estado) values ('CUR-CRE-04','curso','materia-curso','$curso','activo');");
			return $respuesta;
		}
		public function mostrar_curso($id){
			$respuesta=$this->db->query("select id_categoria,detalle from ral_categoria where cod_categoria='CUR-CRE-04' and estado = 'activo' and id_categoria=$id;");
			return $respuesta->getResult();
		}
		public function modificar_curso($id,$curso){
			$respuesta=$this->db->query("update ral_categoria set detalle='$curso' where id_categoria=$id and estado='activo'; ");
			return $respuesta;
		}
		public function eliminar_curso($id){
			$respuesta=$this->db->query("update ral_categoria set estado='inactivo' where id_categoria=$id and estado='activo';");
			return $respuesta;
		}
	}
?>