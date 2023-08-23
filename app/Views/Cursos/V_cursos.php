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
			<form method="post" accept-charset="utf-8" name="form_curso" id="form_curso" action="<?php base_url() ?>cursos/registrar_curso">
				<input type="text" name="id" id="id" hidden>
				<div class="row">
					<div class="col-sm-3 form-item"></div>
					<div class="col-sm-6 form-item">
						<label for="nombre_curso" class="form-label">Nombre curso: </label>
						<input type="text" class="form-control" name="nombre_curso" id="nombre_curso" placeholder="Agregar nombre del curso" required>
					</div>
				</div>
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
							<th>Nº</th>
							<th>Curso</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_cursos as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->detalle."</td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='editar_curso(".$key->id_categoria.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_curso(".$key->id_categoria.")'><i class='bi bi-trash-fill' title='Editar'></i></button></td>";
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

	function editar_curso(id){
		console.log(id);
		$.ajax({
			url:"<?php echo base_url()?>cursos/mostrar_curso",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);
				if (resp2.success) {
					$(document).ready(function(){
						id=document.getElementById('id');
						nombre_curso=document.getElementById('nombre_curso');
						id.value=resp2.data[0].id_categoria;
						nombre_curso.value=resp2.data[0].detalle;
						//botones
						var btnad=document.getElementById('Registrar');
						var btnmd=document.getElementById('Modificar');
						var form=document.getElementById('form_curso');

						btnad.classList.remove("btn-primary");
						btnmd.classList.add("btn-primary");
						btnad.disabled=true;
						btnmd.disabled=false;
						form.action="<?php echo base_url()?>cursos/modificar_curso";

					});
				}//mensaje de la bdd
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_curso(id){
		$.ajax({
			url:"<?php echo base_url()?>cursos/mostrar_curso",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				var inf=resp2.data[0].detalle;
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
								url:"<?php echo base_url()?>cursos/eliminar_curso",
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