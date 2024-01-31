<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	namespace App\Models\Configuracion;
	use CodeIgniter\Model;

	class M_permisos extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_niveles(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, id_cargo, cargo 
				from adm_cargo ac
				where ac.estado='activo';
			");
			return $respuesta->getResult();
		}
		public function lista_paginas(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, id_paginas, nombre_pagina, codigo_modulo, codigo_submodulo
				from adm_paginas
				where estado='activo';
			");
			return $respuesta->getResult();
		}
		public function permisos_usuario($id){
			$respuesta=$this->db->query("
				select ap.id_paginas, ac.cargo, ap.codigo_modulo, ap.codigo_submodulo
				from adm_paginas ap, adm_cargo ac
				where ap.estado='activo'
				and ac.estado='activo'
				and ac.id_cargo=$id
				and ap.id_paginas=any(ac.permisos);
			");
			return $respuesta->getResult();
		}
		public function modificar_permisos($id,$array_permisos){
			$respuesta=$this->db->query("
				update adm_cargo
				set permisos = '$array_permisos'
				where id_cargo=$id;
			");
			return $respuesta;
		}
	}
?>