<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023
*/
	namespace App\Controllers\Ubicacion;
	use App\Controllers\BaseController;
	use App\Models\ubicacion\M_aulas;

	class C_aulas extends BaseController{
		public function __construct(){
			$this->aulas=new M_aulas;
		}
		public function index(){
			$listaAulas=$this->aulas->listar_aulas();
			$listaUbicaciones=$this->aulas->listar_ubicaciones();
			return view("ubicacion/V_aulas",['aulas'=>$listaAulas,'ubicaciones'=>$listaUbicaciones]);
		}
		public function registrar_aula(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$ubicacion=$_POST['ubicacion'];
				$descripcion=$_POST['descripcion'];
				$nombre=$_POST['nombre'];
			}
			$respuesta=$this->aulas->registrar_aula($ubicacion,$descripcion,$nombre);
			if ($respuesta) {
				$this->session->setFlashData('exito','Se realizo la consula');
			}else{
				$this->session->setFlashdata('fracaso','error en el registro');
			}
			return redirect()->to(base_url('aula'));
		}
		public function mostrar_aula(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->aulas->mostrar_aulas($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_aula(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$ubicacion=$_POST['ubicacion'];
				$descripcion=$_POST['descripcion'];
				$nombre=$_POST['nombre'];
			}
			$respuesta=$this->aulas->modificar_aula($id,$ubicacion,$descripcion,$nombre);
			if ($respuesta) {
				$this->session->setFlashData('exito','Se realizo la consula');
			}else{
				$this->session->setFlashdata('fracaso','error en el registro');
			}
			return redirect()->to(base_url('aula'));
		}
		public function eliminar_aula(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->aulas->eliminar_aulas($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>