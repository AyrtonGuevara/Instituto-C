<?php
/*
	Ayrton Jhonny Guevara Montaño 15-12-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Models\Estudiantes\M_reinscripcion;
	use App\Controllers\BaseController;

	class C_reinscripcion extends BaseController{
		public function __construct(){
			$this->reinscripcion=new M_reinscripcion();
		}
		public function index(){
			$lista_reinscripcion=$this->reinscripcion->lista_reinscripcion();
			return view("Estudiantes/V_reinscripcion",['lista_reinscripcion'=>$lista_reinscripcion]);
		}
	}
?>