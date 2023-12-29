<?php
/*
	Ayrton Jhonny Guevara Montaño 15-12-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Models\Estudiantes\M_reinscripcion;
	use App\Controllers\BaseController;
	use App\Controllers\Security\sesion;

	class C_reinscripcion extends BaseController{
		public function __construct(){
			$this->reinscripcion=new M_reinscripcion();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(3,7,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_reinscripcion=$this->reinscripcion->lista_reinscripcion();
			return view("Estudiantes/V_reinscripcion",['lista_reinscripcion'=>$lista_reinscripcion]);
		}
	}
?>