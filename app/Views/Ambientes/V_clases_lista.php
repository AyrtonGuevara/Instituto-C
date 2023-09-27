<?php
/*
	Ayrton Jhonny Guevara Montaño 14-09-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>

<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Lista clases</h2>
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-footer">
			<div class="table-responsive">
				<table class="table table-hover table-basic">
					<caption>table title and/or explanatory text</caption>
					<thead>
						<tr>
							<th>Nº</th>
							<th>Ubicacion</th>
							<th>Aula</th>
							<th>Materia</th>
							<th>Horario</th>
							<th>Profesor</th>
							<th>Capacidad</th>
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_clases as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->direccion."</td>";
							echo "<td>".$key->nombre_aula."</td>";
							echo "<td>".$key->nombre_materia."</td>";
							echo "<td>".$key->id_horarios."</td>";
							echo "<td>".$key->nombre."</td>";
							echo "<td>".$key->cantidad_estudiantes."</td>";
							echo "<td><a type='button' class='btn btn-primary' href='".base_url()."clases_lista/editar_clase?id=".$key->id_clase."'><i class='bi bi-card-checklist' title='Modificar aula'></i></a></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
	$this->endSection();
?>