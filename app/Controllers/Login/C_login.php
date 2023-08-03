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
			$this->encripter=\config\Services::encrypter();
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
				}else{
					echo "CONTRASEÑA ERRONEA";
				}
			}else{
				echo "usuario erroneo";
			}
		}
	}
?>