<?php
/*
	Ayrton Jhonny Guevara Montaño 14-09-2023
*/
	namespace App\Controllers\Ambientes;
	use App\Controllers\BaseController;
	use App\Models\Ambientes\M_clases_lista;

	class C_clases_lista extends BaseController{
		public function __construct(){
			$this->clases_lista=new M_clases_lista();
		}
		public function index(){
			$lista_clases=$this->clases_lista->lista_clases();
			return view('Ambientes/V_clases_lista',['lista_clases'=>$lista_clases]);
		}
		public function editar_clase(){
			$id=$_GET['id'];
			$this->session->setFlashdata('id_clase',$id);
			return redirect()->to(base_url('clases'));
		}

	}
?>