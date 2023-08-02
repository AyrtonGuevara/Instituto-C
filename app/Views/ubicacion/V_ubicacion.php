<?php
/*
	Ayrton Guevara Montaño 30/07/2023
	
*/
	$this->extend('Template/Head');
	$this->section('content');
?>


<div class="container">
	<?php if(session()->getFlashdata('exito')){?>
		<div class="alert alert-success">
			<?php echo session()->getFlashData('exito');?>
		</div>
	<?php }else if(session()->getFlashdata('fracaso')){ ?>
		<div class="alert alert-success">
			<?php echo session()->getFlashData('fracaso');?>
		</div>
	<?php }else{} ?>
	<!--<form action="<?php echo base_url() ?>ubicacion/registrar_ubicacion" method="post" accept-charset="utf-8" id="form_ubicacion">-->
	<form method="post" accept-charset="utf-8" name="form_ubicacion" id="form_ubicacion" action="<?php base_url() ?>ubicacion/registrar_ubicacion">
		<div class="row">
			<input type="text" name="id" id="id" hidden>
			<div class="col-sm-6">
				<input type="text" name="zona" id="zona" required/><br>
				<label for="zona">zona</label>
			</div>
			<div class="col-sm-6">
				<input type="text" name="direccion" id="direccion" required/><br>
				<label for="direccion">direccion</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<input type="text" name="detalle" id="detalle"><br>
				<label for="detalle">detalle</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<input type="text" name="descripcion" id="descripcion"><br>
				<label for="descripcion">descripcion</label>
			</div>
		</div>
		<input type="submit" name="Registrar" value="Registrar" id="Registrar">
		<input type="submit" name="Modificar" value="Modificar" id="Modificar" disabled>
	</form>
</div>
<div class="container">
	<table class="table responsive">
		<caption>table title and/or explanatory text</caption>
		<thead>
			<tr>
				<th>Nº</th>
				<th>Direcci&oacute;n</th>
				<th>Zona</th>
				<th>Detalle</th>
				<th>Descripci&oacute;n</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
				<?php
				foreach ($list->getResult() as $key) {
					echo "<tr>";
					echo "<td>".$key->id_ubicacion."</td>";
					echo "<td>".$key->zona."</td>";
					echo "<td>".$key->direccion."</td>";
					echo "<td>".$key->detalle."</td>";
					echo "<td>".$key->descripcion."</td>";
					echo "<td> <input type='button' class='btn btn-info' name='Editar' value='Editar' onclick='mostrar_ubicacion(".$key->id_ubicacion.")'>";
					echo "<input type='button' class= 'btn btn-danger' name='Eliminar' value='Eliminar' onclick='eliminar_ubicacion(".$key->id_ubicacion.")'></td>";
					echo "</tr>";
				}
				?>
		</tbody>
	</table>
</div>

<script>
/*
	function RegistrarModificar(event){
		//para la noche convertir esto en un json, ver chatgpt y estudiar puto
		event.preventDefault();
		var zona=document.getElementById("zona").value;
		var direccion=document.getElementById("direccion").value;
		var detalle=document.getElementById("detalle").value;
		var descripcion=document.getElementById("descripcion").value;
		var id=document.getElementById("id").value;
		console.log(id);
		if (id !== null) {
			$.ajax({
				url:"<?php base_url() ?>ubicacion/modificar_ubicacion",
				type:"POST",
				data:{
					id:id,
					zona:zona,
					direccion:direccion,
					detalle:detalle,
					descripcion:descripcion
				},
				success:function(resp){
					var resp2=JSON.parse(resp);
					if (resp2.success) {
						Swal.fire({
							icon: 'success',
							text: 'Registro modificado',
							confirmButtonColor: '#000010',
							confirmButtonText: 'ACeptar'
						}).then(function(result){
							location.reload();
						});
					}else{
						Swal.fire({
							icon: 'error',
							text: 'error en el registro',
							confirmButtonColor: '#FFFFFF',
							conformButtonText: 'Aceptar'
						})
					}
				},error:function(){
					$('#respuesta').text('error al conectar con el servidor');
				}
			});
		}else{
			$.ajax({
				url:"<?php echo base_url() ?>ubicacion/registrar_ubicacion",
				type:"POST",
				data:{
					zona:zona,
					direccion:direccion,
					detalle:detalle,
					descripcion:descripcion
				},
				success:function(resp){
					var data=JSON.parse(resp);
					if (data.success) {
						Swal.fire({
							icon: 'success',
							text: 'registro añadido',
							confirmButtonColor: '#000000',
							confirmButtonText:'Aceptar'
						}).then(function(result){
							location.reload();
						});
					}else{
						Swal.fire({
							icon: 'error',
							text: 'registro erroneo',
							confirmButtonColor: '#111111',
							confirmButtonText:'Aceptar'
						});
					}
				},error:function(){
					$('#respuesta').text('Error al conectar con el servidor');
				}
			});
		}

	}
*/
	function mostrar_ubicacion(id){
		$.ajax({
			url:"<?php echo base_url()?>ubicacion/mostrar_ubicacion",
			type:"POST",
			data:{id:id},
			success:function(resp){
				console.log(resp);
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
						//botones
						var btnad=document.getElementById('Registrar');
						var btnmd=document.getElementById('Modificar');
						var form=document.getElementById('form_ubicacion');
						btnad.disabled=true;
						btnmd.disabled=false;
						form.action="<?php echo base_url()?>ubicacion/molificar_ubicacion";

					});
				}//mensaje de la bdd
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}

		});
	}
	function eliminar_ubicacion(id){
		$.ajax({
			url:"<?php echo base_url()?>ubicacion/mostrar_ubicacion",
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
								url:"<?php echo base_url()?>ubicacion/eliminar_ubicacion",
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
		/*echo "	{";
			echo "var form=document.getElementById('zona');";
			echo "var val='".$a->zona."';";
			echo "form.value = val;";
			echo "});";
		/*function hacerPeticionAJAX(id) {
            // Realiza la solicitud AJAX al controlador usando jQuery
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url('mi_controlador/') ?>' + '/' + id,
                dataType: 'html',
                success: function(respuesta) {
                    // Maneja la respuesta del controlador
                    alert(respuesta); // Muestra la respuesta del controlador en una alerta (puedes cambiar esto)
                },
                error: function() {
                    alert('Error al comunicarse con el servidor.');
                }
            });
        }*/

</script>

<?php
	$this->endSection();
?>