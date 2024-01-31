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
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('3-7');
			$lista=$this->reinscripcion->lista_reinscripcion();
			$pagination=$this->pagination($lista);
			$data=[
				'lista_reinscripcion'=>$pagination['pagedResults'],
				'pager'=>$pagination['pager_links'],
				'menu_permisos'=>$menu_permisos,
				'title'=>'Reinscripciones'
			];
			return view("Estudiantes/V_reinscripcion",$data);
		}
	}
?>