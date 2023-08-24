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
		public function registrar_materia($curso,$nombre_materia,$precio,$usuario){
			$respuesta=$this->db->query("INSERT INTO aca_materia (tipo_materia,nombre_materia,precio,usu_creado,fec_creado,estado) VALUES ($curso,'$nombre_materia',$precio,'$usuario',now(),'activo');");
			return $respuesta;
		}
		public function lista_materias(){
			$respuesta=$this->db->query("
				SELECT row_number() over () as nro, 
					ac.id_materia,
					(select rc.detalle from ral_categoria rc where rc.id_categoria=ac.tipo_materia and rc.estado='activo')as tipo_materia,
					ac.nombre_materia,
					ac.precio 
				FROM aca_materia ac 
				WHERE ac.estado = 'activo' 
				order by ac.fec_creado desc;");
			return $respuesta->getResult();
		}
		public function mostrar_materia($id){
			$respuesta=$this->db->query("SELECT
					ac.id_materia,
					ac.tipo_materia as id_tipo_materia,
					(select rc.detalle from ral_categoria rc where rc.id_categoria=ac.tipo_materia and rc.estado='activo')as nombre_tipo_materia,
					ac.nombre_materia,
					ac.precio 
				FROM aca_materia ac 
				WHERE ac.estado = 'activo' 
				AND id_materia=$id;");
			return $respuesta->getResult();
		}
		public function modificar_materia($id,$curso,$nombre_materia,$precio,$usuario){
			$respuesta=$this->db->query("
				UPDATE aca_materia SET tipo_materia=$curso, nombre_materia='$nombre_materia',precio=$precio,usu_modificado='$usuario', fec_modificado=now() WHERE id_materia=$id
				");
			return $respuesta;
		}
		public function eliminar_materia($usuario,$id){
			$respuesta=$this->db->query("UPDATE aca_materia SET usu_modificado='$usuario', fec_modificado=now(), estado='inactivo' WHERE id_materia='$id' ");
		}
	}
?>