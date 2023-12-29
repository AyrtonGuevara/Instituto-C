<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_lista_estudiantes;
	use App\Controllers\Security\sesion;

	class C_lista_estudiantes extends BaseController{
		public function __construct(){
			$this->lista_estudiantes=new M_lista_estudiantes();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(3,5,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			$lista=$this->lista_estudiantes->lista_estudiantes();
			return view("Estudiantes/V_lista_estudiantes",["lista"=>$lista]);
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