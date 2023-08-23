<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Controllers\Cursos;
	use App\Controllers\BaseController;
	use App\Models\Cursos\M_horarios;

	class C_horarios extends BaseController{
		public function __construct(){
			$this->horarios=new M_horarios();
		}
		public function index(){
			//$lista_horarios=$this->horarios->listar_horarios();
			//return view('Cursos/V_horarios',['lista_horarios'=>$lista_horarios]);
			$lista_dia=$this->horarios->lista_dia();
			return view('Cursos/V_horarios',['dia'=>$lista_dia]);
		}
	}
?>