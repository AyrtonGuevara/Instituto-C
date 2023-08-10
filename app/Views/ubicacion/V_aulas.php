<?php
/*
	Ayrton Jhonny Guevara Montaño 10-08-2023

	??categoria??
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Aulas</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form action="<?php base_url('')?>aula/registrar_aula" method="post" id="form_aulas" name="form_aulas" accept-charset="utf-8">
				<div class="row">
					<input type="text" id="id" name="id"hidden>
					<div class="col-sm-4 form-item">
						<label for="ubicacion" class="form-label">Ubicaci&oacute;n</label>
						<select name="ubicacion" id="ubicacion" class="form-control" required>
							<option id="default" default></option>
							<?php
							foreach ($ubicaciones->getResult() as $key) {
								echo "<option value='".$key->id_ubicacion."'>".$key->direccion."</option>";
							}
							?>
						</select>
					</div>
					<div class="col-sm-4 form-item">
						<label for="nombre" class="form-label">Nombre Aula:</label>
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre aula" required>
					</div>
					<div class="col-sm-4 form-item">
						<label for="descripcion" class="form-label">Descripci&oacute;n:</label>
						<input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion" required>
					</div>
				</div>
				<div class="btn-form form-item">
					<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
					<input type="submit" class="btn border-light" name="Modificar" value="Modificar" id="Modificar" disabled="">
				</div>
			</form>
		</div>
		<div class="card-foot">
			<div class="table-responsive">
				<table class="table table-hover table-basic">
					<caption>table title and/or explanatory text</caption>
					<thead>
						<tr>
							<th>Nº</th>
							<th>Ubicaci&oacute;n</th>
							<th>Aula</th>
							<th>Descripci&oacute;n</th>
							<th>Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($aulas->getResult() as $key) {
							echo "<tr>";
							echo "<td>".$key->id_aula."</td>";
							echo "<td>".$key->direccion."</td>";
							echo "<td>".$key->nombre_aula."</td>";
							echo "<td>".$key->descripcion."</td>";
							echo "<td> <button class='btn btn-warning' name='Editar' value='Editar' onclick='mostrar_aula(".$key->id_aula.")'><i class='bi bi-pen-fill' title='Editar'></i></button>";
							echo "<button class= 'btn btn-danger' name='Eliminar' value='Eliminar' onclick='eliminar_aula(".$key->id_aula.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
	document.addEventListener('DOMContentLoaded',function(){
		<?php
		
		if (session()->getFlashData('exito')) {
			?>
			Swal.fire({
				title:'Se registró con exito',
				icon: 'success',
				confirmButonText:'Aceptar',
				confirmButonColor:'#111111'
			})
			<?php
		}else if(session()->getFlashData('fracaso')){
			?>
			Swal.fire({
				title:'Error al registrar',
				icon: 'error',
				confirmButonText:'Aceptar',
				confirmButonColor:'#111111'	
			})
			<?php
		}
		?>
	});
	function mostrar_aula(id){
		$.ajax({
			url:"<?php echo base_url()?>aula/mostrar_aula",
			type: "POST",
			data: {id:id},
			success: function(resp){
				var resp2=JSON.parse(resp);
				if (resp2.success) {
					var id=document.getElementById('id');
					var ubicacion=document.getElementById('default');
					var nombre=document.getElementById('nombre');
					descripcion=document.getElementById('descripcion');
					//botones
					botonagregar=document.getElementById('Registrar');
					botonmodificar=document.getElementById('Modificar');
					form=document.getElementById('form_aulas');
					console.log(resp2.data[0].id_aula);
					console.log(resp2.data[0].id_ubicacion);
					console.log(resp2.data[0].direccion);
					console.log(resp2.data[0].nombre_aula);
					console.log(resp2.data[0].descripcion);
					id.value=resp2.data[0].id_aula;
					ubicacion.value=resp2.data[0].id_ubicacion;
					ubicacion.textContent=resp2.data[0].direccion;
					nombre.value=resp2.data[0].nombre_aula;
					descripcion.value=resp2.data[0].descripcion;
					form.action="<?php echo base_url()?>aula/modificar_aula";
					botonagregar.classList.remove("btn-primary");
					botonmodificar.classList.add("btn-primary");
					botonmodificar.disabled=false;
					botonagregar.disabled=true;
				}
			}
		});
	}
	function eliminar_aula(id){
		console.log("a");
		$.ajax({
			url:"<?php echo base_url()?>aula/mostrar_aula",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				var inf=resp2.data[0].direccion;
				if (resp2.success) {
					Swal.fire({
						title:'Seguro que quiere borrar el registro de :',
						text: 'Aula: '+resp2.data[0].nombre_aula+' de: '+resp2.data[0].direccion,
						icon:'question',
						denyButtonText: 'No',
						confirmButtonText:'Si',
						showDenyButton:true
					}).then((result)=>{
						if (result.isConfirmed) {
							$.ajax({
								url:"<?php echo base_url()?>aula/eliminar_aula",
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
				}//mensaje de la bdd
			},error:function(){
				$('#respuesta').text('Error al conectarse con el servidor');
			}
		});
	}
</script>
<?php
	$this->endSection();
?>