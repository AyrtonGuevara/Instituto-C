<?php
/*
	Ayrton Jhonny Guevara Montaño 03-09-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Inscripcion de estudiante</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_curso" id="form_curso" action="<?php base_url() ?>horarios/registrar_horarios">
				<h3>Datos del estudiante</h3>
				<div class="row">
					<div class="col-sm-3 form-item">
						<label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
						<input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido paterno">
					</div>
					<div class="col-sm-3 form-item">
						<label for="apellido_materno" class="form-label">Apellido Materno:</label>
						<input type="text" class="form-control" name="apellido_materno" id="apellido_materno" placeholder="Apellido materno">
					</div>
					<div class="col-sm-6 form-item">
						<label for="nombre" class="form-label">Nombre:</label>
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
					</div>
					<div class="col-sm-3 form-item">
						<label for="fecha_nac" class="form-label">Fecha nacimiento:</label>
						<input type="date" class="form-control" name="fecha_nac" id="fecha_nac">
					</div>
					<div class="col-sm-3 form-item">
						<label for="edad" class="form-label">Edad:</label>
						<input type="number" class="form-control" name="edad" id="edad" placeholder="Edad">
					</div>
					<div class="col-sm-3 form-item">
						<label for="celular" class="form-label">Celular:</label>
						<input type="number" class="form-control" name="celular" id="celular" placeholder="Celular">
					</div>
					<div class="col-sm-3">
					</div>
					<div class="col-sm-3 form-item">
						<label for="ue" class="form-label">Unidad Educativa:</label>
						<input type="text" class="form-control" name="ue" id="ue" placeholder="Unidad Educativa">
					</div>
					<div class="col-sm-3 form-item">
						<label for="turno" class="form-label">Turno:</label>
						<input type="text" class="form-control" name="turno" id="turno" placeholder="Turno">
					</div>
					<div class="col-sm-3 form-item">
						<label for="nivel" class="form-label">Nivel:</label>
						<input type="text" class="form-control" name="nivel" id="nivel" placeholder="Nivel">
					</div>
					<div class="col-sm-3 form-item">
						<label for="grado" class="form-label">Grado:</label>
						<input type="text" class="form-control" name="grado" id="grado" placeholder="Grado">
					</div>
					<div class="col-sm-3 form-item">
						<label for="zona" class="form-label">Zona:</label>
						<input type="text" class="form-control" name="zona" id="zona" placeholder="Zona">
					</div>
					<div class="col-sm-3 form-item">
						<label for="calle" class="form-label">Calle:</label>
						<input type="text" class="form-control" name="calle" id="calle" placeholder="Calle">
					</div>
					<div class="col-sm-3 form-item">
						<label for="fuente" class="form-label">Fuente:</label>
						<input type="text" class="form-control" name="fuente" id="fuente" placeholder="Fuente">
					</div>
				</div>
				<br>
				<h3>Datos del tutor</h3>
				<div class="row">
					<div class="col-sm-3 form-item">
						<label for="t_apellido_paterno0" class="form-label">Apellido Paterno:</label>
						<input type="text" class="form-control" name="t_apellido_paterno[]" id="t_apellido_paterno0" placeholder="Apellido paterno">
					</div>
					<div class="col-sm-3 form-item">
						<label for="t_apellido_materno0" class="form-label">Apellido Materno:</label>
						<input type="text" class="form-control" name="t_apellido_materno[]" id="t_apellido_materno0" placeholder="Apellido materno">
					</div>
					<div class="col-sm-6 form-item">
						<label for="t_nombre0" class="form-label">Nombre:</label>
						<input type="text" class="form-control" name="t_nombre[]" id="t_nombre0" placeholder="Nombre">
					</div>
					<div class="col-sm-3 form-item">
						<label for="t_actividad0" class="form-label">Actividad</label>
						<input type="text" class="form-control" name="t_actividad[]" id="t_actividad0" placeholder="Actividad">
					</div>
					<div class="col-sm-3 form-item">
						<label for="t_trabajo0" class="form-label">Trabajo:</label>
						<input type="text" class="form-control" name="t_trabajo[]" id="t_tranajo0" placeholder="Trabajo">
					</div>
					<div class="col-sm-3 form-item">
						<label for="t_telefono0" class="form-label">Telefono:</label>
						<input type="number" class="form-control" name="t_telefono[]" id="t_telefono0" placeholder="Telefono">
					</div>
					<div class="col-sm-3 form-item">
						<label for="t_celular0" class="form-label">Celular:</label>
						<input type="number" class="form-control" name="t_celular[]" id="t_celular0" placeholder="Celular">
					</div>
				</div>
				<br>
				<h3>Datos de la clase</h3>
				<div class="container form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" name="tipo_horarios" id="tipo_checkbox">
					<label class="form-check-label" id="tipo_checkbox_label" for="tipo_horarios">Crear horario especial</label>
				</div>
				<div class="row">
					<div class="col-sm-3 form-item">
						<label for="fuente" class="form-label">Fecha de Inscripcion:</label>
						<input type="text" class="form-control" name="fuente" id="fuente" placeholder="Fuente">
					</div>
					<div class="col-sm-3 form-item">
						<label for="fuente" class="form-label">Fecha de Inicio:</label>
						<input type="text" class="form-control" name="fuente" id="fuente" placeholder="Fuente">
					</div>
					<div class="row" id="div_horario_tradicional">
						<div class="col-sm-3">
							<label for="curso">Cursos:</label>
							<select name="Curso" class="form-control">
								<option value="">Curso</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label for="materia">Materia:</label>
							<select name="materia" class="form-control">
								<option value="">Materias</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label for="aulas">Aulas:</label>
							<select name="aulas" class="form-control">
								<option value="">Aulas</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label for="horarios">Horarios:</label>
							<select name="horarios" class="form-control">
								<option value="">Horarios</option>
							</select>
						</div>
					</div>
					<div class="row" id="div_horario_especial" hidden>
						especial
					</div>

				</div>
				<br>
				<h3>Datos del pago</h3>
				<div>
					
				</div>
				<div>

				</div>
			</form>
			<div class="form-item btn-form">
				<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById("tipo_checkbox").addEventListener('change', function(){
		tipo_horarios=document.getElementById("tipo_checkbox").checked;
		horario_especial=document.getElementById("div_horario_especial");
		horario_tradicional=document.getElementById("div_horario_tradicional");
		console.log(tipo_horarios);
		if(tipo_horarios==1){
			// se da el horario especial o formulado especialmente a peticion del estudiante
			aviso=document.getElementById("tipo_checkbox_label");
			aviso.innerHTML="Volver a horarios establecidos";
			horario_tradicional.hidden=true;
			horario_especial.hidden=false;
			console.log("encendido");
		}else if(tipo_horarios==0){
			// se da el horario tradicional o formulado por la institucion
			aviso=document.getElementById("tipo_checkbox_label");
			aviso.innerHTML="Crear horario especial";
			horario_tradicional.hidden=false;
			horario_especial.hidden=true;
			console.log("apagado");
		}
		
	});

</script>
<?php
	$this->endSection();
?>