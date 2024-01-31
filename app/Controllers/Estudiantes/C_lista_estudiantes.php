<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_lista_estudiantes;

	class C_lista_estudiantes extends BaseController{
		public function __construct(){
			$this->lista_estudiantes=new M_lista_estudiantes();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('3-5');
			$lista=$this->lista_estudiantes->lista_estudiantes();
			$pagination=$this->pagination($lista);
			$data=[
				'lista'=>$pagination['pagedResults'],
				'pager'=>$pagination['pager_links'],
				'menu_permisos'=>$menu_permisos,
				'title'=>'Lista Estudiantes'
			];
			
			return view("Estudiantes/V_lista_estudiantes",$data);
		}
		public function ver_estudiante(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$cont=$_POST['cont'];
			}
			$this->session->setFlashdata('ver_estudiante',[$id,$cont]);
		}
	}
?>