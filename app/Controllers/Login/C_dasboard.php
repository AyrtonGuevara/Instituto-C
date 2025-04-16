<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	namespace App\Controllers\Login;
	use App\Controllers\BaseController;
	use App\Models\Login\M_dasboard;

	class C_dasboard extends BaseController{
		public function __construct(){
			$this->dasboard=new M_dasboard();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			$fecha_sistema=$this->session->get('fecha');
			$data=[
				'menu_permisos'=>$menu_permisos,
				'fecha_sistema'=>$fecha_sistema,
				'title'=>'Inicio'
			];
			return view('Login/V_dasboard',$data);
		}
		public function funcion_inicio(){
			$respuesta=$this->dasboard->funcion_inicio();
			//fecha, mensaje, success
			echo json_encode($resp=array('success'=>$respuesta[0]->success,'mensaje'=>$respuesta[0]->mensaje,'fecha'=>$respuesta[0]->v2_fecha_actual));
		}
	}
?>