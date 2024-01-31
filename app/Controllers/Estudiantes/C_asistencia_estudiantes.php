<?php
/*
	Ayrton Jhonny Guevara Montaño 13-10-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_asistencia_estudiantes;
	
	class C_asistencia_estudiantes extends BaseController{
		public function __construct(){
			$this->asistencia_estudiantes=new M_asistencia_estudiantes();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('3-3');
			$data=[
				'menu_permisos'=>$menu_permisos,
				'title'=>'Asistencia Estudiante'
			];
			return view("Estudiantes/V_asistencia_estudiantes",$data);
		}
		public function clases_activas(){
			$respuesta=$this->asistencia_estudiantes->clases_activas();
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function registrar_asistencia(){
			if ($_SERVER['REQUEST_METHOD']==="POST") {
				$nombre=$_POST['nombre'];
				$valor=$_POST['valor'];
			}
			$respuesta=$this->asistencia_estudiantes->registrar_asistencia($nombre,$valor);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function asistencias_guardadas(){
			if ($_SERVER['REQUEST_METHOD']==='POST'){
				$id=$_POST['id_clase'];
			}
			$respuesta=$this->asistencia_estudiantes->asistencias_guardadas($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>