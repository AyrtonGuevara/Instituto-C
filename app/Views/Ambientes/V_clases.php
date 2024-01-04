<?php
/*
	Ayrton Jhonny Guevara Montaño 14-09-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>

<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Lista clases</h2>
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-body"id="card-forms">
			<form action="<?php base_url()?>clases/registrar_clases" name="form_clase" id="form_aulas" method="POST" accept-charset="utf-8">
			      			<input type="text" name="id" id="id" hidden>
				      		<div class="row">
				      			<div class="col-sm-6 form-item">
				      				<label for="materia" class="form-label">Materia</label>
				      				<select name="materia" class="form-control" id="materia" required>
				      					<option id="input_materia1" selected></option>
				      					<?php
				      					foreach ($lista_materia->getResult() as $key) {
				      						echo "<option value='".$key->id_materia."'>".$key->materia."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="horario" class="form-label">Horario</label>
				      				<select name="horario" class="form-control" id="horario" required>
				      					<option id="input_horario1" selected></option>
				      					<?php
				      					foreach ($lista_horario->getResult() as $key) {
				      						echo "<option value='".$key->id_conf_horarios."'>".$key->dias_horarios."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="aula" class="form-label">Aula</label>
				      				<select name="aula" class="form-control" id="aula">
				      					<option id="input_aula1" selected></option>
				      					<?php
				      					foreach ($lista_aula->getResult() as $key) {
				      						echo "<option value='".$key->id_aula."'>".$key->aula."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="personal" class="form-label">Personal</label>
				      				<select name="personal" class="form-control" id="personal">
				      					<option id="input_personal1" selected></option>
				      					<?php
				      					foreach ($lista_personal->getResult() as $key) {
				      						echo "<option value='".$key->id_personal."'>".$key->persona."</option>";
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
							<th>Ubicacion</th>
							<th>Aula</th>
							<th>Materia</th>
							<th>Horario</th>
							<th>Profesor</th>
							<th>Capacidad</th>
							<th>Acciones</th>

						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_clases as $key) {
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->direccion."</td>";
							echo "<td>".$key->nombre_aula."</td>";
							echo "<td>".$key->nombre_materia."</td>";
							echo "<td>".$key->id_horarios."</td>";
							echo "<td>".$key->nombre."</td>";
							echo "<td>".$key->cantidad_estudiantes."</td>";
							//echo "<td><a type='button' class='btn btn-primary' href='".base_url()."clases_lista/editar_clase?id=".$key->id_clase."'><i class='bi bi-card-checklist' title='Modificar aula'></i></a></td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='editar_clase(".$key->id_clase.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_clase(".$key->id_clase.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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

<div class="modal fade" id="modal_control_asistencia">
  	<div class="modal-dialog modal-dialog-centered modal-xl">
    	<div class="modal-content">

      		<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h3 class="modal-title">Asistencias:</h3>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      	</div>	

	      	<!-- Modal body -->
	      	<div class="modal-body" id="card-forms">
	      		<div class="card card-modal">
	      			<div class="card-body">
				      		<div class="row">
				      			<div class="col-sm-6 form-item">
				      				<label for="materia" class="form-label">Mes</label>
				      				<input type="text" class="form-control" name="materia" id="materia" readonly>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="horario" class="form-label">Año</label>
				      				<input type="text" class="form-control" name="horario" id="horario" readonly>
				      			</div>
	      			</div>
	      		</div>
	      		<div class="card-footer">
	      			<div id=table_hidden>
	      				<div class="card-footer">
	      					<div class="card-body">
								<div id="listas_clases" class="table-responsive">
									
								</div>
							</div>
	      				</div>
	      			</div>
	      		</div>
	      	</div>
	      	<!-- Modal footer -->
	      	<div class="modal-footer">
	        	<button type="button" id="cerrar_modal" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      	</div>
		</div>
	</div>
</div>


<script>
	document.addEventListener("DOMContentLoaded",function(){
		//alertas de registro de datos
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
	/*function lista_asistencias(id){
		$('#modal_control_asistencia').modal('show');
		limpiar_modal()
		$.ajax({
			url:"<?php echo base_url()?>control_asistencia/buscar_fechas_clase",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp=JSON.parse(resp);
				const tabla = document.createElement("table");
			    const thead = tabla.createTHead();
			    const headerRow = thead.insertRow();
			    const th = document.createElement("th");
				th.textContent = "Nº";
			    headerRow.appendChild(th);
			    const th2 = document.createElement("th");
				th2.textContent = "Nombre";
			    headerRow.appendChild(th2);
			    for (const key of resp.data) {
			        const th = document.createElement("th");
			        th.textContent = (key.dia+' '+key.fecha);
			        th.setAttribute("id",key.fecha);
			        headerRow.appendChild(th);
			    }
			    tabla.setAttribute("id","tabla-"+id);
			    tabla.classList.add("table");
			    tabla.classList.add("table-hover");
			    tabla.classList.add("table-basic");
			    tabla.classList.add("text-nowrap");
			    document.getElementById("listas_clases").appendChild(tabla);
			    //ahora se llenan con las filas correspondientes
			    $.ajax({
			    	url:"<?php echo base_url()?>control_asistencia/buscar_estudiantes_asistencias",
			    	type:"POST",
			    	data:{id:id},
			    	success:function(resp){
			    		resp=JSON.parse(resp);
			    		//buscamos las columnas y la tabla para poder imprimir los datos
			    		const cells_cabs=document.querySelectorAll("table[id='tabla-"+id+"'] th[id*='/']");
			    		const tabla = document.getElementById("tabla-"+id);
			    		const tbody = tabla.createTBody();
			    		//buscamos la fecha actual en formato DD/MM para parar la impresion de celdas en el ultimo bucle
			    		const fechaActual = new Date();
						const dia = fechaActual.getDate().toString().padStart(2, '0');
						const mes = (fechaActual.getMonth() + 1).toString().padStart(2, '0');
						const fechaFormateada = `${dia}/${mes}`;
						//declaramos algunas variable mas
			    		var row;
				    		j=1;
			    		id_est=0;
			    		numero=0
			    		//iniciamos con la lectura de datos
			    		for(const key2 of resp.data){
			    			//variables reutilizables
			    			preg=true;
			    			//verificamos si es nuevo alumno en la tabla
			    			if(id_est!==key2.id_estudiante){
			    				//rellenamos celdas restantes siempre y cuando no sea la primera fila
			    				j--;
			    				if(id_est!==0 && cells_cabs[j].id<fechaFormateada){
			    					for(k=j+1;k<cells_cabs.length;k++){
			    						if(cells_cabs[k].id<fechaFormateada){
			    							row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
			    						}else{
			    							row.insertCell();
			    						}
			    					}
			    				}
			    				numero++;
			    				j=0;
			    				row = tbody.insertRow();
			    				row.insertCell().textContent=numero;
            					row.insertCell().textContent=key2.nombre;
            					id_est=key2.id_estudiante;
			    			}
			    			//verficamos fechas previas a la clase de la primera asistencia registrada
			    			do{
			    				if(cells_cabs[j].id===key2.fecha){
			    					row.insertCell().innerHTML=key2.detalle;
			    					preg=false;
			    				}else{
			    					row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
			    				}
			    				j++;
			    				//por seguridad, establecemos que si j xcede el numero de filas que se detenga
			    				if(j>cells_cabs.length){
			    					preg=false;
			    				}
			    			}while(preg);
			    		}
			    		if(id_est!==0){
			    			for(k=j;k<cells_cabs.length;k++){
			    				if(cells_cabs[k].id<fechaFormateada){
			    					row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
			    				}else{
			    					row.insertCell();
			    				}
			    			}
			    		}
			    	},error:function(){

			    	}
			    })
			},error:function(){

			}
		})
	}*/
	function editar_clase(id){
		$.ajax({
			url:"<?php echo base_url()?>clases/mostrar_clases",
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2 = JSON.parse(resp);
				resp2=JSON.parse(resp);
				idf=document.getElementById("id");
				form=document.getElementById("form_aulas");
				materia=document.getElementById("input_materia1");
				horario=document.getElementById("input_horario1");
				aula=document.getElementById("input_aula1");
				personal=document.getElementById("input_personal1");
				idf.value=resp2.data[0].id_clase;
				materia.value=resp2.data[0].id_materia;
				materia.textContent=resp2.data[0].materia;
				horario.value=resp2.data[0].id_horarios;
				horario.textContent=resp2.data[0].horarios;
				aula.value=resp2.data[0].id_aula;
				aula.textContent=resp2.data[0].nombre_aula;
				personal.value=resp2.data[0].id_personal;
				personal.textContent=resp2.data[0].docente;
				//botones
				btnaceptar=document.getElementById("Registrar");
				btnmodificar=document.getElementById("Modificar");
				btneliminar=document.getElementById("Eliminar");
				btncerrar=document.getElementById("salir_edicion");
				btncerrar.hidden=false;
				btnaceptar.disabled=true;
				btnmodificar.disabled=false
				btnaceptar.classList.remove("btn-primary");
				btnmodificar.classList.add("btn-primary");
				form.action="<?php echo base_url()?>clases/modificar_clases";
				$("html, body").animate({ scrollTop: 0 }, 100);
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_clase(id){
		Swal.fire({
			title:'Seguro que quiere borrar esta clase?',
			icon:'question',
			denyButtonText: 'No',
			confirmButtonText:'Si',
			showDenyButton:true
		}).then((result)=>{
			if (result.isConfirmed) {
				$.ajax({
					url:"<?php echo base_url()?>clases/eliminar_clases",
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
		limpieza_form();
		var inputs=document.querySelectorAll("[id^=input]");
		for(var i=0;i<inputs.length;i++){
			inputs[i].value="";
			inputs[i].textContent="";
		}
		var form=document.getElementById('form_aulas');
		form.action="<?php echo base_url()?>clases/registrar_clases";
	}
	/*function limpiar_modal(){
		listas_clases=document.getElementById("listas_clases");
		listas_clases.innerHTML="";
	}*/
</script>
<?php
	$this->endSection();
?>