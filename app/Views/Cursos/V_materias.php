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
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_materia" id="form_materia" action="<?php base_url() ?>materias/registrar_materia">
				<input type="text" name="id" id="input_id" hidden>
				<div class="row">
					<div class="col-sm-6 form-item">
						<label for="curso" class="form-label">Curso:</label>
						<select class="form-control" name="curso" id="curso" required>
							<option id="input_default" default></option>
							<?php
							foreach ($lista_cursos as $key) {
								echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
							}
							?>
							option
						</select>
					</div>
					<div class="col-sm-6 form-item">
						<label for="nombre_materia" class="form-label">Nombre de la Materia:</label>
						<input type="text" class="form-control" name="nombre_materia" id="input_nombre_materia" placeholder="Nombre de la materia" required>
					</div>
					<div class="col-sm-9 form-item">
						<label for="detalle_materia" class="form-label">Descripci&oacute;n de la Materia:</label>
						<input type="text" class="form-control" name="detalle_materia" id="input_detalle_materia" placeholder="Detalle de la materia" required>
					</div>
					<div class="col-sm-3 form-item">
						<label for="precio" class="form-label">Precio:</label>
						<input type="number" class="form-control" id="input_precio" name="precio" placeholder="Precio">
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
					<caption></caption>
					<thead>
						<tr>
							<th>Nro</th>
							<th>Curso</th>
							<th>Materia</th>
							<th>Descripci&oacute;n</th>
							<th>Precio</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_materias as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->curso."</td>";
							echo "<td>".$key->nombre_materia."</td>";
							echo "<td>".$key->detalle."</td>";
							echo "<td>".$key->precio."</td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='modificar_materia(".$key->id_precios.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_materia(".$key->id_precios.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
		$.ajax({
			url:"<?php echo base_url()?>materias/mostrar_materia",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);
				if (resp2.success) {
					$(document).ready(function(){
						id=document.getElementById('input_id');
						curso=document.getElementById('curso');
						id_tipo=document.getElementById('input_default');
						nombre_materia=document.getElementById('input_nombre_materia');
						detalle_materia=document.getElementById('input_detalle_materia');
						precio=document.getElementById('input_precio');

						id.value=resp2.data[0].id_precios;
						id_tipo.value=resp2.data[0].id_categoria;
						id_tipo.textContent=resp2.data[0].nombre_curso;
						curso.disabled=true;
						nombre_materia.value=resp2.data[0].nombre_materia;
						nombre_materia.disabled=true;
						detalle_materia.value=resp2.data[0].detalle;
						precio.value=resp2.data[0].precio;
						//botones
						var btnexit=document.getElementById('salir_edicion');
						var btnad=document.getElementById('Registrar');
						var btnmd=document.getElementById('Modificar');
						var form=document.getElementById('form_materia');

						btnad.classList.remove("btn-primary");
						btnmd.classList.add("btn-primary");
						btnad.disabled=true;
						btnmd.disabled=false;
						btnexit.hidden=false;
						form.action="<?php echo base_url()?>materias/modificar_materia";
						$("html, body").animate({ scrollTop: 0 }, 100);
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
	$('#input_nombre_materia').autocomplete({
		source:function(request,response){
			$.ajax({
				url:"<?php echo base_url()?>materias/autocompletar_materia",
				type:"POST",
				data:{request:request.term},
				success:function(resp){
					resp=JSON.parse(resp);
					var nombres = resp.data.map(function(item) {
			            return item.nombre_materia;
			        });
			        response(nombres);
				}
			})
		}
	})
	function limpiar_form(){
		limpieza_form();
		curso=document.getElementById('curso');
		curso.disabled=false
		var form=document.getElementById('form_materia');
		form.action="<?php echo base_url()?>materias/registrar_materia";
	}

</script>
<?php
	$this->endSection();
?>