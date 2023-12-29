<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	namespace App\Controllers\Configuracion;
	use App\Models\Configuracion\M_permisos;
	use App\Controllers\BaseController;
	use App\Controllers\Security\sesion;

	class C_permisos extends BaseController{
		public function __construct(){
			$this->permisos=new M_permisos();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(6,1,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			return view("Configuracion/V_permisos");
		}
	}
?>