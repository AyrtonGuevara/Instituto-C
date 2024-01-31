<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	namespace App\Controllers\Personal;
	use App\Controllers\BaseController;
	use App\Models\Personal\M_personal;

	class C_personal extends BaseController{
		public function __construct(){
			$this->personal=new M_personal();
		}
		public function index(){
			$menu_permisos=session('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('5-1');
			//obteniendo resultados de la cunsulta
        	$lista = $this->personal->listar_personal();
			$paginacion=$this->pagination($lista);
			$data=[
				'menu_permisos'=>$menu_permisos,
				'cargos'=>$this->personal->listar_cargos(),
				'lista'=>$paginacion['pagedResults'],
				'pager'=>$paginacion['pager_links'],
				'title'=>'Personal'
			];
			return view('Personal/V_personal', $data);
		}
		public function registrar_personal(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$nombre=$_POST['nombre'];
				$apellidoP=$_POST['apellidoP'];
				$apellidoM=$_POST['apellidoM'];
				$fecnac=$_POST['fecnac'];
				$celular=$_POST['celular'];
				$cargo=$_POST['cargo'];
			}
			$respuesta=$this->personal->registrar_personal($nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo);
			if ($respuesta) {
				$this->session->setFlashData('exito','Registro exitoso');
			}else{
				$this->session->setFlashData('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('personal'));
		}
		public function mostrar_personal(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->personal->mostrar_personal($id);
			echo json_encode($resp=array("success"=>true,'data'=>$respuesta));
		}
		public function modificar_personal(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$nombre=$_POST['nombre'];
				$apellidoP=$_POST['apellidoP'];
				$apellidoM=$_POST['apellidoM'];
				$fecnac=$_POST['fecnac'];
				$celular=$_POST['celular'];
				$cargo=$_POST['cargo'];
			}
			$respuesta=$this->personal->modificar_personal($id,$nombre,$apellidoP,$apellidoM,$fecnac,$celular,$cargo);
			if ($respuesta) {
				$this->session->setFlashData('exito','Modificacion exitosa');
			}else{
				$this->session->setFlashData('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('personal'));	
		}
		public function eliminar_personal(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->personal->eliminar_personal($id);
			echo json_encode($res=array('success'=>true,'data'=>$respuesta));
		}
	}
?>