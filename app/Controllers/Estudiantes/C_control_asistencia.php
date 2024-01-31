<?php
/*
	Ayrton Jhonny Guevara Montaño 28-10-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_control_asistencia;

	class C_control_asistencia extends BaseController{
		public function __construct(){
			$this->control_asistencia=new M_control_asistencia();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('3-4');
			$data=[
				'lista_cursos'=>$this->control_asistencia->lista_cursos(),
				'menu_permisos'=>$menu_permisos,
				'title'=>'Control Asistencia'
			];
			return view('Estudiantes/V_control_asistencia',$data);
		}
		public function buscar_fechas_clase(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$comp_mes=$_POST['comp_mes'];
			}
			$respuesta=$this->control_asistencia->buscar_fechas_clase($id,$comp_mes);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function buscar_estudiantes_asistencias(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$comp_mes=$_POST['comp_mes'];
			}
			$respuesta=$this->control_asistencia->buscar_estudiantes_asistencias($id,$comp_mes);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function lista_clases_aula(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->control_asistencia->lista_clases_aula($id);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
	}
?>