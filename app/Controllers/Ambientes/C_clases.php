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
				$materia=$_POST['materia'];
				$horario=$_POST['horario'];
				$aula=$_POST['aula'];
				$personal=$_POST['personal'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->clases->registrar_clase($materia,$horario,$aula,$personal,$usuario);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
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
	}
?>