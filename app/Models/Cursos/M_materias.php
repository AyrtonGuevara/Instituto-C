<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Models\Cursos;
	use CodeIgniter\Model;
	class M_materias extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_cursos(){
			$respuesta=$this->db->query("SELECT id_categoria, detalle FROM ral_categoria WHERE cod_categoria='CUR-CRE-04' AND tipo='curso' AND nombre_categoria='materia-curso' AND estado='activo';");
			return $respuesta->getResult();
		}
		public function registrar_materia($curso,$nombre_materia,$detalle_materia,$precio,$usuario){
			$respuesta=$this->db->query("SELECT * FROM public.fn_agregar_materia($curso,'$nombre_materia','$detalle_materia',$precio,'$usuario')");
			return $respuesta;
		}
		public function lista_materias(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, cp.id_precios, rc.detalle as curso , am.nombre_materia ,cp.detalle, cp.precio
				from aca_materia am, com_precios cp, ral_categoria rc 
				where am.id_materia = cp.id_materia 
				and am.tipo_materia = rc.id_categoria 
				and am.estado ='activo' 
				and cp.estado='activo'
				and rc.estado='activo';");
			return $respuesta->getResult();
		}
		public function mostrar_materia($id){
			$respuesta=$this->db->query("
				select cp.id_precios, rc.id_categoria,rc.detalle as nombre_curso, am.nombre_materia , cp.detalle, cp.precio
				from aca_materia am , com_precios cp, ral_categoria rc
				where am.id_materia = cp.id_materia
				and am.tipo_materia = rc.id_categoria 
				and am.estado = 'activo'
				and cp.estado = 'activo'
				and rc.estado = 'activo'
				and cp.id_precios = $id;
				");
			return $respuesta->getResult();
		}
		public function modificar_materia($id,$detalle_materia,$precio,$usuario){
			$respuesta=$this->db->query("
				UPDATE com_precios SET detalle='$detalle_materia',precio=$precio,usu_modificado='$usuario', fec_modificado=now() WHERE id_precios=$id;
				");
			return $respuesta;
		}
		public function eliminar_materia($id){
			$respuesta=$this->db->query("UPDATE com_precios SET estado='inactivo' WHERE id_precios='$id' ");
		}
		public function autocompletar_materia($nombre_materia){
			$respuesta=$this->db->query("
				select nombre_materia 
				from aca_materia 
				where estado='activo'
				and nombre_materia ilike '%$nombre_materia%';
				");
			return $respuesta->getResult();
		}
	}
?>