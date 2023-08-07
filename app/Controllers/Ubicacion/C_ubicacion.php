<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	namespace App\Controllers\Ubicacion;
	use App\Controllers\BaseController;
	use App\Models\ubicacion\M_ubicacion;

	class C_ubicacion extends BaseController{
		public function __construct(){
			$this->ubicacion = new M_ubicacion;
		}
		public function index(){
			$list=$this->ubicacion->listar_ubicacion();
			return view('ubicacion/V_ubicacion', ['list'=>$list]);
		}

		public function registrar_ubicacion(){
				if ($_SERVER['REQUEST_METHOD']==='POST') {
					$zona=$_POST['zona'];
					$direccion=$_POST['direccion'];
					$detalle=$_POST['detalle'];
					$descripcion=$_POST['descripcion'];
				}
				$respuesta=$this->ubicacion->agregar_ubicacion($zona,$direccion,$detalle,$descripcion);
				if ($respuesta) {
					$this->session->setFlashdata('exito','Registro Exitoso');
				}else{
					$this->session->setFlashdata('fracaso','error en el registro');
				}
				return redirect()->to(base_url('ubicacion'));
		}

		public function mostrar_ubicacion(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->ubicacion->mostrar_ubicacion($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}

		public function modificar_ubicacion(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$zona=$_POST['zona'];
				$direccion=$_POST['direccion'];
				$detalle=$_POST['detalle'];
				$descripcion=$_POST['descripcion'];
			}
			$respuesta=$this->ubicacion->modificar_ubicacion($id,$zona,$direccion,$detalle,$descripcion);
			if ($respuesta) {
				$this->session->setFlashData('exito','Modificacion Exitosa');
			}else{
				$this->session->setFlashData('fracaso','Error en la modificacion');
			}
			return redirect()->to(base_url('ubicacion'));
		}


		public function eliminar_ubicacion(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->ubicacion->eliminar_ubicacion($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>