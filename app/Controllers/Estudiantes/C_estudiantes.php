<?php
/*
	Ayrton Jhonny Guevara Montaño 04-09-2023	
*/
	namespace App\Controllers\Estudiantes;
	use App\Controllers\BaseController;
	use App\Models\Estudiantes\M_estudiantes;
	use tFPDF;

	class C_estudiantes extends BaseController{
		public function __construct(){
			$this->estudiantes=new M_estudiantes();
		}
		public function index(){
			$menu_permisos=$this->session->get('permisos');
			//comprobando el permiso de accesso al modulo
			$this->control_pagina('3-1');
			$data=[
				'menu_permisos'=>$menu_permisos,
				'lista_fuentes'=>$this->estudiantes->lista_fuentes(),
				'lista_nivel'=>$this->estudiantes->lista_nivel(),
				'lista_turno'=>$this->estudiantes->lista_turno(),
				'lista_materias'=>$this->estudiantes->lista_materias(),
				'lista_lapso'=>$this->estudiantes->lista_lapso(),
				'title'=>'Registro Estudiante'
			];
			return view('Estudiantes/V_estudiantes',$data);
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
				$id_precios=$_POST['id_materia'];
				if ($th==='true') {
					$id_horarios=$this->estudiantes->horarios_conf_materia_esp($id_horarios);
				}
			}
			$respuesta=$this->estudiantes->aulas_materia($id_horarios,$id_precios);
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
				//si es actualizacion de datos de estudiante se captura el id
				if(isset($_POST['id'])){
					$id=$_POST['id'];
				}else{
					$id=0;
				}
				$inscripcion_estudiante=array(
					'id_est'=>$id,
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
					//?
					'materia'=>$_POST['materia'],
					'horarios'=>$_POST['horarios'],
					'aulas'=>$_POST['aulas'],
						
					'pago_checkbox'=>$_POST['pago_checkbox'],
					'total'=>$_POST['total'],
					'a_cuenta'=>$_POST['cuenta'],
					'f_pago'=>$_POST['f_pago']
					);
				//se almacena dependiendo del tipo de horario elegido
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
		public function ver_estudiante(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$tipo=$_POST['tipo'];
			}
			$respuesta=$this->estudiantes->ver_estudiante($id,$tipo);
			echo json_encode($resp = array('success'=>true,'data'=>$respuesta));
		}
		public function modificar_estudiante_tutor(){
			if ($_SERVER['REQUEST_METHOD']==='POST') {
				$id=$_POST['id'];
				$modificar_estudiante=array(
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
					't_celular'=>$_POST['t_celular']
				);
			}
			$usuario=$this->session->get('id_usuario');
			$respuesta=$this->estudiantes->modificar_estudiante_tutor($id,$modificar_estudiante,$usuario);
			if ($respuesta) {
				$this->session->setFlashdata("exito","Se registro al estudiante con exito");
			}else{
				$this->session->setFlashdata("fracaso","Error al modificar el estudiante");
			}
			return redirect()->to(base_url("lista_estudiantes"));
		}

		public function pdf_estudiante_fl(){
			$id = $_GET['id'];
			//$id = $this->request->getPost('id');
			if (!$id) {
		        return $this->response->setStatusCode(400)->setBody('ID no proporcionado');
		    }
			//$id=$this->estudiantes->ultimo_registro();
			//$id = intval($id->id_estudiante);
			$data=$this->estudiantes->ver_estudiante($id,1);

			// Inicializar FPDF
	        $pdf = new tFPDF('L', 'mm', array(140, 216)); // 'P' para orientación vertical, 'mm' para unidades de medida, 'Letter' para tamaño de papel captura
	        $pdf->SetMargins(20,15,10);
	        $pdf->AddPage();
	        $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	        $pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf',true);
			$pdf->SetFont('DejaVu','B',13);
	        $pdf->Cell(0, 10, 'FILIACION ESTUDIATE', 1, 1, 'C');

	        $pdf->Ln(3);

	        // Datos del estudiante
	        $pdf->SetFont('DejaVu', 'B', 12);
	        $pdf->Cell(0, 8, 'DATOS DEL ESTUDIANTE', 1, 1, 'L');
	        $pdf->SetFont('DejaVu', '', 12);

	        $pdf->Ln(3);
	        
	        $pdf->Cell(92, 8, 'Apellido Paterno: '.$data->ap_pat_persona , 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Unid. Educ.: '.$data->unid_educativa, 1, 1);

	        $pdf->Cell(92, 8, 'Apellido Materno: '.$data->ap_mat_persona , 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(46, 8, 'Grado: '.$data->grado, 1, 0);
	        $pdf->Cell(46, 8, 'Turno: '.$data->turno, 1, 1);

	        $pdf->Cell(92, 8, 'Nombre(s): '.$data->nom_persona, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Zona en la que vive:'.$data->zona, 1, 1);

	        $pdf->Cell(40, 8, 'Edad: '.$data->edad, 1, 0);
	        $pdf->Cell(52, 8, 'Fecha Nac.:'.$data->fec_nacimiento, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Direccion: '.$data->direccion, 1, 1);

	        $pdf->Cell(40, 8, 'Celular: '.$data->celular, 1, 0);
	        $pdf->Cell(52, 8, 'Fecha Ins.: '.$data->fecha_act, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Fuente: '.$data->fuente, 1, 1);

	        $pdf->Ln(3);

	        // Datos del Tutor
	        $pdf->SetFont('DejaVu', 'B', 12);
			$pdf->Cell(0, 8, 'DATOS DEL TITULAR   (PAPÁ, MAMÁ O APODERADO)', 1, 1, 'L');
	        $pdf->SetFont('DejaVu', '', 12);

	        $pdf->Ln(3);
	        
	        $pdf->Cell(92, 8, 'Apellido Paterno: '.$data->pat_tutor, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Actividad: '.$data->act_tutor, 1, 1);

	        $pdf->Cell(92, 8, 'Apellido Materno: '.$data->mat_tutor, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(92, 8, 'Trabajo en: '.$data->trab_tutor, 1, 1);

	        $pdf->Cell(92, 8, 'Nombre(s): '.$data->nom_tutor, 1, 0);
	        $pdf->Cell(2, 8, '', 0, 0);
	        $pdf->Cell(46, 8, 'Telf.: '.$data->telefono_tutor, 1, 0);
	        $pdf->Cell(46, 8, 'Cel.: '.$data->celular_tutor, 1, 1);
    		
    		//$pdfPath = WRITEPATH.'upload/pdf.pdf';
			// Salida del PDF
			$pdf->Output('I', 'formulario_inscripcion.pdf');
			exit;
	        //return $this->response->setJson(['pdf'=>base_url('upload/pdf.pdf')]);
		}

		public function pdf_estudiante_ins(){
			$id = $_GET['id'];
			//$id = $this->request->getPost('id');
			if (!$id) {
		        return $this->response->setStatusCode(400)->setBody('ID no proporcionado');
		    }
			//$id=$this->estudiantes->ultimo_registro();
			//$id = intval($id->id_estudiante);
			$data=$this->estudiantes->boleta_de_pago($id);

			// Inicializar FPDF
	        $pdf = new tFPDF('L', 'mm', array(165,108)); // medio array(170, 215) cuarta array(165,108) 'P' para orientación vertical, 'mm' para unidades de medida, 'Letter' para tamaño de papel captura
	        $pdf->SetMargins(10,9,0);
	        $pdf->AddPage();
	        $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	        $pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf',true);

        // Logo

        //$pdf->Image('logo.png',10,6,30);
        $pdf->SetFont('DejaVu','B',14);

        $pdf->Cell(72,5,'CREATICA Instituto',0,0,'C');
        $pdf->Cell(72,4,'BOLETA DE INSCRIPCION',0,1,'C');
        $pdf->SetFont('DejaVu','',7);
        $pdf->Cell(72,5,'Miraflores Av. Lucas Jimenez Estr #7 6to Piso',0,0,'C');
        $pdf->Cell(10,4,'',0,0,'C');
        $pdf->Cell(17,4,'DIA',0,0,'C');
        $pdf->Cell(1,4,'',0,0,'C');
        $pdf->Cell(17,4,'MES',0,0,'C');
        $pdf->Cell(1,4,'',0,0,'C');
        $pdf->Cell(17,4,'AÑO',0,1,'C');
        $pdf->Cell(72,4,'Tel: 2 222748 WhatsApp 612-348323 612-74544',0,0,'C');
        $pdf->Cell(10,4,'',0,0,'C');
        $pdf->Cell(17,6,$data->dia_ins,1,0,'C');
        $pdf->Cell(1,5,'',0,0,'C');
        $pdf->Cell(17,6,$data->mes_ins,1,0,'C');
        $pdf->Cell(1,5,'',0,0,'C');
        $pdf->Cell(17,6,$data->año_ins,1,1,'C');
        $pdf->Ln(2);
    
        $pdf->SetFont('DejaVu','',9);
		// Nombre del estudiante
        $pdf->Cell(30, 5, 'Nombre Estudiante:', 0);
        $pdf->Cell(70, 6, $data->nombre, 1);
        $pdf->Cell(14, 5, 'Codigo:', 0);
        $pdf->Cell(30, 6, $data->id_estudiante, 1);
        $pdf->Ln(7);

        // Materia y Aula Número
        $pdf->Cell(30, 5, 'Aula Numero:', 0);
        $pdf->Cell(65, 6, $data->nombre_aula, 1);
        $pdf->Cell(10, 5, 'Plan:', 0);
        $pdf->Cell(39, 6, $data->detalle, 1);
        $pdf->Ln(7);
        // Segunda fila
        $pdf->Cell(30, 5, 'Materia:', );
        //114
        $pdf->Cell(70, 6, $data->nombre_materia, 1);
        $pdf->Ln(7);
        //
        $pdf->Cell(30, 5, 'Dias:', );
        $pdf->Cell(70, 6, $data->dias, 1);
        $pdf->Ln(7);
        //
        $pdf->Cell(30, 5, 'Hora de ingreso:', );
        $pdf->Cell(70, 6, $data->h_inicio, 1);
        $pdf->Ln(7);
        //
        $pdf->Cell(30, 5, 'Hora de salida:', );
        $pdf->Cell(70, 6, $data->h_fin, 1);
        $pdf->Ln(7);
        //
        $pdf->Cell(30, 5, 'Fecha inicio:', );
        $pdf->Cell(70, 6, $data->fec_inicio, 1);
        $pdf->Ln(7);
        //
        $pdf->Cell(30, 5, 'Fecha Fin:', );
        $pdf->Cell(70, 6, $data->fec_fin, 1);
        $pdf->Ln(7);
        //
        if(strcmp($data->estado,'cancelado')==0){
        	$pdf->Cell(30, 5, 'Precio Total:', );
	        $pdf->Cell(15, 6, $data->monto_cancelado, 1);
	        $pdf->Cell(16, 5, 'A cuenta:', );
	        $pdf->Cell(15, 6, '---', 1);
	        $pdf->Cell(12, 5, 'Saldo:', );
	        $pdf->Cell(15, 6, '---', 1);
	        $pdf->Cell(20, 5, 'Fecha pago:', );
	        $pdf->Cell(20, 6, '---', 1);
        }else{
        	$pdf->Cell(30, 5, 'Precio Total:', );
	        $pdf->Cell(15, 6, ($data->monto_cancelado+$data->monto_deuda), 1);
	        $pdf->Cell(16, 5, 'A cuenta:', );
	        $pdf->Cell(15, 6, $data->monto_cancelado, 1);
	        $pdf->Cell(12, 5, 'Saldo:', );
	        $pdf->Cell(15, 6, $data->monto_deuda, 1);
	        $pdf->Cell(20, 5, 'Fecha pago:', );
	        $pdf->Cell(20, 6, $data->fec_pago, 1);
        }
        

        $pdf->SetXY(111,39);
        $pdf->Cell(43, 20,'',1,0,'C');
        $pdf->SetXY(111,60);
        $pdf->Cell(43, 20,'',1,0,'C');

        $pdf->SetXY(111,52);
        $pdf->Cell(43, 10,'Recibi Conforme',0,0,'C');
        $pdf->SetXY(111,73);
        $pdf->Cell(43, 10,'Entregue Conforme',0,0,'C');

    
			// Salida del PDF
			$pdf->Output('I', 'formulario_inscripcion.pdf');
	        exit;
		}
	}
?>