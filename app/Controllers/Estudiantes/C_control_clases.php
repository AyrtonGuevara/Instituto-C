<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Models\Estudiantes\M_control_clases;
	use App\Controllers\BaseController;
	use App\Controllers\Security\sesion;

	class C_control_clases extends BaseController{
		public function __construct(){
			$this->control_clases=new M_control_clases();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(3,2,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_aula=$this->control_clases->lista_aulas();
			return view('Estudiantes/V_control_clases',['lista_aulas'=>$lista_aula]);
		}
		public function cronograma_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->control_clases->cronograma_clases($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function mostrar_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->control_clases->mostrar_clases($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function lista_estudiantes(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->control_clases->lista_estudiantes($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>