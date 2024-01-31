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
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
			<div class="card-body" id="card-forms">
				<form action="<?php echo base_url()?>personal/agregar_personal" method="POST" name="form_personal" id="form_personal" class="form_control" accept-charset="utf-8">
							<div class="row">
								<input type="text" name="id" id="input_id" hidden>
								<div class="col-sm-6 form-item">
									<label for="nombre" class="form-label">Nombre:</label>
									<input type="text" class="form-control" name="nombre" id="input_nombre" placeholder="Nombre" required/>
								</div>
								<div class="col-sm-3 form-item">
									<label for="apellidoP" class="form-label">Apellido Paterno:</label>
									<input type="text" class="form-control" name="apellidoP" id="input_apellidoP" placeholder="Apellido Paterno" required/>
								</div>
								<div class="col-sm-3 form-item">
									<label for="apellidoM" class="form-label">Apellido Materno:</label>
									<input type="text" class="form-control" name="apellidoM" id="input_apellidoM" placeholder="Apellido Materno" required/>
								</div>
								<div class="col-sm-3 form-item">
									<label for="fecnac" class="form-label">Fecha de Nacimiento:</label>
									<input type="date" class="form-control" name="fecnac" id="input_fecnac" required/>
								</div>
								<div class="col-sm-3 form-item">
									<label for="celular" class="form-label">Celular:</label>
									<input type="number" class="form-control" name="celular" id="input_celular" placeholder="Celular" required/>
								</div>
								<div class="col-sm-3 form-item">
									<label for="cargo" class="form-label">Cargo:</label>
									<select class="form-control" name="cargo" id="cargo" required>
										<option value="" default id="input_defaul"></option>
										<?php foreach($cargos->getResult() as $key){?>
											<option value="<?php echo $key->id_categoria?>"><?php echo $key->detalle?></option>
										<?php }?>
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
								<th>Nombre</th>
								<th>Celular</th>
								<th>Cargo</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
								<?php
								foreach ($lista as $key) {
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
	function modificar_personal(id){
		$.ajax({
			url:"<?php base_url()?>personal/mostrar_personal",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				if (resp2.success) {
					id=document.getElementById('input_id');
					nombre=document.getElementById('input_nombre');
					apellidoP=document.getElementById('input_apellidoP');
					apellidoM=document.getElementById('input_apellidoM');
					fecnac=document.getElementById('input_fecnac');
					celular=document.getElementById('input_celular');
					cargo=document.getElementById('input_defaul');
					id.value=resp2.data[0].id_personal;
					nombre.value=resp2.data[0].nom_persona;
					apellidoP.value=resp2.data[0].ap_pat_persona;
					apellidoM.value=resp2.data[0].ap_mat_persona;
					fecnac.value=resp2.data[0].fec_nacimiento;
					celular.value=resp2.data[0].celular;
					cargo.value=resp2.data[0].puesto;
					cargo.textContent=resp2.data[0].detalle;
					//botones
					btnexit=document.getElementById('salir_edicion');
					modificar=document.getElementById('Modificar');
					agregar=document.getElementById('Registrar');
					form=document.getElementById("form_personal");
					modificar.classList.add("btn-primary");
					agregar.classList.remove("btn-primary");
					modificar.disabled=false;
					agregar.disabled=true;
					btnexit.hidden=false;
					form.action="<?php echo base_url()?>personal/modificar_personal";
					$("html, body").animate({ scrollTop: 0 }, 100);
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
	function limpiar_form(){
		limpieza_form();
		form=document.getElementById("form_personal");
		form.action="<?php echo base_url()?>personal/registrar_personal";
	}
</script>
<?
	$this->endSection('');
?>