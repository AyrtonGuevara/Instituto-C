<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	namespace App\Models\Security;
	use CodeIgniter\Model;

	class M_seguridad extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function comprobar_modulo($mod,$submod,$lvl_usu){
			$respuesta=$this->db->query("
				select exists(select ac.id_cargo
				from adm_cargo ac,
				(select id_paginas 
					from adm_paginas 
					where codigo_modulo=$mod
					and codigo_submodulo=$submod
					and estado='activo')as id_pag
				where id_pag.id_paginas=any(ac.permisos)
				and cargo='$lvl_usu'
				and ac.estado='activo') as t1
				;
				");
			return $respuesta->getResult();
		}
	}
?>