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
				<div>
					
				</div>
				<br>
				<h3>Datos del pago</h3>
				<div>
					
				</div>
				<div hidden>
					estudiante
					codigp
					c
					paterno
					materno
					nombres
					edad
					nac
					cel
					fec insc (el dia del registro?)
					ue
					grado
					turno
					zona
					calle
					fuente
					DEL TUTOR
					ap pat
					ap mat
					nombre
					actividad
					trabajo en
					talf
					cel
				</div>
			</form>
			<div class="form-item btn-form">
				<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
			</div>
		</div>
	</div>
</div>

<?php
	$this->endSection();
?>