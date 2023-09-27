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
			<h2>Clases</h2>
		</div>
		<div class="card-body" id="card-forms">
			<div class="item-form btn-form-center">
				<input type="button" class="btn btn-primary" name="agregar_clase" id="agregar_clase" onclick="limpiar_modal()" value="Nueva Clase" data-bs-toggle="modal" data-bs-target="#modal_agregar_clase">
			</div>
			
				<div class="row">
					<div class="col-sm-3">
						
					</div>
					<div class="col-sm-6 form-item">
						<label for="ubicacion" class="form-label">Buscar por aula:</label>
						<select class="form-control" name="aula_mostrar" id="aula_mostrar">
							<option></option>
		      					<?php
		      					foreach ($lista_aulas->getResult() as $key) {
		      						echo "<option value='".$key->id_aula."'>".$key->aula."</option>";
		      					}
		      					?>
						</select>
					</div>
				</div>
				
		</div>
		<div class="card-footer">
			<div class="table-responsive">
				<table class="table table-hover table-basic">
					<caption>table title and/or explanatory text</caption>
					<thead>
						<tr>
							<th>Dias/<br>Horarios</th>
							<th>Lunes</th>
							<th>Martes</th>
							<th>Miercoles</th>
							<th>Jueves</th>
							<th>Viernes</th>
							<th>S&aacute;bado</th>
						</tr>
					</thead>
					<tbody>
			            <?php //se genera de manera automatica una table como agenda que despues se llenara por script, cabe notar que cada celda tiene un id unico con respecto a su posicion en dia, hora, y el contador que representa las medias horas
			            	//se inicia un contador en 0
				            $con=0;
				            $cero="0";
				            //si inicia un for con inicio de 8 y que dure hasta 20
			                for ($hour = 8; $hour <= 20; $hour++) : 
			                	if($hour==10){$cero="";}
			                	?>
			                <tr>
			                	<?php 
			                	if ($con==0) {
			                	?>
			                    	<td rowspan="2"><?= sprintf('%02d:00', $hour) ?></td>
			                    <?php 
			                    	
			                	}
			                	$con++;
			                    for ($day = 0; $day < 6; $day++) : ?>
			                        <td id="<?php echo $day.':'.$cero.$hour.':'.($con-1)?>0"></td>
			                    <?php 
			                	endfor; 
			                	if ($con==1) {
			                		$con=3;
			                		$hour--;
			                	}else if($con==4){
			                		$con=0;
			                	}
			                	?>
			                </tr>
			            <?php endfor; ?>
			        </tbody>
				</table>

			</div>
		</div>
	</div>
</div>
<!--MODAL-->
<div class="modal fade" id="modal_agregar_clase">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
    	<div class="modal-content">

      		<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h3 class="modal-title">Clase:</h3>
	        	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	      	</div>	

	      	<!-- Modal body -->
	      	<div class="modal-body" id="card-forms">
	      		<div class="card card-modal">
	      			<div class="card-body">
	      				<form action="<?php base_url()?>clases/registrar_clases" name="form_clase" id="form_aulas" method="POST" accept-charset="utf-8">
			      			<input type="text" name="id" id="id" hidden>
				      		<div class="row">
				      			<div class="col-sm-6 form-item">
				      				<label for="materia" class="form-label">Materia</label>
				      				<select name="materia" class="form-control" id="materia" required>
				      					<option id="materia1" selected></option>
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
				      					<option id="horario1" selected></option>
				      					<?php
				      					foreach ($lista_horarios->getResult() as $key) {
				      						echo "<option value='".$key->id_conf_horarios."'>".$key->dias_horarios."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="aula" class="form-label">Aula</label>
				      				<select name="aula" class="form-control" id="aula">
				      					<option id="aula1" selected></option>
				      					<?php
				      					foreach ($lista_aulas->getResult() as $key) {
				      						echo "<option value='".$key->id_aula."'>".$key->aula."</option>";
				      					}
				      					?>
				      				</select>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="personal" class="form-label">Personal</label>
				      				<select name="personal" class="form-control" id="personal">
				      					<option id="personal1" selected></option>
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
								<input type="button" class="btn border-light" name="Eliminar" value="Eliminar" id="Eliminar" disabled>
				      		</div>
					      </form>
	      			</div>
	      			<div id=table_hidden hidden>
	      				<div class="card-footer">
	      					<div class="table-responsive">
	      						<table id="tabla_estudiantes" class="table table-hover table-basic">
	      							<thead>
	      								<caption id="caption_modal"></caption>
	      								<tr>
	      									<th>Nombre del estudiante</th>
	      									<th>Fecha Inicio</th>
	      									<th>Fecha Fin</th>
	      									<th>Acciones</th>
	      								</tr>
	      							</thead>
	      							<tbody>
	      								<tr>
	      									<td colspan="4">No Hay estudiantes inscritos</td>
	      								</tr>
	      							</tbody>
	      						</table>
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
	var select_aula=document.getElementById('aula_mostrar');
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
			if (session()->getFlashData('id_clase')) {
				$id=session()->getFlashData('id_clase');
				?>
				editar_clase("<?php echo $id?>");
				<?php
			}
		?>
	});

	select_aula.addEventListener('change', function(){
		var cells = document.querySelectorAll("td[id]");
		console.log(cells);
		for (var j = 0; j < cells.length; j++) {
			cells[j].style.backgroundColor = "";
			cells[j].title="";
			cells[j].onclick="";
			cells[j].innerHTML="";
			cells[j].rowSpan=1;
			cells[j].style.display = "";
		}

		var g=0;
		var id=document.getElementById("aula_mostrar").value;
		$.ajax({
			url:'<?php echo base_url()?>clases/cronograma_clases',
			type:"POST",
			data:{id:id},
			success:function(resp){
				var resp2=JSON.parse(resp);
				for(const info of resp2.data){
					var nombre_materia=info.materia;
					var dat=info.horarios;
					var id=info.id_clase;
					var timeSlots = dat.split(" || ");
					var timeData = {};
					
					for (var i = 0; i < timeSlots.length; i++) {
					    var parts = timeSlots[i].split(": ");
					    var day = parts[0];
					    var timeRange = parts[1].split(" - ");
					    timeData[day] = timeRange;
					}
					console.log(timeData);
					console.log("uno"+id);
					for (var day in timeData) {
				    	var ndia=0
				        var timeRange = timeData[day];
				        var startTime = timeRange[0];
				        var endTime = timeRange[1];
				        switch (day){
				        case "lunes":
				        	ndia=0;
				        	break;
				        case "martes":
				        	ndia=1;
				        	break;
				        case "miercoles":
				        	ndia=2;
				        	break;
				        case "jueves":
				        	ndia=3;
				        	break;
				        case "viernes":
				        	ndia=4;
				        	break;
				        case "sabado":
				        	ndia=5;
				        	break;
				        }
				        console.log(ndia);
				        var cells = document.querySelectorAll("td[id^='" + ndia + "']");
				        var contador_collspan=0;
				        var fij=0;
				        var pasa=1;
				        for (var j = 0; j < cells.length; j++) {
				            var cellTime = cells[j].getAttribute("id").substr(2, 5);
				            //console.log(cellTime);
				            if (cellTime >= startTime && cellTime < endTime) {
				            	fij=j+1;
				            	contador_collspan++;
				            	pasa=0
				                /*cells[j].style.backgroundColor = "green";
				                cells[j].title=nombre_materia;
				                cells[j].innerHTML="<input type='button' id='' value='"+id+"' onclick='editar_clase("+id+")'>";
				                /*cells[j].onclick=function(){
				                	editar_clase(id);
				                }*/
				            }
				    	}
				    	if(pasa===0){
				    		cells[fij-contador_collspan].rowSpan=contador_collspan;
					    	cells[fij-contador_collspan].style.backgroundColor = "green";
							cells[fij-contador_collspan].title=nombre_materia;
					        cells[fij-contador_collspan].innerHTML="<input type='button' class='btn button-absolute btn-green' value='"+id+"' onclick='editar_clase("+id+")' >";
					        for(var k=1; k<contador_collspan;k++){
					        	c=(fij-contador_collspan)+k;
								cells[c].style.display = "none";
					        }
				    	}
					}
				}
			}
		});
	});
	function editar_clase(id){
		console.log(id);
		$('#modal_agregar_clase').modal('show');
		limpiar_modal();
		tablah=document.getElementById("table_hidden");
		tablah.hidden=false;
		$.ajax({
			url:"<?php echo base_url()?>clases/mostrar_clases",
			type:"POST",
			data: {id:id},
			success: function (resp){
				resp2=JSON.parse(resp);
				idf=document.getElementById("id");
				form=document.getElementById("form_aulas");
				materia=document.getElementById("materia1");
				horario=document.getElementById("horario1");
				aula=document.getElementById("aula1");
				personal=document.getElementById("personal1");
				caption=document.getElementById("caption_modal");
				idf.value=resp2.data[0].id_clase;
				materia.value=resp2.data[0].id_materia;
				materia.textContent=resp2.data[0].materia;
				horario.value=resp2.data[0].id_horarios;
				horario.textContent=resp2.data[0].horarios;
				aula.value=resp2.data[0].id_aula;
				aula.textContent=resp2.data[0].nombre_aula;
				personal.value=resp2.data[0].id_personal;
				personal.textContent=resp2.data[0].docente;
				caption.innerHTML="Capacidad de estudiantes en esta aula : "+resp2.data[0].capacidad + " estudiantes.";
				//botones
				btnaceptar=document.getElementById("Registrar");
				btnmodificar=document.getElementById("Modificar");
				btneliminar=document.getElementById("Eliminar");
				btnaceptar.disabled=true;
				btnmodificar.disabled=false
				btneliminar.disabled=false
				btneliminar.onclick=function(){
					eliminar_clase(resp2.data[0].id_clase);
				}
				btnaceptar.classList.remove("btn-primary");
				btneliminar.classList.add("btn-danger");
				btnmodificar.classList.add("btn-primary");
				form.action="<?php echo base_url()?>clases/modificar_clases";
				//solicitud axaj para mostrar la lista de estudiantes del curso
				$.ajax({
					url:"<?php echo base_url()?>clases/lista_estudiantes",
					type:"POST",
					data:{id:id},
					success:function(resp){
						resp=JSON.parse(resp);
						resp2=resp.data;
						var cells = document.querySelectorAll('#tabla_estudiantes tr');
						console.log("tamaño tabla"+cells.length);
						for(var i=1; i<cells.length; i++){
							tabla=document.getElementById("tabla_estudiantes");
							tabla.deleteRow(1);
						}
						console.log();
						if(resp2.length!=0){
							for(const item of resp2){
								//var tabla=document.getElementById('tabla_estudiantes');
								nueva_fila=tabla.insertRow();
								celda1=nueva_fila.insertCell(0);
								celda1.innerHTML="<td>"+resp.data[0].nombre+"</td>";
								celda2=nueva_fila.insertCell(1);
								celda2.innerHTML="<td>"+resp.data[0].fec_inicio+"</td>";
								celda3=nueva_fila.insertCell(2);
								celda3.innerHTML="<td>aun no se ha calculado</td>";
								celda2=nueva_fila.insertCell(3);
								celda2.innerHTML="<td>acciones??</td>";
							}
						}else{
							nueva_fila=tabla.insertRow();
							celda0=nueva_fila.insertCell(0);
							celda1=nueva_fila.insertCell(1);
							celda2=nueva_fila.insertCell(2);
							celda3=nueva_fila.insertCell(3);
							celda0.innerHTML="<td colspan='4'>No Hay estudiantes inscritos</td>";
						}
					},error:function(){
						$('#respuesta').text('Error al conectar con el servidor');
					}
				});
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
	function limpiar_modal(){
		tablah=document.getElementById("table_hidden");
		tablah.hidden=true;
		id=document.getElementById("id");
		materia=document.getElementById("materia1");
		horario=document.getElementById("horario1");
		aula=document.getElementById("aula1");
		personal=document.getElementById("personal1");
		caption=document.getElementById("caption_modal");
		id.value="";
		materia.selected=materia.defaultSelected;
		horario.selected=horario.defaultSelected;
		aula.selected=aula.defaultSelected;
		personal.selected=personal.defaultSelected;
		materia.value="";
		materia.textContent="";
		horario.value="";
		horario.textContent="";
		aula.value="";
		aula.textContent="";
		personal.value="";
		personal.textContent="";
		caption.innerHTML="";
		//botones
		form=document.getElementById("form_aulas");
		btnaceptar=document.getElementById("Registrar");
		btnmodificar=document.getElementById("Modificar");
		btneliminar=document.getElementById("Eliminar");
		btnaceptar.disabled=false;
		btnmodificar.disabled=true;
		btneliminar.disabled=true;
		btnaceptar.classList.add("btn-primary");
		btneliminar.classList.remove("btn-danger");
		btnmodificar.classList.remove("btn-primary");
		form.action="<?php echo base_url()?>clases/registrar_clases";
	}

</script>
<?php
	$this->endSection();
?>