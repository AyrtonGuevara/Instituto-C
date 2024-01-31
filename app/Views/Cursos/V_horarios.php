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
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_curso" id="form_curso" action="<?php base_url() ?>horarios/registrar_horarios">
				<input type="text" name="id" id="input_id" hidden>
				<div class="row">
					<div class="col-sm-10">
						<div class="row">
							<input type="text" name="id_horario[]" id="input_id_horario0" hidden>
							<div class="col-sm-4 form-item">
								<label for="dia" class="form-label">D&iacute;a</label>
								<select name="dia[]" id="dia" class="form-control"required>
									<option id="input_default0" value="" default></option>
									<?php
									foreach ($dia->getResult() as $key) {
									echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
										}
									?>
								</select>
							</div>
							<div class="col-sm-4 form-item">
								<label for="horario" class="form-label">Hora inicio:</label>
								<input type="time" class="form-control" id="input_hora_inicio0" name="horario_inicio[]" required >
							</div>
							<div class="col-sm-4 form-item">
								<label for="horario" class="form-label">Hora fin:</label>
								<input type="time" class="form-control" id="input_hora_fin0" name="horario_fin[]" required>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="btn btn-form-plus" onclick="horario_mas_uno()" name="horauno" id="horauno"><i class="bi bi-plus-circle-fill"></i></div>
					</div>
				</div>
				<div id="nuevo_horario0" class="form-item"></div>
				<div class="form-item btn-form">
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
							<th>D&iacute;as</th>
							<th>Horarios</th>
							<th>Estado</th>
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_horarios as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>";
								$a=explode(',', $key->dias);
								//print_r($a);
								foreach ($a as $key2) {
								    echo "- $key2<br>";
								}
								echo "</td>";
							echo "<td>";
								$a=explode(',', $key->horarios);
								//print_r($a);
								foreach ($a as $key2) {
								    echo "$key2<br>";
								}
								echo "</td>";
							echo "<td>".$key->estado."</td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='modificar_horarios(".$key->id_conf_horarios.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_horarios(".$key->id_conf_horarios.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
	var contador_btn_plus=0;
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
		function horario_mas_uno(){
		contador_btn_plus++;
	    div_hora = document.getElementById("nuevo_horario" + (contador_btn_plus - 1));
	    div_hora.innerHTML += "<div class='row'>"+
					"<div class='col-sm-10'>"+
						"<div class='row'>"+
							"<input type='text' name='id_horario[]'' id='input_id_horario"+contador_btn_plus+"' hidden>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='dia' class='form-label'>D&iacute;a</label>"+
								"<select name='dia[]' id='dia' class='form-control'required>"+
									"<option id='input_default"+contador_btn_plus+"' value='' default></option>"+
									"<?php foreach ($dia->getResult() as $key) { echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>"; } ?>"+
								"</select>"+
							"</div>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='horario' class='form-label'>Hora inicio:</label>"+
								"<input type='time' class='form-control' id='input_hora_inicio"+contador_btn_plus+"' name='horario_inicio[]' required >"+
							"</div>"+
							"<div class='col-sm-4 form-item'>"+
								"<label for='horario' class='form-label'>Hora fin:</label>"+
								"<input type='time' class='form-control' id='input_hora_fin"+contador_btn_plus+"' name='horario_fin[]' required>"+
							"</div>"+
						"</div>"+
					"</div>"+
					"<div class='col-sm-2'>"+
						"<div class='btn btn-form-trash' onclick='horario_menos_uno("+contador_btn_plus+")' name='horauno' id='horauno'><i class='bi bi-trash-fill' title='Eliminar'></i></div>"+
					"</div>"+
					"<div id='nuevo_horario"+contador_btn_plus+"'> </div>"+
				"</div>";
	}

	function horario_menos_uno(id){
		div_hora = document.getElementById("nuevo_horario" + (id-1));
		div_hora.innerHTML = "";
		contador_btn_plus=id-1;
	}
	function modificar_horarios(id){
		$.ajax({
			url:"<?php echo base_url()?>horarios/mostrar_horarios",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp=JSON.parse(resp);
				var contador2 = 0;
				const resp2 = resp.data;
				id=document.getElementById("input_id");
				id.value=resp.data[0].id_conf;
				for(const item of resp2){ 
					horario_mas_uno();
					id_horario=document.getElementById("input_id_horario"+contador2);
					dia=document.getElementById("input_default"+contador2);
					hora_inicio=document.getElementById("input_hora_inicio"+contador2);
					hora_fin=document.getElementById("input_hora_fin"+contador2);
					id_horario.value=item.id_horarios;
					dia.textContent=item.detalle_dias;
					dia.value=item.dias;
					hora_inicio.value=item.hora_inicio;
					hora_fin.value=item.hora_fin;
					contador2++;
				}
				horario_menos_uno(contador2);
				//botones
				var btnexit=document.getElementById('salir_edicion');
				var btnad=document.getElementById('Registrar');
				var btnmd=document.getElementById('Modificar');
				var form=document.getElementById('form_curso');
				btnad.classList.remove("btn-primary");
				btnmd.classList.add("btn-primary");
				btnad.disabled=true;
				btnmd.disabled=false;
				btnexit.hidden=false;
				form.action="<?php echo base_url()?>horarios/modificar_horarios";
				$("html, body").animate({ scrollTop: 0 }, 100);
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_horarios(id){
		Swal.fire({
			title:'Seguro que quiere borrar el registro?',
			icon:'question',
			denyButtonText: 'No',
			confirmButtonText:'Si',
			showDenyButton:true
		}).then((result)=>{
			if (result.isConfirmed) {
				$.ajax({
					url:"<?php echo base_url()?>horarios/eliminar_horarios",
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
	function limpiar_form(){
		horario_menos_uno(1);
		limpieza_form();
		var form=document.getElementById('form_curso');
		form.action="<?php echo base_url()?>horarios/agregar_horarios";
	}
	
</script>
<?php
	$this->endSection();
?>