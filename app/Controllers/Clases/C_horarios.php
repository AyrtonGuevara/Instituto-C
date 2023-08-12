<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023

	**revisar la duncion mostrar_aulas
*/
	namespace App\Controllers\Clases;
	use App\Models\Clases\M_horarios;
	use App\Models\ubicacion\M_aulas;
	use App\Controllers\BaseController;

	class C_horarios extends BaseController{
		public function __construct(){
			$this->horario=new M_horarios;
			$this->direccion=new M_aulas;
		}
		public function index(){
			$ubicaciones=$this->direccion->listar_ubicaciones();
			$lista_dia=$this->horario->lista_dia();
			return view("Clases/V_horarios",['dia'=>$lista_dia,'ubicacion'=>$ubicaciones]);
		}
		public function registrar_horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$dias=$_POST['dia'];
				$hora_inicio=$_POST['horario_inicio'];
				$hora_fin=$_POST['horario_fin'];
			}
			$respuesta=$this->horario->registrar_horarios($dias,$hora_inicio,$hora_fin);
			if ($respuesta) {
				$this->session->setFlashData('exito');
			}else{
				$this->session->setFlashData('fracaso');
			}
			return redirect()->to(base_url('horario'));
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