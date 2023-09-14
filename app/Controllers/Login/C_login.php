<?php
	/*
	Ayrton Jhonny Guevara Montaño 03-08-2023
	*/
	namespace App\Controllers\Login;
	use App\Models\Login\M_login;
	use App\Controllers\BaseController;

	class C_login extends BaseController{
		public function __construct(){
			$this->login=new M_login();
			//$this->session=session();
			//$this->encripter=\config\Services::encrypter();
		}
		public function index(){
			return view('Login/V_login');
		}
		public function autenticar(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$usuario=$_POST['usuario'];
				$psswrd=$_POST['psswrd'];
			}
			$resp1=$this->login->verificar_usuario($usuario);
			$respuesta1=$resp1->getRow();
			if (isset($respuesta1->salt)) {
				$salt=$respuesta1->salt;
				$contraseña_ingresada=$psswrd.$salt;
				$contraseña_bdd=$respuesta1->psswd;
				if (password_verify($contraseña_ingresada, $contraseña_bdd)) {
					echo "INGRESAR!!!";
					//se hace una nueva consulta y se guarda en la sesion y se va al dasboard
					$resp2=$this->login->iniciar_sesion($usuario);
					$resp2=$resp2->getRow();
					//nivel
					$datasession=[
						'login'=>true,
						'usuario'=>$resp2->usuario,
						'id_usuario'=>$resp2->id_usuario,
						'nivel'=>$resp2->nivel
					];
					$this->session->set($datasession);
					return redirect()->to(base_url('usuario'));
				}else{
					echo "CONTRASEÑA ERRONEA";
					$this->session->setFlashdata("error_contraseña","contraseña erronea");
					return redirect()->to(base_url('login'));
				}
			}else{
				echo "usuario erroneo";
				$this->session->setFlashdata("error_usuario","usuario erroneo");
				return redirect()->to(base_url('login'));
			}
		}
	}
?>