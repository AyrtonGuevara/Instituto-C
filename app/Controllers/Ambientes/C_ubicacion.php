<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	namespace App\Controllers\Ambientes;
	use App\Controllers\BaseController;
	use App\Models\Ambientes\M_ubicacion;

	class C_ubicacion extends BaseController{
		public function __construct(){
			$this->ubicacion = new M_ubicacion;
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('2-1');
			//obteniendo resultados de la consulta
			$lista=$this->ubicacion->listar_ubicacion();
			$paginacion=$this->pagination($lista);
			$data=[
				'menu_permisos'=>$menu_permisos,
				'lista'=>$paginacion['pagedResults'],
				'pager'=>$paginacion['pager_links'],
				'title'=>'Ubicacion'
			];
			return view('Ambientes/V_ubicacion', $data);
		}

		public function registrar_ubicacion(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$valores_ubicacion=array(
				'zona'=>$_POST['zona'],
				'direccion'=>$_POST['direccion'],
				'detalle'=>$_POST['detalle'],
				'descripcion'=>$_POST['descripcion'],
				'nombre_aula'=>$_POST['nombre_aula'],
				'detalle_aula'=>$_POST['detalle_aula'],
				);
			}
				$usuario=$this->session->get('id_usuario');
				$valores_ubicacion=json_encode($valores_ubicacion);
				$respuesta=$this->ubicacion->agregar_ubicacion($usuario,$valores_ubicacion);
				
			if ($respuesta[0]->success==='t') {
				$this->session->setFlashdata('exito','Registro Exitoso');
			}else{
				$this->session->setFlashdata('fracaso',$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url('ambientes'));
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
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->ubicacion->modificar_ubicacion($usuario,$id,$zona,$direccion,$detalle,$descripcion);
			if ($respuesta) {
				$this->session->setFlashData('exito','Modificacion Exitosa');
			}else{
				$this->session->setFlashData('fracaso','Error en la modificacion');
			}
			return redirect()->to(base_url('ambientes'));
		}


		public function eliminar_ubicacion(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$cascada=$this->ubicacion->cascada_ubicacion($id);

			if ($cascada[0]->column==='f'){
				$usuario=$this->session->get('id_usuario');
				$respuesta=$this->ubicacion->eliminar_ubicacion($usuario,$id);
				echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
			}else{
				echo json_encode($resp=array('success'=>false,'data'=>'Existen datos relacionados con esta informacion. Asegurese de borrar los servicios dependientes de la misma'));
			}
		}
		public function modal_mostrar_aulas(){
			if($_SERVER['REQUEST_METHOD']==='POST'){
				$id=$_POST['id'];
			}
			$respuesta=$this->ubicacion->modal_mostrar_aulas($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}

		public function modal_editar_aulas(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id_ubicacion=$_POST['id_ubicacion'];
				$id_aula=$_POST['id_aula'];
				$nombre_aula=$_POST['modal_nombre_aula'];
				$detalle_aula=$_POST['modal_detalle_aula'];
			}
			$respuesta1=$this->ubicacion->modal_mostrar_aulas($id_ubicacion);
			$ids_originales=array();
			foreach ($respuesta1 as $key) {
				array_push($ids_originales, $key->id_aula);
			}
			$ids_eliminados= array();
			foreach ($ids_originales as $value) {
			    if (!in_array($value, $id_aula)) {
			        $ids_eliminados[] = $value;
			    }
			}
			$array_json=array(
				'id_ubicacion'=>$id_ubicacion,
				'id_aula'=>$id_aula,
				'ids_eliminados'=>$ids_eliminados,
				'nombre_aula'=>$nombre_aula,
				'detalle_aula'=>$detalle_aula);
			$json_modificar_aula=json_encode($array_json);
			$usuario=$this->session->get('id_usuario');
			$respuesta2=$this->ubicacion->modal_modificar_aulas($json_modificar_aula,$usuario);
			if ($respuesta2[0]->success) {
				$this->session->setFlashData('exito','Modificacion Exitosa');
			}else{
				$this->session->setFlashData('fracaso',$respuesta2[0]->mensaje);
			}
			return redirect()->to(base_url('ambientes'));
		}
	}
?>