<?php
	$this->extend('Template/Head');
	$this->section('content');
?>
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
</script>
<div class="container">
	<form action="<?php echo base_url()?>usuario/registrar_usuario" class="form" method="post" id="form_usuario" name="form_usuario" accept-charset="utf-8">
		<div class="row">
			<input type="text" id="id" name="id" hidden>
			<div class="col-sm-4">
				<label for="persona" class="form-label">Usuario:</label>
				<select name="persona" id="persona" class="form-control" required>
					<option value="" id="default" default></option>
					<?php 
					foreach ($persona->getResult() as $key) {
						echo "<option value=".$key->id_persona.">".$key->nombre."</option>";
					}
					?>
				</select>
			</div>
			<div class="col-sm-4">
				<label for="usuario" class="form-label">Nombre usuario:</label>
				<input type="text" class="form-control" name="usuario" id="usuario" required/>
			</div>
			<div class="col-sm-4">
				<label for="password" class="form-label">Contraseña:</label>
				<input type="password" class="form-control" name="password" id="password" required>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<label for="nivel" class="form-label">Nivel:</label>
				<select name="nivel" class="form-control" required>
					<option value="" id="default2" default></option>
					<?php 
					foreach ($nivel->getResult() as $key) {
						echo "<option value=".$key->id_categoria.">".$key->detalle."</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div>
			<input type="submit" class="btn btn-info" name="Registrar" value="Registrar" id="Registrar">
			<input type="submit" class="btn btn-info" name="Modificar" value="Modificar" id="Modificar" disabled>
		</div>
	</form>
</div>
<div class="container">
	<table class="table">
		<caption>table title and/or explanatory text</caption>
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
			foreach($list->getResult() as $key){
				echo "<tr>";
				echo "<td>".$key->nro."</td>";
				echo "<td>".$key->concat."</td>";
				echo "<td>".$key->usuario."</td>";
				echo "<td>".$key->detalle."</td>";
				echo "<td><input type='button' class='btn btn-danger' name='modificar_usuario' value='Modificar' onclick='modificar_usuario(".$key->id_usuario.")''>
					<input type='button' class='btn btn-danger' name='eliminar_usuario' value='Eliminar' onclick='eliminar_usuario(".$key->id_usuario.")'></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
<script>
	function modificar_usuario(id){
		$.ajax({
			url:"<?php echo base_url()?>usuario/mostrar_usuario",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp2 =JSON.parse(resp);
				id=document.getElementById('id');
				usuario=document.getElementById('default');
				nombre=document.getElementById('usuario');
				nivel=document.getElementById('default2');
				id.value=resp2.data[0].id_usuario;
				console.log(resp2.data[0].id_usuario);
				usuario.value=resp2.data[0].id_persona;
				usuario.textContent=resp2.data[0].concat;
				nombre.value=resp2.data[0].usuario;
				nivel.value=resp2.data[0].nivel;
				nivel.textContent=resp2.data[0].detalle;
				//botones
				btnagregar=document.getElementById("Registrar");
				btnmodificar=document.getElementById("Modificar");
				form=document.getElementById("form_usuario");
				btnagregar.disabled=true;
				btnmodificar.disabled=false;
				form.action="<?php echo base_url()?>usuario/modificar_usuario";
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
</script>
<?php
	$this->endSection();
?>