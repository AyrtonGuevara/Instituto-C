<?php
/*
	Ayrton Jhonny Guevara Montaño 04-09-2023	
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_estudiantes;

	class C_estudiantes extends BaseController{
		public function __construct(){
			$this->estudiantes=new M_estudiantes();
		}
		public function index(){
			return view('Estudiantes/V_estudiantes');
		}

	}
?>