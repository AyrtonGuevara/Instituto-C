<?php
/*
	Ayrton Jhonny Guevara Montaño 22-10-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Cursos</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_curso" id="form_curso" action="<?php base_url() ?>cursos/registrar_horario">
				<input type="text" name="id" id="id" hidden>
				<div class="row">
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-4 form-item">
								<label for="dia" class="form-label">D&iacute;a</label>
								<select name="dia[]" id="dia" class="form-control"required>
									<option id="default" default></option>
									<?php
									foreach ($dia->getResult() as $key) {
									echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
										}
									?>
								</select>
							</div>
							<div class="col-sm-4 form-item">
								<label for="horario" class="form-label">Hora inicio:</label>
								<input type="time" class="form-control" name="horario_inicio[]" required >
							</div>
							<div class="col-sm-4 form-item">
								<label for="horario" class="form-label">Hora fin:</label>
								<input type="time" class="form-control" name="horario_fin[]" required>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="btn btn-form-plus" onclick="horario_mas_uno()" name="horauno" id="horauno"><i class="bi bi-plus-circle-fill"></i></div>
					</div>
				</div>
				<div id="nuevo_horario0" class="form-item"></div>
				<div class="form-item btn-form">
					<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
					<input type="submit" class="btn border-light" name="Modificar" value="Modificar" id="Modificar" disabled>
				</div>
			</form>
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
<script>
	var contador_btn_plus=0;
		function horario_mas_uno(){
		contador_btn_plus++;
	    div_hora = document.getElementById("nuevo_horario" + (contador_btn_plus - 1));
	    div_hora.innerHTML += "<div class='row'>"+
					"<div class='col-sm-10'>"+
						"<div class='row'>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='dia' class='form-label'>D&iacute;a</label>"+
								"<select name='dia[]' id='dia' class='form-control'required>"+
									"<option id='default' default></option>"+
									"<?php foreach ($dia->getResult() as $key) { echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>"; } ?>"+
								"</select>"+
							"</div>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='horario' class='form-label'>Hora inicio:</label>"+
								"<input type='time' class='form-control' name='horario_inicio[]' required >"+
							"</div>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='horario' class='form-label'>Hora fin:</label>"+
								"<input type='time' class='form-control' name='horario_fin[]' required>"+
							"</div>"+
						"</div>"+
					"</div>"+
					"<div class='col-sm-2'>"+
						"<div class='btn btn-form-trash' onclick='horario_menos_uno("+contador_btn_plus+")' name='horauno' id='horauno'><i class='bi bi-trash-fill' title='Eliminar'></i></div>"+
					"</div>"+
					"<div id='nuevo_horario"+contador_btn_plus+"'> </div>"+
				"</div>";
	}

	function horario_menos_uno(id){
		div_hora = document.getElementById("nuevo_horario" + (id-1));
		div_hora.innerHTML = "";
		contador_btn_plus=id-1;
	}

</script>
<?php
	$this->endSection();
?>