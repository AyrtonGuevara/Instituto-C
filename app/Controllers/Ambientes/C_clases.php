<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Controllers\Ambientes;
	use App\Models\Ambientes\M_clases;
	use App\Controllers\BaseController;
	class C_clases extends BaseController{
		public function __construct(){
			$this->clases=new M_clases();
		}
		public function index(){
			$lista_materia=$this->clases->lista_materias();
			$lista_horario=$this->clases->lista_horarios();
			$lista_aula=$this->clases->lista_aulas();
			$lista_personal=$this->clases->lista_docentes();
			return view('Ambientes/V_clases',['lista_materia'=>$lista_materia,'lista_horarios'=>$lista_horario,'lista_aulas'=>$lista_aula,'lista_personal'=>$lista_personal]);
		}
		public function registrar_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$valores_clases=array(
				'id_materia'=>$_POST['materia'],
				'id_horario'=>$_POST['horario'],
				'id_aula'=>$_POST['aula'],
				'id_persona'=>$_POST['personal'],
				);
			}
			$usuario=$this->session->get('id_usuario');
			$valores_clases=json_encode($valores_clases);
			$respuesta=$this->clases->registrar_clase($usuario,$valores_clases);
			print_r($respuesta);
			if ($respuesta[0]->success==='t') {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso',$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url('clases'));
		}
		public function cronograma_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->clases->cronograma_clases($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function mostrar_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->clases->mostrar_clases($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$materia=$_POST['materia'];
				$horario=$_POST['horario'];
				$aula=$_POST['aula'];
				$personal=$_POST['personal'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->clases->modificar_clase($id,$materia,$horario,$aula,$personal,$usuario);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('clases'));
		}
		public function eliminar_clases(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->clases->eliminar_clases($id,$usuario);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function lista_estudiantes(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->clases->lista_estudiantes($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>