<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023

	**revisar la duncion mostrar_aulas
*/
	namespace App\Controllers\Clases;
	use App\Models\Clases\M_horarios;
	use App\Models\Ambientes\M_aulas;
	use App\Controllers\BaseController;

	class C_horarios extends BaseController{
		public function __construct(){
			$this->horario=new M_horarios;
			$this->direccion=new M_aulas;
		}
		public function index(){
			$cursos=$this->horario->lista_cursos();
			$ubicaciones=$this->direccion->listar_ubicaciones();
			$lista_dia=$this->horario->lista_dia();
			return view("Clases/V_horarios",['dia'=>$lista_dia,'ubicacion'=>$ubicaciones,'cursos'=>$cursos]);
		}
		public function registrar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$dias=$_POST['dia'];
				$hora_inicio=$_POST['horario_inicio'];
				$hora_fin=$_POST['horario_fin'];
				$nombre_materia=$_POST['nombre_materia'];
				$cursos=$_POST['curso'];
				$ubicacion=$_POST['ubicacion'];
				$aula=$_POST['aula'];
			}
			$envio=array('nombre_materia'=>$nombre_materia,'cursos'=>$cursos,'ubicacion'=>$ubicacion,'aula'=>$aula,'dias'=>$dias,'hora_inicio'=>$hora_inicio,'hora_fin',$hora_fin);
			$j_envio=json_encode($envio);
			print_r($j_envio);
			print_r($envio);
			/*$respuesta=$this->horario->registrar_horarios($j_envio);
			if ($respuesta) {
				$this->session->setFlashData('exito');
			}else{
				$this->session->setFlashData('fracaso');
			}*/
			//return redirect()->to(base_url('horario'));
		}
		public function mostrar_aulas(){
			$id=$_POST['valor_ubicacion'];
			$respuesta=$this->horario->aulas_direccion($id);
			
			$optionsHtml = "<option id='default' default></option>";
			foreach ($respuesta->getResult() as $resp) {
	    		$optionsHtml .= "<option value='$resp->id_aula'>$resp->nombre_aula</option>";
			}

			echo $optionsHtml;
		}
	}
?>