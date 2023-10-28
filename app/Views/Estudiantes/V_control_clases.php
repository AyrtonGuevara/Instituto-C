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
				      				<input type="text" class="form-control" name="materia" id="materia" readonly>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="horario" class="form-label">Horario</label>
				      				<input type="text" class="form-control" name="horario" id="horario" readonly>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="aula" class="form-label">Aula</label>
				      				<input type="text" class="form-control" name="aula" id="aula" readonly>
				      			</div>
				      			<div class="col-sm-6 form-item">
				      				<label for="personal" class="form-label">Personal</label>
				      				<input type="text" class="form-control" name="personal" id="personal" readonly>
				      			</div>
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
			url:'<?php echo base_url()?>control_clases/cronograma_clases',
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
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
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
			url:"<?php echo base_url()?>control_clases/mostrar_clases",
			type:"POST",
			data: {id:id},
			success: function (resp){
				resp2=JSON.parse(resp);
				idf=document.getElementById("id");
				materia=document.getElementById("materia");
				horario=document.getElementById("horario");
				aula=document.getElementById("aula");
				personal=document.getElementById("personal");
				caption=document.getElementById("caption_modal");
				idf.value=resp2.data[0].id_clase;
				materia.value=resp2.data[0].materia;
				horario.value=resp2.data[0].horarios;
				aula.value=resp2.data[0].nombre_aula;
				personal.value=resp2.data[0].docente;
				caption.innerHTML="Capacidad de estudiantes en esta aula : "+resp2.data[0].capacidad + " estudiantes.";
				
				//solicitud axaj para mostrar la lista de estudiantes del curso
				$.ajax({
					url:"<?php echo base_url()?>control_clases/lista_estudiantes",
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
						if(resp2.length!=0){
							for(const item of resp2){
								//var tabla=document.getElementById('tabla_estudiantes');
								nueva_fila=tabla.insertRow();
								celda1=nueva_fila.insertCell(0);
								celda1.innerHTML="<td>"+item.nombre+"</td>";
								celda2=nueva_fila.insertCell(1);
								celda2.innerHTML="<td>"+item.fec_inicio+"</td>";
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
	function limpiar_modal(){
		tablah=document.getElementById("table_hidden");
		tablah.hidden=true;
		id=document.getElementById("id");
		materia=document.getElementById("materia");
		horario=document.getElementById("horario");
		aula=document.getElementById("aula");
		personal=document.getElementById("personal");
		caption=document.getElementById("caption_modal");
		id.value="";
		materia.value="";
		horario.value="";
		aula.value="";
		personal.value="";
		caption.innerHTML="";
	}

</script>
<?php
	$this->endSection();
?>