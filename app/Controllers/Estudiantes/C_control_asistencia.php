<?php
/*
	Ayrton Jhonny Guevara Montaño 28-10-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_control_asistencia;
	use App\Controllers\Security\sesion;

	class C_control_asistencia extends BaseController{
		public function __construct(){
			$this->control_asistencia=new M_control_asistencia();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(3,4,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_cursos=$this->control_asistencia->lista_cursos();
			return view('Estudiantes/V_control_asistencia',['lista_cursos'=>$lista_cursos]);
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