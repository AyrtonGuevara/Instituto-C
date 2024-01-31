<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2 id="titulo_modulo">Categorias</h2>
		</div>
		<div class="card-body">
			<div class="form-item">
				<form action="<?php echo base_url()?>categorias/registrar_categoria" method="post" accept-charset="utf-8">
					<select class = "form-control" name="select_categorias" id="input_categorias" onchange="cambio_categorias()">
						<option id="inport-default" value=""></option>
						<option value="cargo">Cargo - Empresa</option>
						<option value="nivel-sistema">Nivel - Sistema</option>
						<option value="dia">Dias de la semana</option>
						<option value="turno-estudiante">Turno - Estudiante</option>
						<option value="nivel-estudinate">Nivel - Estudiante</option>
						<option value="fuente-informacion">Fuente - Informaci&oacute;n</option>
					</select>
					<label for="in" class="form-label"> : </label>
					<input type="text"  class="form-control" name="input" id="input">
					<div class="btn-form form-item">
						<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
					</div>
				</form>
			</div>
		</div>
		<div class="card-footer">
			<div class="table-resposive">
				<table class="table table-hover table.basic" id="tabla-categoria">
					<thead>
						<tr>
							<th>Nº</th>
							<th>Categoria</th>
							<th>Valor</th>
							<th>Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	function cambio_categorias(){
		var categoria=document.getElementById("input_categorias").value;
		$.ajax({
			url:"<?php echo base_url()?>categorias/buscar_categorias",
			type:"POST",
			data:{categoria:categoria},
			success:function(resp){
				resp=JSON.parse(resp);
				resp2=resp.data
				tabla=document.getElementById("tabla-categoria");
				var cells = document.querySelectorAll('#tabla-categoria tr');
					for(var i=1; i<cells.length; i++){
						tabla.deleteRow(1);
					}
				if(resp2.length!=0){
					for(const item of resp2){
						nueva_fila=tabla.insertRow();
						celda1=nueva_fila.insertCell(0);
						celda1.innerHTML="<td>"+item.nro+"</td>";
						celda2=nueva_fila.insertCell(1);
						celda2.innerHTML="<td>"+item.nombre_categoria+"</td>";
						celda3=nueva_fila.insertCell(2);
						celda3.innerHTML="<td>"+item.detalle+"</td>";
						celda2=nueva_fila.insertCell(3);
						celda2.innerHTML="<td><button class='btn btn-danger' name='eliminar' onclick='eliminar_categoria("+item.id_categoria+",`"+item.nombre_categoria+"`)'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
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
				$('#mensaje').text('Error al conectarse con el servidor');
			}
		})
	}
	function eliminar_categoria(id,nombre_categoria){
		$.ajax({
			url:"<?php echo base_url()?>categorias/eliminar_categorias",
			type:"POST",
			data:{id:id,
				nombre_categoria:nombre_categoria},
			success:function(resp){
				location.reload();
			},error:function(){
				$('#mensaje').text('Error al conectarse con el servidor');
			}
		})
	}
</script>
<?php
	$this->endSection();
?>