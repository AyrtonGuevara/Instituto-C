<?
/*
	Ayrton Jhonny Guevara Montaño 18-12-2023
*/
	namespace App\Controllers\Pagos;
	use App\Controllers\BaseController;
	use App\Models\Pagos\M_lista_pagos;
	
	class C_lista_pagos extends BaseController{
		public function __construct(){
			$this->lista_pagos=new M_lista_pagos();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('4-1');
			$lista=$this->lista_pagos->lista_pagos();
			$paginacion=$this->pagination($lista);
			$data=[
				'lista_pagos'=>$paginacion['pagedResults'],
				'pager'=>$paginacion['pager_links'],
				'menu_permisos'=>$menu_permisos,
				'title'=>'Pagos'
			];
			return view('Pagos/V_lista_pagos',$data);
		}
		public function registrar_pago(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST["id"];
				$monto_c=$_POST["monto_c"];
				$saldo=$_POST["saldo"];
				$fec_pago=$_POST["fec_pago"]??0;
			}
			$respuesta=$this->lista_pagos->registrar_pago($id,$monto_c,$saldo,$fec_pago);
			if ($respuesta) {
				$this->session->setFlashdata("exito","Se registro al estudiante con exito");
			}else{
				$this->session->setFlashdata("fracaso","error");
			}
			return redirect()->to(base_url("lista_pagos"));

		}
	}
?>