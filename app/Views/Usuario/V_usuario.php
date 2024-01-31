<?php
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
			<div class="card-header">
				<h2>Usuario</h2>
				<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
			</div>
			<div class="card-body" id="card-forms">

				<form action="<?php echo base_url()?>usuario/registrar_usuario" class="form" method="post" id="form_usuario" name="form_usuario" accept-charset="utf-8">
					<div class="row">
						<input type="text" id="input_id" name="id" hidden>
						<div class="col-sm-4 form-item">
							<label for="persona" class="form-label">Usuario:</label>
							<select name="persona" id="persona" class="form-control" required>
								<option value="" id="input_default" default></option>
								<?php 
								foreach ($persona->getResult() as $key) {
									echo "<option value=".$key->id_persona.">".$key->nombre."</option>";
								}
								?>
							</select>
						</div>
						<div class="col-sm-4 form-item">
							<label for="usuario" class="form-label">Nombre usuario:</label>
							<input type="text" class="form-control" name="usuario" id="input_usuario" placeholder="Nombre de usuario" required/>
						</div>
						<div class="col-sm-4 form-item">
							<label for="password" class="form-label">Contraseña:</label>
							<input type="password" class="form-control" name="password" id="input_password" placeholder="Contraseña" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4 form-item">
							<label for="nivel" class="form-label">Nivel:</label>
							<select name="nivel" class="form-control"  required>
								<option value="" id="input_default2" default></option>
								<?php 
								foreach ($nivel->getResult() as $key) {
									echo "<option value=".$key->id_cargo.">".$key->cargo."</option>";
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
						<caption></caption>
						<thead>
							<tr>
								<th>Nº</th>
								<th>Usuario</th>
								<th>ID</th>
								<th>Nivel</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($lista as $key){
								echo "<tr>";
								echo "<td>".$key->nro."</td>";
								echo "<td>".$key->nombre."</td>";
								echo "<td>".$key->usuario."</td>";
								echo "<td>".$key->cargo."</td>";
								echo "<td><button class='btn btn-warning' name='modificar_usuario' value='Modificar' onclick='modificar_usuario(".$key->id_usuario.")'><i class='bi bi-pen-fill' title='Editar'></i></button>
									<button class='btn btn-danger' name='eliminar_usuario' value='Eliminar' onclick='eliminar_usuario(".$key->id_usuario.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
				?>
				//fracaso
				Swal.fire({
					title:'Error en el registro',
					icon:'Error',
					confirmButtonColor:'#111111',
					confirmButtonText:'Aceptar'
				})
				<?php
			}else{}
		?>
	});

	function modificar_usuario(id){
		$.ajax({
			url:"<?php echo base_url()?>usuario/mostrar_usuario",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp2 =JSON.parse(resp);
				id=document.getElementById('input_id');
				usuario=document.getElementById('input_default');
				nombre=document.getElementById('input_usuario');
				nivel=document.getElementById('input_default2');
				id.value=resp2.data[0].id_usuario;
				usuario.value=resp2.data[0].id_persona;
				usuario.textContent=resp2.data[0].concat;
				nombre.value=resp2.data[0].usuario;
				nivel.value=resp2.data[0].nivel;
				nivel.textContent=resp2.data[0].detalle;
				//botones
				btnexit=document.getElementById('salir_edicion');
				btnagregar=document.getElementById("Registrar");
				btnmodificar=document.getElementById("Modificar");
				form=document.getElementById("form_usuario");

				btnagregar.classList.remove("btn-primary");
				btnmodificar.classList.add("btn-primary");
				btnagregar.disabled=true;
				btnmodificar.disabled=false;
				btnexit.hidden=false;
				form.action="<?php echo base_url()?>usuario/modificar_usuario";
				$("html, body").animate({ scrollTop: 0 }, 100);
			}
		});
	}
	function eliminar_usuario(id){
		$.ajax({
			url:"<?php echo base_url()?>usuario/mostrar_usuario",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp2=JSON.parse(resp);
				Swal.fire({
					title:'Seguro que quiere eliminar el registro de :',
					text:resp2.data[0].concat,
					icon:'question',
					confirmButtonText:'Si',
					denyButtonText:'No',
					showDenyButton:true
				}).then((results)=>{
					if (results.isConfirmed) {
						$.ajax({
							url:"<?php echo base_url()?>usuario/eliminar_usuario",
							type:"POST",
							data:{id:id},
							success:function(resp){
								Swal.fire({
									title:"Se elimino el registro",
									icon:"success",
									confirmButtonText:"Aceptar",
									confirmButtonColor:"#111111",
								}).then(function(result){
									location.reload();
								})
							},error:function(){
								$('#mesaje').text('Error al conectarse con el servidor');
							}
						});
					}else if(results.isDenied){
						Swal.fire('No se pudo eliminar','','warning')
					}
				})
			},error:function(){
				$('#mensaje').text('Error al conectar con el servidor');
			}
		});
	}
	function limpiar_form(){
		limpieza_form();
		form=document.getElementById("form_usuario");
		form.action="<?php echo base_url()?>usuario/registrar_usuario";
	}
</script>
<?php
	$this->endSection();
?>