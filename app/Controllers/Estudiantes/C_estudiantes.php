<?php
/*
	Ayrton Jhonny Guevara Montaño 04-09-2023	
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_estudiantes;

	class C_estudiantes extends BaseController{
		public function __construct(){
			$this->estudiantes=new M_estudiantes();
		}
		public function index(){
			$lista_fuentes=$this->estudiantes->lista_fuentes();
			$lista_nivel=$this->estudiantes->lista_nivel();
			$lista_turno=$this->estudiantes->lista_turno();
			$lista_materias=$this->estudiantes->lista_materias();
			$lista_lapso=$this->estudiantes->lista_lapso();
			return view('Estudiantes/V_estudiantes',["lista_fuentes"=>$lista_fuentes,"lista_nivel"=>$lista_nivel,"lista_turno"=>$lista_turno,"lista_materias"=>$lista_materias,"lista_lapso"=>$lista_lapso]);
		}
		public function horarios(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id_materia'];
			}
			$respuesta=$this->estudiantes->horarios_materia($id);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function aulas(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$th=$_POST['tipo_horarios'];
				$id_horarios=$_POST['id_horarios'];
				$id_materia=$_POST['id_materia'];
				if ($th==='true') {
					$id_horarios=$this->estudiantes->horarios_conf_materia_esp($id_horarios);
				}
			}
			$respuesta=$this->estudiantes->aulas_materia($id_horarios,$id_materia);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function horarios_materia_esp(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id_materia'];
			}
			$respuesta=$this->estudiantes->horarios_materia_esp($id);
			echo json_encode($resp=array("success"=>true,"data"=>$respuesta));
		}
		public function registrar_estudiante(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$inscripcion_estudiante=array(
					'apellido_paterno'=>$_POST['apellido_paterno'],
					'apellido_materno'=>$_POST['apellido_materno'],
					'nombre'=>$_POST['nombre'],
					'fecha_nac'=>$_POST['fecha_nac'],
					'edad'=>$_POST['edad'],
					'celular'=>$_POST['celular'],
					'fuente'=>$_POST['fuente'],
					'ue'=>$_POST['ue'],
					'turno'=>$_POST['turno'],
					'nivel'=>$_POST['nivel'],
					'grado'=>$_POST['grado'],
					'zona'=>$_POST['zona'],
					'calle'=>$_POST['calle'],

					't_apellido_paterno'=>$_POST['t_apellido_paterno'],
					't_apellido_materno'=>$_POST['t_apellido_materno'],
					't_nombre'=>$_POST['t_nombre'],
					't_actividad'=>$_POST['t_actividad'],
					't_trabajo'=>$_POST['t_trabajo'],
					't_telefono'=>$_POST['t_telefono'],
					't_celular'=>$_POST['t_celular'],

					
					'f_inicio'=>$_POST['f_inicio'],
					'cantidad'=>$_POST['cantidad'],
					'materia'=>$_POST['materia'],
					'horarios'=>$_POST['horarios'],
					'aulas'=>$_POST['aulas'],

					'pago_checkbox'=>$_POST['pago_checkbox'],
					'total'=>$_POST['total'],
					'a_cuenta'=>$_POST['cuenta'],
					'f_pago'=>$_POST['f_pago']
					);

				if (isset($_POST['tipo_horarios'])) {
					//asi o null??
					$inscripcion_estudiante2 = array(
					'tipo_horarios'=>true,
					'materia'=>'', 
					'horarios'=>'',
					'aulas'=>'',
					'materia2'=>$_POST['materia2'],
					'horario'=>$_POST['horario'],
					'aula'=>$_POST['aula']
					);
				}else{
					$inscripcion_estudiante2=array(
					'tipo_horarios'=>false,
					'materia'=>$_POST['materia'],
					'horarios'=>$_POST['horarios'],
					'aulas'=>$_POST['aulas'],
					'materia2'=>'',
					'horario'=>'',
					'aula'=>''
					);
				}
				$inscripcion_estudiante=array_merge($inscripcion_estudiante,$inscripcion_estudiante2);

			}
			$inscripcion_estudiante=json_encode($inscripcion_estudiante);
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->estudiantes->registrar_estudiante($usuario,$inscripcion_estudiante);
			if ($respuesta[0]->success=='t') {
				$this->session->setFlashdata("exito","Se registro al estudiante con exito");
			}else{
				$this->session->setFlashdata("fracaso",$respuesta[0]->mensaje);
			}
			return redirect()->to(base_url("estudiantes"));
		}

	}
?>