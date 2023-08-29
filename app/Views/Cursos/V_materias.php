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
			<h2>Materias</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_materia" id="form_materia" action="<?php base_url() ?>materias/registrar_materia">
				<input type="text" name="id" id="id" hidden>
				<div class="row">
					<div class="col-sm-5 form-item">
						<label for="curso" class="form-label">Curso:</label>
						<select class="form-control" name="curso" id="curso" required>
							<option id="default" default></option>
							<?php
							foreach ($lista_cursos as $key) {
								echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
							}
							?>
							option
						</select>
					</div>
					<div class="col-sm-5 form-item">
						<label for="nombre_materia" class="form-label">Nombre de la Materia:</label>
						<input type="text" class="form-control" name="nombre_materia" id="nombre_materia" placeholder="Nombre de la materia" required>
					</div>
					<div class="col-sm-2 form-item">
						<label for="precio" class="form-label">Precio:</label>
						<input type="number" class="form-control" id="precio" name="precio" placeholder="Precio">
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
							<th>Nro</th>
							<th>Clase</th>
							<th>Materia</th>
							<th>Precio</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_materias as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->tipo_materia."</td>";
							echo "<td>".$key->nombre_materia."</td>";
							echo "<td>".$key->precio." Bs.</td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='modificar_materia(".$key->id_materia.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_materia(".$key->id_materia.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
	})
	function modificar_materia(id){
		console.log(id);
		$.ajax({
			url:"<?php echo base_url()?>materias/mostrar_materia",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);
				if (resp2.success) {
					$(document).ready(function(){
						id=document.getElementById('id');
						id_tipo=document.getElementById('default');
						nombre_materia=document.getElementById('nombre_materia');
						precio=document.getElementById('precio');

						id.value=resp2.data[0].id_materia;
						id_tipo.value=resp2.data[0].id_tipo_materia;
						id_tipo.textContent=resp2.data[0].nombre_tipo_materia;
						nombre_materia.value=resp2.data[0].nombre_materia;
						precio.value=resp2.data[0].precio;
						//botones
						var btnad=document.getElementById('Registrar');
						var btnmd=document.getElementById('Modificar');
						var form=document.getElementById('form_materia');

						btnad.classList.remove("btn-primary");
						btnmd.classList.add("btn-primary");
						btnad.disabled=true;
						btnmd.disabled=false;
						form.action="<?php echo base_url()?>materias/modificar_materia";

					});
				}//mensaje de la bdd
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_materia(id){
		$.ajax({
			url:"<?php echo base_url()?>materias/mostrar_materia",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				var inf=resp2.data[0].nombre_materia+" de los cursos de "+resp2.data[0].nombre_tipo_materia;
				if (resp2.success) {
					Swal.fire({
						title:'Seguro que quiere borrar el registro de :',
						text: inf,
						icon:'question',
						denyButtonText: 'No',
						confirmButtonText:'Si',
						showDenyButton:true
					}).then((result)=>{
						if (result.isConfirmed) {
							$.ajax({
								url:"<?php echo base_url()?>materias/eliminar_materia",
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