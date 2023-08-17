<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Materia</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form action="<?php echo base_url()?>horario/registrar_horario" name="form_horario" id="form_horario" method="post" accept-charset="utf-8">
				<div class="row">
					<div class="col-sm-6 form-item">
						<label for="Nombre_materia" class="form-label">Nombre materia:</label>
						<input type="text" class="form-control" name="nombre_materia" id="nombre_materia" placeholder="Nombre de la materia" required>
					</div>
					<div class="col-sm-6 form-item">
						<label for="curso" class="form-label">Curso:</label>
						<select name="curso" id="curso" class="form-control" required>
							<option  id="default" default></option>
							<?php
							foreach ($cursos->getResult() as $key) {
								echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
							}
							?>
						</select>
					</div>
					<div class="col-sm-6 form item">
						<label for="direccion" class="form-label">Direcci&oacute;n:</label>
						<select name="ubicacion" id="ubicacion" class="form-control" required>
							<option id="default" default></option>
							<?php
							foreach ($ubicacion->getResult() as $key) {
								echo "<option value='".$key->id_ubicacion."'>".$key->direccion."</option>";
							}
							?>
						</select>
					</div>
					<div class="col-sm-6 form item">
						<label for="aula" class="form-label">Aula:</label>
						<select name="aula" id="aula" class="form-control">
							<option id="default"></option>
						</select>
					</div>
					<div class="col-sm-12 form-item">
						<div class="row">
							<div class="col-sm-3 form-item">
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
							<div class="col-sm-3 form-item">
								<label for="horario" class="form-label">Hora inicio:</label>
								<input type="time" class="form-control" name="horario_inicio[]" required >
							</div>
							<div class="col-sm-3 form-item">
								<label for="horario" class="form-label">Hora fin:</label>
								<input type="time" class="form-control" name="horario_fin[]" required>
							</div>
							<div class="col-sm-3">
								<div class="btn btn-form-plus" onclick="horario_mas_uno()" name="horauno" id="horauno"><i class="bi bi-plus-circle-fill"></i></div>
							</div>
						</div>
					</div>
					<div id="nuevo_horario0"></div>
					<div class="btn-form form-item">
						<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
						<input type="submit" class="btn border-light" name="Modificar" value="Modificar" id="Modificar" disabled>
					</div>
				</div>
			</form>
		</div>
		<div class="card_foot">
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
	var ubicacion=document.getElementById("ubicacion");
	ubicacion.addEventListener("change", aulas_actualizar);

	document.addEventListener("DOMContentLoaded",function(){
	});


	function aulas_actualizar(){
		var aula=document.getElementById("aula");
		valor_ubicacion=ubicacion.value;
		$.ajax({
			url:"<?php echo base_url()?>horario/aulas",
			type:"POST",
			data:{valor_ubicacion:valor_ubicacion},
			success:function(resp){
				aula.innerHTML=resp;
				//aula.innerHTML=resp.data[0].contenido_select;
			}
		});
		console.log("pasa"+valor_ubicacion);
	}
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



