<?php
	namespace App\Controllers\Categoria;
	use App\Controllers\BaseController;

	class categorias extends BaseController{
		public function index(){
			/*
			$data['menu']=this->load->view('Template/Head');
			$data['menu']=this->load->view('Template/Foot');
			return view('categoria/categoria');	
			return view('Template/Head').view('categoria/categoria').view('Template/Foot');
			$data=array('Head'=>view('Template/Head'));
			$data=array('Body'=>view('categoria/categoria'));
			$data=array('Foot'=>view('Template/Foot'));*/
			return view('categoria/categoria');
		}
	}
?>