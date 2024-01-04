<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	namespace App\Controllers\Cursos;
	use App\Controllers\BaseController;
	use App\Models\Cursos\M_horarios;
	
	class C_horarios extends BaseController{
		public function __construct(){
			$this->horarios=new M_horarios();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			if(array_search('5-2',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_horarios=$this->horarios->listar_horarios();
			$lista_dia=$this->horarios->lista_dia();
			return view('Cursos/V_horarios',['dia'=>$lista_dia,'lista_horarios'=>$lista_horarios,'menu_permisos'=>$menu_permisos]);
		}
		public function registrar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$valores_horarios=array(
				'dias'=>$_POST['dia'],
				'hora_inicio'=>$_POST['horario_inicio'],
				'hora_fin'=> $_POST['horario_fin'],
				't_horario'=>'Regular');
			}
			$usuario=$this->session->get('id_usuario');
			$valores_horarios=json_encode($valores_horarios);
			$respuesta=$this->horarios->registrar_horarios($usuario,$valores_horarios);
			if ($respuesta[0]->success==='t') {
				$this->session->setFlashdata('exito','Registro Exitoso');
			}else{
				$this->session->setFlashdata('fracaso',$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url('horarios'));		
		}
		public function mostrar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->horarios->mostrar_horarios($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id_conf_horarios=$_POST['id'];
				$id_horario=$_POST['id_horario'];
				$dias=$_POST['dia'];
				$hora_inicio=$_POST['horario_inicio'];
				$hora_fin= $_POST['horario_fin'];	
			}
			$respuesta1=$this->horarios->mostrar_horarios($id_conf_horarios);
			//se recupera los ids de los horarios antes de la edicion y los eliminados para guardar los cambios
			$ids_originales=array();
			foreach ($respuesta1 as $key) {
				array_push($ids_originales, $key->id_horarios);
			}
			$ids_eliminados= array();
			foreach ($ids_originales as $value) {
			    if (!in_array($value, $id_horario)) {
			        $ids_eliminados[] = $value;
			    }
			}
			$array_json=array(
				'id_conf_horarios'=>$id_conf_horarios,
				'id_horario'=>$id_horario,
				'id_eliminado'=>$ids_eliminados,
				'dias'=>$dias,
				'hora_inicio'=>$hora_inicio,
				'hora_fin'=>$hora_fin
			);
			$usuario=$this->session->get('id_usuario');
			$valores_horarios=json_encode($array_json);
			$respuesta=$this->horarios->modificar_horarios($usuario,$valores_horarios);
			if ($respuesta[0]->success==='t') {
				$this->session->setFlashdata('exito','Registro Exitoso');
			}else{
				$this->session->setFlashdata('fracaso',$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url('horarios'));		
		}
		public function eliminar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->horarios->eliminar_horarios($id,$usuario);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>