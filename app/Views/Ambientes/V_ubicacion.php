<?php
/*
	Ayrton Guevara Montaño 30/07/2023
	
*/
	$this->extend('Template/Head');
	$this->section('content');
?>


<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Ubicaci&oacute;n</h2>
		</div>
		<div class="card-body" id="card-forms">
			<form method="post" accept-charset="utf-8" name="form_ubicacion" id="form_ubicacion" action="<?php base_url() ?>ambientes/registrar_ubicacion">
				<div class="row">
					<input type="text" name="id" id="id" hidden>
					<div class="col-sm-6 form-item">
						<label for="zona" class="form-label">Zona:</label>
						<input type="text" class="form-control" name="zona" id="zona" placeholder="Zona" required/>
					</div>
					<div class="col-sm-6 form-item">
						<label for="direccion" class="form-label">Direcci&oacute;n:</label>
						<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Direcci&oacute;n" required/>
					</div>
					<div class="col-sm-6 form-item">
						<label for="detalle" class="form-label">Detalle:</label>
						<input type="text" class="form-control" name="detalle" id="detalle" placeholder="Detalle">
					</div>
					<div class="col-sm-6 form-item">
						<label for="descripcion" class="form-label">Descripci&oacute;n:</label>
						<input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripci&oacute;n">
					</div class="btn-form">
					<div class="col-sm-12">
						<h3 class="second_form_title">Aulas</h3>
						<div class="btn btn-form-plus" onclick="aula_mas_uno()" name="horauno" id="horauno"><i class="bi bi-plus-circle-fill"></i></div>
					</div>
					<div class="col-sm-5 form-item">
						<label for="nombre_aula0" class="form-label">Nombre del aula:</label>
						<input type="text" class="form-control" name="nombre_aula[]" id="nombre_aula0" placeholder="Nombre del aula" >
					</div>
					<div class="col-sm-5 form-item">
						<label for="detalle_aula0" class="form-label">Descripcion del aula:</label>
						<input type="text" class="form-control" name="detalle_aula[]" id="detalle_aula0" placeholder="Detalle del aula" >
					</div>
					<div id="nuevas_aulas0">
						
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
							<th>Zona</th>
							<th>Direcci&oacute;n</th>
							<th>Detalle</th>
							<th>Descripci&oacute;n</th>
							<th>Aulas</th>
							<th>detalle</aulas>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
							<?php
							foreach ($list->getResult() as $key) {
								echo "<tr>";
								echo "<td>".$key->nro."</td>";
								echo "<td>".$key->zona."</td>";
								echo "<td>".$key->direccion."</td>";
								echo "<td>".$key->detalle."</td>";
								echo "<td>".$key->descripcion."</td>";
								echo "<td>";
								$a=explode(' || ', $key->nombre_aula);
								//print_r($a);
								foreach ($a as $key2) {
								    echo "- $key2<br>";
								}
								echo "</td>";
								echo "<td>";
								$a=explode(' || ', $key->detalle_aula);
								foreach ($a as $key2) {
								    echo "- $key2<br>";
								}
								echo "</td>";
								echo "<td> <button class='btn btn-warning' name='Editar' value='Editar' onclick='mostrar_ubicacion(".$key->id_ubicacion.")'><i class='bi bi-pen-fill' title='Editar'></i></button>";
								echo "<button class= 'btn btn-danger' name='Eliminar' value='Eliminar' onclick='eliminar_ubicacion(".$key->id_ubicacion.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button>";
								echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' onclick='modal_ubicacion_aulas(".$key->id_ubicacion.")' data-bs-target='#modal_aulas'> <i class='bi bi-card-checklist' title='Modificar aulas'></i></button></td>";
								echo "</tr>";
							}
							?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--MODAL-->
<div class="modal fade" id="modal_aulas">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
    	<div class="modal-content">

      		<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h3 class="modal-title">Aulas :</h3>
	        	<h3 class="modal-title" id="modal_titulo_direccion"></h3>
	        	<div class="btn btn-form-plus" onclick="modal_agregar_aulas()" name="horauno" id="horauno"><i class="bi bi-plus-circle-fill"></i></div>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      	</div>	

	      	<!-- Modal body -->
	      	<div class="modal-body">
	      		<form action="<?php base_url()?>ambientes/modal_editar_aulas" name="modal_form_aulas" id="modal_form_aulas" method="POST" accept-charset="utf-8">
	      			<input type="text" name="id_ubicacion" id="id_ubicacion" hidden>
		      		<div class="container table-responsive">
			      		<table class="table table-hover table-basic">
			      			<caption>table title and/or explanatory text</caption>
			      			<thead>
			      				<tr>
			      					<th>Nº</th>
			      					<th>Aula</th>
			      					<th>Descripci&oacute;n</th>
			      					<th>Acciones</th>
			      				</tr>
			      			</thead>
			      			<tbody id="tbody_aulas">
			      			</tbody>
			      		</table>
			      		
			      	</div>
			      	<input type="submit" class="btn btn-primary" name="editar_aulas" value="Aceptar">
			      </form>
	      	</div>
	      	<!-- Modal footer -->
	      	<div class="modal-footer">
	        	<button type="button" id="cerrar_modal" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      	</div>
		</div>
	</div>
</div>

<script>
	const button_close=document.getElementById("cerrar_modal");
	let contador_aulas=0;
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
	button_close.addEventListener("click", function(){
		console.log(contador_aulas);
		contador_aulas=0;
		console.log(contador_aulas);
	});
	function mostrar_ubicacion(id){
		$.ajax({
			url:"<?php echo base_url()?>ambientes/mostrar_ubicacion",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);

				if (resp2.success) {
					$(document).ready(function(){
						id=document.getElementById('id');
						zona=document.getElementById('zona');
						direccion=document.getElementById('direccion');
						detalle=document.getElementById('detalle');
						descripcion=document.getElementById('descripcion');
						id.value=resp2.data[0].id_ubicacion;
						zona.value=resp2.data[0].zona;
						direccion.value=resp2.data[0].direccion;
						detalle.value=resp2.data[0].detalle;
						descripcion.value=resp2.data[0].descripcion;
						console.log(resp2.data[0].id_ubicacion);
						//botones
						var btnad=document.getElementById('Registrar');
						var btnmd=document.getElementById('Modificar');
						var form=document.getElementById('form_ubicacion');

						btnad.classList.remove("btn-primary");
						btnmd.classList.add("btn-primary");
						btnad.disabled=true;
						btnmd.disabled=false;
						form.action="<?php echo base_url()?>ambientes/molificar_ubicacion";

					});
				}//mensaje de la bdd
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_ubicacion(id){
		$.ajax({
			url:"<?php echo base_url()?>ambientes/mostrar_ubicacion",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				var inf=resp2.data[0].direccion;
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
								url:"<?php echo base_url()?>ambientes/eliminar_ubicacion",
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
	function aula_mas_uno(){	
		contador_aulas++;
		div_nueva_aula=document.getElementById("nuevas_aulas"+(contador_aulas-1));
		div_nueva_aula.innerHTML += "<div class='row'>"+
					"<div class='col-sm-5 form-item'>"+
						"<label for='nombre_aula"+contador_aulas+"' class='form-label'>Nombre del aula:</label>"+
						"<input type='text' class='form-control' name='nombre_aula[]' id='nombre_aula"+contador_aulas+"' placeholder='Nombre del aula'>"+
					"</div>"+
					"<div class='col-sm-5 form-item'>"+
						"<label for='detalle_aula"+contador_aulas+"' class='form-label'>Descripcion del aula:</label>"+
						"<input type='text' class='form-control' name='detalle_aula[]' id='detalle_aula"+contador_aulas+"' placeholder='Detalle aula'>"+
					"</div>"+
					"<div class='col-sm-2'>"+
						"<div class='btn btn-form-trash' onclick='aula_menos_uno("+contador_aulas+")' name='aula_menos_uno' id='aula_menos_uno'><i class='bi bi-trash-fill' title='Eliminar'></i></div>"+
					"</div>"+
				"</div>"+
			"<div id='nuevas_aulas"+contador_aulas+"'></div>";
	}
	function aula_menos_uno(id){
		div_menos=document.getElementById("nuevas_aulas"+(id-1));
		div_menos.innerHTML="";
		contador_aulas=(id-1);
	}
	function modal_ubicacion_aulas(id){
		contador=0;
		$.ajax({
			url:'<?php echo base_url()?>ambientes/modal_mostrar_aulas',
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp=JSON.parse(resp);
				var contador2 = 0;
				const resp2 = resp.data;
				direccion=document.getElementById("modal_titulo_direccion");
				id_direccion=document.getElementById("id_ubicacion");
				direccion.textContent=resp.data[0].direccion;
				id_direccion.value=resp.data[0].id_ubicacion;
				for(const item of resp2){ 
					modal_agregar_aulas();
					id=document.getElementById("id_aula"+contador2);
					nro=document.getElementById("nro_aula"+contador2);
					nombre=document.getElementById("modal_nombre_aula"+contador2);
					detalle=document.getElementById("modal_detalle_aula"+contador2);
					
					id.value=item.id_aula;
					nro.value=item.nro;
					nombre.value=item.nombre_aula;
					detalle.value=item.descripcion;
					contador2++;
				}
			}
		});
	}
	function modal_agregar_aulas(){
		contador_aulas++;
		var modal_aulas=document.getElementById("tbody_aulas");
	    // Crea una nueva fila
	    var nuevaFila = modal_aulas.insertRow();
	    // Crea celdas y agrega contenido
	    var celda1 = nuevaFila.insertCell(0);
	    celda1.innerHTML = "<input type='text' name='id_aula[]' id='id_aula"+(contador_aulas-1)+"' hidden><input type='text' class='form-control' name='modal_numaula' id='nro_aula"+(contador_aulas-1)+"' disabled>";
	    var celda2 = nuevaFila.insertCell(1);
	    celda2.innerHTML = "<input type='text' class='form-control' name='modal_nombre_aula[]' id='modal_nombre_aula"+(contador_aulas-1)+"' placeholder='Nombre del aula'>";
	    var celda3 = nuevaFila.insertCell(2);
	    celda3.innerHTML = "<input type='text' class='form-control' name='modal_detalle_aula[]' id='modal_detalle_aula"+(contador_aulas-1)+"' placeholder='Detalle del aula'>";
	    var celda4 = nuevaFila.insertCell(3);
	    celda4.innerHTML = "<button class= 'btn btn-danger' name='Eliminar' value='Eliminar' onclick='modal_eliminar_fila(this)'><i class='bi bi-trash-fill' title='Eliminar'></i></button>";
	}
	function modal_eliminar_fila(boton){
		var fila=boton.parentNode.parentNode;
		fila.parentNode.removeChild(fila);
	}
</script>

<?php
	$this->endSection();
?>