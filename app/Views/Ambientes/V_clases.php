<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>

<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Clases</h2>
		</div>
		<div class="card-body" id="card-forms">
			<div class="item-form btn-form-center">
				<input type="button" class="btn btn-primary" name="agregar_clase" id="agregar_clase" value="Nueva Clase" data-bs-toggle="modal" data-bs-target="#modal_agregar_clase">
			</div>
			
				<div class="row">
					<div class="col-sm-3">
						
					</div>
					<div class="col-sm-6 form-item">
						<label for="ubicacion" class="form-label">Buscar por aula:</label>
						<select class="form-control" name="ubicacion">
							<option value=""></option>
							option
						</select>
					</div>
				</div>
				
		</div>
		<div class="card-footer">
			<div class="table-responsive">
				<table class="table table-hover table-basic">
					<caption>table title and/or explanatory text</caption>
					<thead>
						<tr>
							<th>header</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>data</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--MODAL-->
<div class="modal fade" id="modal_agregar_clase">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
    	<div class="modal-content">

      		<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h3 class="modal-title">Clase:</h3>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      	</div>	

	      	<!-- Modal body -->
	      	<div class="modal-body" id="card-forms">
	      		<form action="<?php base_url()?>clase/registrar_clase" name="form_clase" id="form_aulas" method="POST" accept-charset="utf-8">
	      			<input type="text" name="id" id="id" hidden>
		      		<div class="row">
		      			<div class="col-sm-6 form-item">
		      				<label for="materia" class="form-label">Materia</label>
		      				<select name="materia" class="form-control" id="materia" required>
		      					<option id="materia1"></option>
		      					option
		      				</select>
		      			</div>
		      			<div class="col-sm-6 form-item">
		      				<label for="horario" class="form-label">Horario</label>
		      				<select name="horario" class="form-control" id="horario" required>
		      					<option id="horario1"></option>
		      					option
		      				</select>
		      			</div>
		      			<div class="col-sm-6 form-item">
		      				<label for="aula" class="form-label">Aula</label>
		      				<select name="aul" class="form-control" id="aula">
		      					<option id="aula1"></option>
		      					option
		      				</select>
		      			</div>
		      			<div class="col-sm-6 form-item">
		      				<label for="personal" class="form-label">Personal</label>
		      				<select name="personal" class="form-control" id="personal">
		      					<option id="personal1"></option>
		      					option
		      				</select>
		      			</div>
		      		</div>
		      		<div class="btn-form form-item">
		      			<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
						<input type="submit" class="btn border-light" name="Modificar" value="Modificar" id="Modificar" disabled>
		      		</div>
			      </form>
	      	</div>
	      	<!-- Modal footer -->
	      	<div class="modal-footer">
	        	<button type="button" id="cerrar_modal" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      	</div>
		</div>
	</div>
</div>
<?php
	$this->endSection();
?>