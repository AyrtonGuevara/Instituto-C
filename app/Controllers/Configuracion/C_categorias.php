<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	namespace App\Controllers\Configuracion;
	use App\Controllers\BaseController;
	use App\Models\Configuracion\M_categorias;

	class C_categorias extends BaseController{
		public function __construct(){
			$this->categorias=new M_categorias();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			if(array_search('6-2',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			return view("Configuracion/V_categorias",['menu_permisos'=>$menu_permisos]);
		}
		public function buscar_categorias(){
			if ($_SERVER['REQUEST_METHOD']==="POST") {
				$categoria=$_POST['categoria'];
			}
			if ($categoria==='nivel-sistema') {
				$respuesta=$this->categorias->buscar_nivel();
			}else{
				$respuesta=$this->categorias->buscar_categorias($categoria);
			}
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function registrar_categorias(){
			if ($_SERVER['REQUEST_METHOD']==="POST") {
				$select_categorias=$_POST['select_categorias'];
				$input=$_POST['input'];
			}
			if ($select_categorias==='nivel-sistema') {
				$respuesta=$this->categorias->registrar_nivel($input);
			}else{
				$respuesta=$this->categorias->registrar_categorias($select_categorias,$input);
			}
			return redirect()->to(base_url('categorias'));
		}
		public function eliminar_categorias(){
			if ($_SERVER['REQUEST_METHOD']==="POST") {
				$id=$_POST['id'];
				$nombre_categoria=$_POST['nombre_categoria'];
			}
			if ($nombre_categoria==='nivel_sistema') {
				$respuesta=$this->categorias->eliminar_nivel($id);
			}else{
				$respuesta=$this->categorias->eliminar_categorias($id);
			}
			echo json_encode($resp=array("success"=>true));
		}
	}
?>