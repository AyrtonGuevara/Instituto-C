<?php
	namespace App\Controllers\Ubicacion;
	use App\Controllers\BaseController;
	use App\Models\ubicacion\M_ubicacion;

	class C_ubicacion extends BaseController{
		public function __construct(){
			$this->ubicacion = new M_ubicacion;
		}
		public function index(){
			return view('ubicacion/V_ubicacion');
		}
		//se registra una nueva ubicacion
		public function registrar_ubicacion(){
				$zona=$_POST['zona'];
				$direccion=$_POST['direccion'];
				$detalle=$_POST['detalle'];
				$descripcion=$_POST['descripcion'];

				$respuesta=$this->ubicacion->agregar_ubicacion($zona,$direccion,$detalle,$descripcion);
				//echo $zona.$direccion.$detalle.$descripcion;

				return redirect()->to(base_url('ubicacion'));
			
		}
	}
?>