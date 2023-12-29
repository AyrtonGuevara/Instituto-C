<?
/*
	Ayrton Jhonny Guevara Montaño 18-12-2023
*/
	namespace App\Controllers\Pagos;
	use App\Controllers\BaseController;
	use App\Models\Pagos\M_lista_pagos;
	use App\Controllers\Security\sesion;
	
	class C_lista_pagos extends BaseController{
		public function __construct(){
			$this->lista_pagos=new M_lista_pagos();
			$this->seguridad= new sesion();
		}
		public function index(){
			$nivel_usuario=$this->session->get('nivel');
			if(!$this->seguridad->comprobar_modulo(4,1,$nivel_usuario)){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_pagos=$this->lista_pagos->lista_pagos();
			return view('Pagos/V_lista_pagos',['lista_pagos'=>$lista_pagos]);
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