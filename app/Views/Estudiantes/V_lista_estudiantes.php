<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Lista de Estudiantes</h2>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-basic text-nowrap" id="tbl_est">
					<caption></caption>
					<thead>
						<tr>
							<th>Nº</th>
							<th>Estudiante</th>
							<th>Tutor</th>
							<th>Aula</th>
							<th>Materia</th>
							<th>Fecha inicio</th>
							<th>Fecha fin</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista as $key) {
							echo "<tr>";
							echo"<td>".$key->nro."</td>";
							echo"<td>".$key->estudiante."</td>";
							echo"<td>".$key->tutor."</td>";
							echo"<td>".$key->aula."</td>";
							echo"<td>".$key->nombre_materia."</td>";
							echo"<td>".$key->fec_inicio."</td>";
							echo"<td>".$key->fec_fin."</td>";
							echo "<td><button type='button' class='btn btn-primary' onclick='ver_datos_estudiante(".$key->id_estudiante.",0)'> <i class='bi bi-eye' title='Ver estudiante'></i></button>";
							echo "<button class='btn btn-warning' onclick='ver_datos_estudiante(".$key->id_estudiante.",1)'><i class='bi bi-pen-fill' title='Editar'></i></button>";
							echo "<button class= 'btn btn-success' name='Asistencia' value='Asistencia' onclick='asistencia_estudiante(".$key->id_estudiante.")'><i class='bi bi-card-checklist' title='Ver asistencias'></i></button></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
				<?= $pager ?>
			</div>
		</div>
	</div>
</div>
<!--MODAL ESTUDIANTE-->
<div class="modal fade" id="modal_estudiante">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
    	<div class="modal-content">

      		<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h3 class="modal-title">Estudiante :</h3>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      	</div>	

	      	<!-- Modal body -->
	      	<div class="modal-body">
	      		
	      	</div>
	      	<!-- Modal footer -->
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      	</div>
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded",function(){
		<?php
			if(session()->getFlashData('exito')){
				//exito
				?>
				Swal.fire({
					title:'Se registro con éxito',
					icon:'success',
					confirmButtonColor:'#111111',
					confirmButtonText:'Aceptar'
				})
				<?php
			}else if(session()->getFlashData('fracaso')){
				$mensaje=session()->getFlashData('fracaso');
				?>
				//fracaso
				Swal.fire({
					title:'Error en el registro',
					text:'<?php echo $mensaje?>',
					icon:'error',
					confirmButtonColor:'#111111',
					confirmButtonText:'Aceptar'
				})
				<?php
			}?>
	})
	function ver_datos_estudiante(id,cont){
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
	function asistencia_estudiante(){
		$('#modal_estudiante').modal('show');
	}
</script>
<?php
	$this->endSection();
?>