<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_permisos;

	class C_permisos extends BaseController{
		public function __construct(){
			$this->permisos=new M_permisos();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			if(array_search('3-6',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_permisos=$this->permisos->lista_permisos();
			return view("Estudiantes/V_permisos",["lista_permisos"=>$lista_permisos,'menu_permisos'=>$menu_permisos]);
		}
		public function autocompletar_estudiantes(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$request=$_POST['request'];
			}
			$respuesta=$this->permisos->autocompletar_estudiantes($request);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function buscar_clase(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$nombre=$_POST['nombre'];
			}
			$respuesta=$this->permisos->buscar_clase($nombre);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function buscar_clase_reemplazo(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$fecha=$_POST['fecha'];
			}
			$respuesta=$this->permisos->buscar_clase_reemplazo($fecha,$id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function registrar_permiso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$nombre=$_POST['nombre'];
				$f_permiso=$_POST['f_permiso'];
				$f_reemplazo=$_POST['f_reemplazo'];
				$clase_remp=$_POST['clase_remp'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->permisos->registrar_permiso($nombre,$f_permiso,$f_reemplazo,$usuario,$clase_remp,0);
			if ($respuesta[0]->success=='t') {
				$this->session->setFlashdata("exito","Se registro al estudiante con exito");
			}else{
				$this->session->setFlashdata("fracaso",$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url("permisos"));
		}
		public function mostrar_permiso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->permisos->mostrar_permiso($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
		public function editar_permiso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$nombre=$_POST['nombre'];
				$f_permiso=$_POST['f_permiso'];
				$f_reemplazo=$_POST['f_reemplazo'];
				$clase_remp=$_POST['clase_remp'];
				$id=$_POST['id'];
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->permisos->registrar_permiso($nombre,$f_permiso,$f_reemplazo,$usuario,$clase_remp,$id);
			if ($respuesta[0]->success=='t') {
				$this->session->setFlashdata("exito","Se registro al estudiante con exito");
			}else{
				$this->session->setFlashdata("fracaso",$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url("permisos"));
		}
		public function eliminar_permiso(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->permisos->eliminar_permiso($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}
	}
?>