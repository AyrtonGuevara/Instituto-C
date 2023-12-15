<?php
/*
	Ayrton Jhonny Guevara Montaño 15-12-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Reinscripciones</h2>
		</div>
		<div class="card-body">
			<label for="clase_busc">Clase:</label>

			<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-basic text-nowrap" id="tbl_est">
					<caption>table title and/or explanatory text</caption>
					<thead>
						<tr>
							<th>Nº</th>
							<th>Estudiante</th>
							<th>Escuela</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_reinscripcion as $key) {
							echo "<tr>";
							echo"<td>".$key->nro."</td>";
							echo"<td>".$key->estudiante."</td>";
							echo"<td>".$key->unid_educativa."</td>";
							echo "<td><button class= 'btn btn-success' name='Asistencia' value='Asistencia' onclick='reinscripcion_estudiante(".$key->id_estudiante.")'><i class='bi bi-card-checklist' title='Ver asistencias'> Reinscribir</i></button></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
		<div class="card-footer">
			<div class="accordion" id="accordionExample">
  				<div class="accordion-item" id="lista_clases">
  				</div>
  			</div>
		</div>
	</div>
</div>
<script>
	function reinscripcion_estudiante(id){
		cont=2;
		$.ajax({
			url:'<?php echo base_url()?>lista_estudiantes/ver_estudiante',
			type:'POST',
			data:{id:id,
				cont:cont},
			success:function(){
				window.location.href="<?php echo base_url()?>estudiantes";
			}
		})
	}
</script>
<?php
	$this->endSection();
?>