<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	namespace App\Models\Configuracion;
	use CodeIgniter\Model;

	class M_categorias extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function buscar_categorias($categoria){
			$respuesta=$this->db->query("
				select row_number()over() as nro, id_categoria, nombre_categoria, detalle 
				from ral_categoria 
				where nombre_categoria ilike '$categoria'
				and estado='activo';
			");
			return $respuesta->getResult();
		}
		public function buscar_nivel(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, id_cargo as id_categoria,'nivel_sistema' as nombre_categoria,cargo as detalle 
				from adm_cargo
				where estado='activo';
			");
			return $respuesta->getResult();
		}
		public function registrar_nivel($input){
			$respuesta=$this->db->query("
				insert into adm_cargo (cargo,estado) values ('$input','activo');
			");
			return $respuesta;
		}
		public function registrar_categorias($select_categorias,$input){
			$respuesta=$this->db->query("
				insert into ral_categoria (cod_categoria,tipo,nombre_categoria,detalle,estado) 
				values ((select cod_categoria from ral_categoria where nombre_categoria ilike '$select_categorias' limit 1),(select tipo from ral_categoria where nombre_categoria ilike '$select_categorias' limit 1),'$select_categorias','$input','activo');
			");
			return $respuesta;
		}
		public function eliminar_nivel($id){
			$respuesta=$this->db->query("
				update adm_cargo set estado='inactivo' where id_cargo='$id';
				");
			return $respuesta;
		}

		public function eliminar_categorias($id){
			$respuesta=$this->db->query("
				update ral_categoria set estado='inactivo' where id_categoria='$id';
				");
			return $respuesta;
		}
	}
?>