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
			if(array_search('3-5',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			$lista=$this->lista_estudiantes->lista_estudiantes();
			return view("Estudiantes/V_lista_estudiantes",["lista"=>$lista,'menu_permisos'=>$menu_permisos]);
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