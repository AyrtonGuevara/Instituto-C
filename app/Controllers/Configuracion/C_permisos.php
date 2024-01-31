<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	namespace App\Controllers\Configuracion;
	use App\Models\Configuracion\M_permisos;
	use App\Controllers\BaseController;

	class C_permisos extends BaseController{
		public function __construct(){
			$this->permisos=new M_permisos();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			if(array_search('6-1',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_niveles=$this->permisos->lista_niveles();
			$lista_paginas=$this->permisos->lista_paginas();
			return view("Configuracion/V_permisos",['menu_permisos'=>$menu_permisos,'lista_niveles'=>$lista_niveles,'lista_paginas'=>$lista_paginas]);
		}
		public function permisos_usuario(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->permisos->permisos_usuario($id);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function modificar_permisos(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$array_permisos=$_POST['permisos'];
			}
			$array='{';
			foreach($array_permisos as $key){
				$array=$array.$key.',';
			}
			$array = substr($array, 0, -1);
			$array=$array.'}';
			echo $array;
			$respuesta=$this->permisos->modificar_permisos($id,$array);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('conf_permisos'));
		}
	}
?>