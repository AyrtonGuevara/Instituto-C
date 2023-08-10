<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
	
?>

<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Personal</h2>
		</div>
			<div class="card-body" id="card-forms">
				<form action="<?php echo base_url()?>personal/agregar_personal" method="POST" name="form_personal" id="form_personal" class="form_control" accept-charset="utf-8">

							<div class="row">
								<input type="text" name="id" id="id" hidden>
								<div class="col-sm-6">
									<label for="nombre" class="form-label">Nombre:</label>
									<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required/>
								</div>
								<div class="col-sm-3">
									<label for="apellidoP" class="form-label">Apellido Paterno:</label>
									<input type="text" class="form-control" name="apellidoP" id="apellidoP" placeholder="Apellido Paterno" required/>
								</div>
								<div class="col-sm-3">
									<label for="apellidoM" class="form-label">Apellido Materno:</label>
									<input type="text" class="form-control" name="apellidoM" id="apellidoM" placeholder="Apellido Materno" required/>
								</div>
								<div class="col-sm-3">
									<label for="fecnac" class="form-label">Fecha de Nacimiento:</label>
									<input type="date" class="form-control" name="fecnac" id="fecnac" required/>
								</div>
								<div class="col-sm-3">
									<label for="celular" class="form-label">Celular:</label>
									<input type="number" class="form-control" name="celular" id="celular" placeholder="Celular" required/>
								</div>
								<div class="col-sm-3">
									<label for="cargo" class="form-label">Cargo:</label>
									<select class="form-control" name="cargo" id="cargo" required>
										<?php foreach($cargos->getResult() as $key){?>
											<option value="" default id="defaul"></option>
											<option value="<?php echo $key->id_categoria?>"><?php echo $key->detalle?></option>
										<?php }?>
									</select>
								</div>
							</div>
						<div class="btn-form">
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
								<th>Nombre</th>
								<th>Celular</th>
								<th>Cargo</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
								<?php
								foreach ($list->getResult() as $key) {
									echo "<tr><td>".$key->nro."</td>";
									echo "<td>".$key->nombre."</td>";
									echo "<td>".$key->celular."</td>";
									echo "<td>".$key->cargo."</td>";
									echo "<td><button class='btn btn-warning' name='Modificar' value='Modificar' onclick='modificar_personal(".$key->id_personal.")'><i class='bi bi-pen-fill' title='Editar'></i></button>
										<button class='btn btn-danger' name='Eliminar' value='Eliminar' onclick=eliminar_personal(".$key->id_personal.")><i class='bi bi-trash-fill' title='Eliminar'></i></button></td></tr>";
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
	function modificar_personal(id){
		$.ajax({
			url:"<?php base_url()?>personal/mostrar_personal",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				if (resp2.success) {
					id=document.getElementById('id');
					nombre=document.getElementById('nombre');
					apellidoP=document.getElementById('apellidoP');
					apellidoM=document.getElementById('apellidoM');
					fecnac=document.getElementById('fecnac');
					celular=document.getElementById('celular');
					cargo=document.getElementById('defaul');
					id.value=resp2.data[0].id_personal;
					nombre.value=resp2.data[0].nom_persona;
					apellidoP.value=resp2.data[0].ap_pat_persona;
					apellidoM.value=resp2.data[0].ap_mat_persona;
					fecnac.value=resp2.data[0].fec_nacimiento;
					celular.value=resp2.data[0].celular;
					cargo.value=resp2.data[0].puesto;
					cargo.textContent=resp2.data[0].detalle;
					//botones
					modificar=document.getElementById('Modificar');
					agregar=document.getElementById('Registrar');
					metodo=document.getElementById("form_personal");
					modificar.classList.add("btn-primary");
					agregar.classList.remove("btn-primary");
					modificar.disabled=false;
					agregar.disabled=true;
					metodo.action="<?php echo base_url()?>personal/modificar_personal";
				}
			}
		});
	}
	function eliminar_personal(id){
		$.ajax({
			url:"<?php echo base_url()?>personal/mostrar_personal",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp2=JSON.parse(resp);
				Swal.fire({
					title:'Seguro que quiere eliminar el registro de :',
					text:resp2.data[0].nom_persona+' '+resp2.data[0].ap_pat_persona+' '+resp2.data[0].ap_mat_persona,
					icon:'question',
					confirmButtonText:'Si',
					denyButtonText:'No',
					showDenyButton:true
				}).then((results)=>{
					if (results.isConfirmed) {
						$.ajax({
							url:"<?php echo base_url()?>personal/eliminar_personal",
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
<?
	$this->endSection('');
?>