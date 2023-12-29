<?php
	namespace App\Controllers\Security;
	use App\Models\Security\M_seguridad;
	use App\Controllers\BaseController;

	class sesion extends BaseController{
		public function __construct(){
			$this->seguridad=new M_seguridad();
		}
		public function cerrar_sesion(){
			$this->session->destroy();
			return redirect()->to(base_url('login'));
		}
		public function comprobar_modulo($mod,$submod,$lvl_usu){
			$respuesta=$this->seguridad->comprobar_modulo($mod,$submod,$lvl_usu);
			if ($respuesta[0]->t1==='t') {
				$resp=true;
			}else{
				$resp=false;
			}
			return $resp;
		}
	}
?>