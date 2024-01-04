<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	namespace App\Controllers\Configuracion;
	use App\Models\Configuracion\M_permisos;
	use App\Controllers\BaseController;

	class C_permisos extends BaseController{
		public function __construct(){
			$this->permisos=new M_permisos();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			if(array_search('6-1',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			return view("Configuracion/V_permisos",['menu_permisos'=>$menu_permisos]);
		}
	}
?>