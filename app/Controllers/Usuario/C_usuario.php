<?
	/*
	Ayrton Jhonny Guevara Montaño 02-08-2023
	*/
	namespace App\Controllers\Usuario;
	use App\Models\Usuario\M_usuario;
	use App\Controllers\BaseController;	

	class C_usuario extends BaseController{
		public function __construct(){
			$this->usuario=new M_usuario();
			//$this->session=\config\Services::session();
			//$this->encripter=\config\Services::encrypter();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			if(array_search('5-2',$menu_permisos)===false){
				throw new \App\Controllers\Error\C_403();
			}
			$lista_personas=$this->usuario->listar_personas_pusuario();
			$lista_lvl=$this->usuario->listar_nivel();
			$list=$this->usuario->listar_usuario();
			return view('Usuario/V_usuario',['list'=>$list, 'persona'=>$lista_personas,'nivel'=>$lista_lvl,'menu_permisos'=>$menu_permisos]);
		}
		public function registrar_usuario(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$persona=$_POST['persona'];
				$usuario=$_POST['usuario'];
				$pasword=$_POST['password'];
				$nivel=$_POST['nivel'];
			}
			$salt=bin2hex(random_bytes(16));
			$psswd=password_hash($pasword.$salt, PASSWORD_DEFAULT);
			$respuesta=$this->usuario->agregar_usuario($persona,$usuario,$psswd,$salt,$nivel);
			if ($respuesta) {
				$this->session->setFlashData("exito","Se registro con exito");
			}else{
				$this->session->setFlashData("fracaso","no se pudo registrar");
			}
			return redirect()->to(base_url('usuario'));
		}
		public function mostrar_usuario(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->usuario->mostrar_usuario($id);
			echo json_encode($res=array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_usuario(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$persona=$_POST['persona'];
				$usuario=$_POST['usuario'];
				$password=$_POST['password'];
				$nivel=$_POST['nivel'];
			}
			$salt=bin2hex(random_bytes(16));
			$psswd=password_hash($password.$salt, PASSWORD_DEFAULT);
			$respuesta=$this->usuario->modificar_usuario($id,$persona,$usuario,$psswd,$salt,$nivel);
			if ($respuesta) {
				$this->session->setFlashDAta("exito","Se modifico con exito");
			}else{
				$this->session->setFlashData("fracaso","Error al modificar");
			}
			return redirect()->to(base_url('usuario'));
		}
		public function eliminar_usuario(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
			}
			$respuesta=$this->usuario->eliminar_usuario($id);
			echo json_encode($resp=array('success'=>true,'data'=>$respuesta));
		}

	}
?>