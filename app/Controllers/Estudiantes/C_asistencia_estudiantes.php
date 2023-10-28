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
			return view("Estudiantes/V_asistencia_estudiantes");
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