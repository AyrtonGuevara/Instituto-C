<?php
	namespace App\Controllers\Security;

	use App\Controllers\BaseController;

	class session extends BaseController{

		public function cerrar_sesion(){
			$this->session->destroy();
			return redirect()->to(base_url('login'));
		}
	}
?>