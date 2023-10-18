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
		<div class="card-body"id="card-forms">
			<form action="<?php base_url()?>clases/registrar_clases" name="form_clase" id="form_aulas" method="POST" accept-charset="utf-8">
			      			<input type="text" name="id" id="id" hidden>
				      		<div class="row">
				      			<div class="col-sm-6 form-item">
				      				<label for="materia" class="form-label">Materia</label>
				      				<select name="materia" class="form-control" id="materia" required>
				      					<option id="materia1" selected></option>
				      					<?php
				      					foreach ($lista_materia->getResult() as $key) {
				      						echo "<option value='".$key->id_materia."'>".$key->materia."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="horario" class="form-label">Horario</label>
				      				<select name="horario" class="form-control" id="horario" required>
				      					<option id="horario1" selected></option>
				      					<?php
				      					foreach ($lista_horario->getResult() as $key) {
				      						echo "<option value='".$key->id_conf_horarios."'>".$key->dias_horarios."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="aula" class="form-label">Aula</label>
				      				<select name="aula" class="form-control" id="aula">
				      					<option id="aula1" selected></option>
				      					<?php
				      					foreach ($lista_aula->getResult() as $key) {
				      						echo "<option value='".$key->id_aula."'>".$key->aula."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="personal" class="form-label">Personal</label>
				      				<select name="personal" class="form-control" id="personal">
				      					<option id="personal1" selected></option>
				      					<?php
				      					foreach ($lista_personal->getResult() as $key) {
				      						echo "<option value='".$key->id_personal."'>".$key->persona."</option>";
				      					}
				      					?>

				      				</select>
				      			</div>
				      		</div>
				      		<div class="btn-form form-item">
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
							//echo "<td><a type='button' class='btn btn-primary' href='".base_url()."clases_lista/editar_clase?id=".$key->id_clase."'><i class='bi bi-card-checklist' title='Modificar aula'></i></a></td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='editar_clase(".$key->id_clase.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_clase(".$key->id_clase.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
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
			}else{}
		?>
	});

	function editar_clase(id){
		console.log(id);
		$.ajax({
			url:"<?php echo base_url()?>clases/mostrar_clases",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);
				resp2=JSON.parse(resp);
				idf=document.getElementById("id");
				form=document.getElementById("form_aulas");
				materia=document.getElementById("materia1");
				horario=document.getElementById("horario1");
				aula=document.getElementById("aula1");
				personal=document.getElementById("personal1");
				idf.value=resp2.data[0].id_clase;
				materia.value=resp2.data[0].id_materia;
				materia.textContent=resp2.data[0].materia;
				horario.value=resp2.data[0].id_horarios;
				horario.textContent=resp2.data[0].horarios;
				aula.value=resp2.data[0].id_aula;
				aula.textContent=resp2.data[0].nombre_aula;
				personal.value=resp2.data[0].id_personal;
				personal.textContent=resp2.data[0].docente;
				//botones
				btnaceptar=document.getElementById("Registrar");
				btnmodificar=document.getElementById("Modificar");
				btneliminar=document.getElementById("Eliminar");
				btnaceptar.disabled=true;
				btnmodificar.disabled=false
				btnaceptar.classList.remove("btn-primary");
				btnmodificar.classList.add("btn-primary");
				form.action="<?php echo base_url()?>clases/modificar_clases";
				$("html, body").animate({ scrollTop: 0 }, 100);
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_clase(id){
		Swal.fire({
			title:'Seguro que quiere borrar esta clase?',
			icon:'question',
			denyButtonText: 'No',
			confirmButtonText:'Si',
			showDenyButton:true
		}).then((result)=>{
			if (result.isConfirmed) {
				$.ajax({
					url:"<?php echo base_url()?>clases/eliminar_clases",
					type:"POST",
					data:{id:id},
					success:function(resp){
						var resp3=JSON.parse(resp);
						if(resp3.success){
							Swal.fire({
								title:'Se elimino el registro',
								icon:'success',
								confirmButtonText:'aceptar',
								confirmButtonColor: '#666666',
							}).then(function(result){
								location.reload();
							})
						}//mensaje de la bdd
					},error:function(){
						$('#mensaje').text('Error al conectarse con el servidor');
					}
				});
			}else if(result.isDenied){
				Swal.fire('No se elimino el registro','','warning')
			}
		});
	}
</script>
<?php
	$this->endSection();
?>