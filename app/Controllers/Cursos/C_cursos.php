<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Controllers\Cursos;
	use App\Controllers\BaseController;
	use App\Models\Cursos\M_cursos;

	class C_cursos extends BaseController{
		public function __construct(){
			$this->cursos=new M_cursos();
		}
		public function index(){
			$lista_cursos=$this->cursos->listar_cursos();
			return view('Cursos/V_cursos',['lista_cursos'=>$lista_cursos]);
		}
		public function registrar_curso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$curso=$_POST['nombre_curso'];
			}
			$respuesta=$this->cursos->registrar_curso($curso);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registron exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('cursos'));
		}
		public function mostrar_curso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->cursos->mostrar_curso($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_curso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$curso=$_POST['nombre_curso'];
			}
			$respuesta=$this->cursos->modificar_curso($id,$curso);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registron exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('cursos'));
		}public function eliminar_curso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->cursos->eliminar_curso($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>
