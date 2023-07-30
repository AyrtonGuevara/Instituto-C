<?php
	namespace App\Controllers\Categoria;
	use App\Controllers\BaseController;
	class C_categorias extends BaseController{
		public function index(){
			return view('categoria/categoria');
		}
	}
?>