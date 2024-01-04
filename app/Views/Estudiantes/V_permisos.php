<?php
/*
	Ayrton Jhonny Guevara Montaño 10-11-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Permisos</h2>
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-body" id="card-forms">
			<form action="<?php echo base_url()?>permisos/registrar_permiso" method="post" id="form_permisos" accept-charset="utf-8">
				<input type="text" name="id" id="input_id" hidden>
				<div class="row">
					<div class="col-sm-6 form-item">
						<label for="nombre" class="form-label">Estudiante:</label>
						<input type="text" class="form-control" name="nombre" id="input_nombre" required>
					</div>
					<div class="col-sm-6 form-item">
						<label for="clase" class="form-label">Clase a reemplazar:</label>
						<select class="form-control" name="clase" id="clase" required>
							<option id="input_clase"></option>
						</select>
					</div>
					<div class="col-sm-3 form-item">
						<label for="f_permiso" class="form-label">Fecha de permiso:</label>
						<input type="date" class="form-control" name="f_permiso" id="input_f_permiso" required>
					</div>
					<div class="col-sm-3 form-item">
						<label for="f_reemplazo" class="form-label">Fecha de reemplazo:</label>
						<input type="date" class="form-control" name="f_reemplazo" id="input_f_reemplazo" required >
					</div>
					<div class="col-sm-6 form-item">
						<label for="clase_remp" class="form-label">Clase de reemplazo:</label>
						<select class="form-control" name="clase_remp" id="clase_remp" required>
							<option id="input_clase_remp"></option>
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
					<thead>
						<tr>
							<th>Nº</th>
							<th>Estudiante</th>
							<th>Fecha permiso</th>
							<th>Fecha reemplazo</th>
							<th>D&iacute;a</th>
							<th>Detalle</th>
							<th>Acci&oacute;n</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($lista_permisos as $key){
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->estudiante."</td>";
							echo "<td>".$key->fec_reprogramacion."</td>";
							echo "<td>".$key->fec_reemplazo."</td>";
							echo "<td>".$key->dia."</td>";
							echo "<td>".$key->detalle."</td>";
							echo "<td><button class='btn btn-warning' name='editar' onclick='editar_permiso(".$key->id_rep.")'><i class='bi bi-pen-fill' title='Editar'></i>";
							echo "<button class='btn btn-danger' name='eliminar' onclick='eliminar_permiso(".$key->id_rep.")'><i class='bi bi-trash-fill' title='Eliminar'></i></button></td>";
							echo "</tr>";
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

	$('#input_nombre').autocomplete({
		source:function(request,response){
			$.ajax({
				url:"<?php echo base_url()?>permisos/autocompletar_estudiantes",
				type:"POST",
				data:{request:request.term},
				success:function(resp){
					resp=JSON.parse(resp);
					var nombres = resp.data.map(function(item) {
			            return item.nombre;
			        });
			        response(nombres);
				}
			})
		}
	})
	document.getElementById("input_nombre").addEventListener("change", function(){
		option=document.getElementById("input_clase");
		option.value="";
		option.textContent="";
		buscar_clase_nombre();
	});
	function buscar_clase_nombre(){
		nombre = document.getElementById("input_nombre").value;
		var select = document.getElementById("clase");
		for (var i=1; i<select.length; i++) {
		    select.remove(1);
		}
		$.ajax({
			url:"<?php echo base_url()?>permisos/buscar_clase",
			type:"POST",
			data:{nombre:nombre},
			success:function(resp){
				resp=JSON.parse(resp);
				for(const key of resp.data){
					var opcion=document.createElement('option');
					opcion.value=key.id_materia;
					opcion.innerHTML=key.nombre_materia;
					select.appendChild(opcion);
				}
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}
		})
	}
	document.getElementById("input_f_reemplazo").addEventListener("change",function(){
		option2=document.getElementById("input_clase_remp");
		option2.value="";
		option2.textContent="";
		buscar_clase_dispònible();
	});
	function buscar_clase_dispònible(){
		id=document.getElementById("clase").value;
		fecha=document.getElementById("input_f_reemplazo").value;
		var select2= document.getElementById("clase_remp");
		for (var i=1; i<=select2.length; i++) {
		    select2.remove(1);
		}
		$.ajax({
			url:"<?php echo base_url()?>permisos/buscar_clase_reemplazo",
			type:"POST",
			data:{id:id,
				fecha:fecha},
			success:function(resp){
				resp=JSON.parse(resp);
				for(const key of resp.data){
					var opcion=document.createElement('option');
					opcion.value=key.id_clase;
					opcion.innerHTML=key.concat;
					select2.appendChild(opcion);
				}
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}
		})
	}
	function editar_permiso(id){
		$.ajax({
			url:"<?php echo base_url()?>permisos/mostrar_permiso",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp=JSON.parse(resp);
				id=document.getElementById("input_id");
				nombre=document.getElementById("input_nombre");
				clase=document.getElementById("input_clase");
				f_permiso=document.getElementById("input_f_permiso");
				f_reemplazo=document.getElementById("input_f_reemplazo");
				clase_rep=document.getElementById("input_clase_remp");
				id.value=resp.data[0].id_rep;
				nombre.value=resp.data[0].estudiante;
				clase.value=resp.data[0].id_materia;
				clase.innerHTML=resp.data[0].nombre_materia;
				f_permiso.value=resp.data[0].fec_reprogramacion;
				f_reemplazo.value=resp.data[0].fec_reemplazo;
				buscar_clase_dispònible();
				clase_rep.value=resp.data[0].id_clase;
				clase_rep.innerHTML=resp.data[0].detalle;
				buscar_clase_nombre();
				//botones
				btn_edit=document.getElementById("Modificar");
				btn_agreg=document.getElementById("Registrar");
				btn_exit=document.getElementById("salir_edicion");
				form=document.getElementById("form_permisos");
				btn_exit.hidden=false;
				btn_agreg.disabled=true;
				btn_agreg.classList.remove("btn-primary");
				btn_edit.disabled=false;
				btn_edit.classList.add("btn-primary");
				form.action="<?php echo base_url()?>permisos/editar_permiso";
				$("html, body").animate({scrollTop:0},100);
			}, error:function(){
				$('#respuesra').text('error al conectar con el servidor');
			}
		});

	}
	function eliminar_permiso(id){
		Swal.fire({
			title:'Seguro que quiere borrar el permiso?',
			icon:'question',
			denyButtonText: 'No',
			confirmButtonText:'Si',
			showDenyButton:true
		}).then((result)=>{
			if (result.isConfirmed) {
				$.ajax({
					url:"<?php echo base_url()?>permisos/eliminar_permiso",
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
		var form=document.getElementById('form_permisos');
		form.action="<?php echo base_url()?>permisos/registrar_permiso";

		var select= document.getElementById("clase_remp");
		for (var i=1; i<select.length; i++) {
		    select.remove(i);
		}
		var select2= document.getElementById("clase");
		for (var i=1; i<select2.length; i++) {
		    select2.remove(i);
		}
	}
</script>
<?php
	$this->endSection();
?>