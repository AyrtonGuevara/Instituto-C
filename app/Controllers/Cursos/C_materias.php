<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Controllers\Cursos;
	use App\Controllers\BaseController;
	use App\Models\Cursos\M_materias;

	class C_materias extends BaseController{
		public function __construct(){
			$this->materias=new M_materias();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('1-3');
			//obteniendo paginacion
			$lista=$this->materias->lista_materias();
			$paginacion=$this->pagination($lista);
			$data=[
				'lista_cursos'=>$this->materias->lista_cursos(),
				'menu_permisos'=>$menu_permisos,
				'lista_materias'=>$paginacion['pagedResults'],
				'pager'=>$paginacion['pager_links'],
				'title'=>'Materias'
			];
			return view('Cursos/V_materias',$data);
		}
		public function registrar_materias(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$curso=$_POST['curso'];
				$nombre_materia=$_POST['nombre_materia'];
				$detalle_materia=$_POST['detalle_materia'];
				$precio=$_POST['precio'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->materias->registrar_materia($curso,$nombre_materia,$detalle_materia,$precio,$usuario);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('materias'));
		}
		public function mostrar_materia(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->materias->mostrar_materia($id);
			echo json_encode($resp=array('success'=> true ,'data'=>$respuesta));
		}
		public function modificar_materia(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$curso=$_POST['curso'];
				$nombre_materia=$_POST['nombre_materia'];
				$precio=$_POST['precio'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->materias->modificar_materia($id,$curso,$nombre_materia,$precio,$usuario);
			if ($respuesta) {
				$this->session->setFlashdata('exito','Registro exitoso');
			}else{
				$this->session->setFlashdata('fracaso','Error en el registro');
			}
			return redirect()->to(base_url('materias'));
		}
		public function eliminar_materia(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->materias->eliminar_materia($id);
			echo json_encode($resp=array('success'=> true ,'data'=>$respuesta));
		}
		public function autocompletar_materia(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$request=$_POST['request'];
			}
			$respuesta=$this->materias->autocompletar_materia($request);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>