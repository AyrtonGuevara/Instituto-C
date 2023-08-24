<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Controllers\Ambientes;
	use App\Models\Ambientes\M_clases;
	use App\Controllers\BaseController;
	class C_clases extends BaseController{
		public function __construct(){
			$this->clases=new M_clases();
		}
		public function index(){
			return view('Ambientes/V_clases');
		}
	}
?>