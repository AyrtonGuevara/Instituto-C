<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2 id="titulo_modulo">Permisos</h2>
		</div>
		<div class="card-body table-responsive">
			<form action="<?php echo base_url()?>conf_permisos/modificar_permisos" method="POST" accept-charset="utf-8">
				<input type="text" name="id" id="id" required hidden>
				<table class="table table-basic table-hover">
					<thead>
						<tr>
							<th>Nro</th>
							<th>Modulos</th>
							<th>Permiso</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($lista_paginas as $key){
							echo "<tr>";
							echo "<td>".$key->nro."</td>";
							echo "<td>".$key->nombre_pagina."</td>";
							echo "<td><input class='form_control readonly-checkbox' type='checkbox' name='permisos[]' id='".$key->codigo_modulo."-".$key->codigo_submodulo."' value='".$key->id_paginas."' readonly onchange='lista_permisos(this)'></td>";
							echo "</tr>";	
						}
						?>
					</tbody>
				</table>
				<div class="btn-form form-item">
					<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
				</div>	
			</form>
		</div>
		<div class="card-footer table-responsive">
			<table class="table table-basic table-hover">
				<thead>
					<tr>
						<th>Nro</th>
						<th>Nivel</th>
						<th>Acci&oacute;n</th>

					</tr>
				</thead>
				<tbody>
					<?php
					foreach($lista_niveles as $key2){
						echo "<tr>";
						echo "<td>".$key2->nro."</td>";
						echo "<td>".$key2->cargo."</td>";
						echo "<td><input type='button' class='btn btn-danger' name='ver-permisos' onclick='mostrar_permisos_usuario(".$key2->id_cargo.")' value='Ver'></td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	
	function mostrar_permisos_usuario(id){
		//se limpia la tabla
		const limp=document.querySelectorAll("input[name='permisos[]']");
		for (var i = 0; i < limp.length; i++) {
			limp[i].checked=false;
			id_check=limp[i].id;
			if (!id_check.endsWith("-0")) 
			{
				limp[i].classList.remove("readonly-checkbox");
			}
		}
		//se realiza la funcion
		$.ajax({
			url:"<?php echo base_url()?>conf_permisos/permisos_usuario",
			type:"POST",
			data:{id:id},
			success:function(resp){
				resp=JSON.parse(resp);
				//se notifica el cargo o nivel a configurar
				document.getElementById("titulo_modulo").innerHTML="Perminsos "+resp.data[0].cargo;
				document.getElementById("id").value=id;

				for(const key of resp.data){
					document.getElementById(key.codigo_modulo+'-'+key.codigo_submodulo).checked=true;
				}
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}
		})
	}

	//const check=document.getElementById("intput ");
	function lista_permisos(checkbox){
		var cont=0;
		var cont2=0;
		var id_bas=checkbox.id.split("-");
		if(id_bas[1]!==0){
			if (document.getElementById(checkbox.id).checked) {
				if (!document.getElementById(id_bas[0]+"-0").checked) {
					document.getElementById(id_bas[0]+"-0").checked=true;
				}
			}else{
				const inputs=document.querySelectorAll("input[id^='"+id_bas[0]+"']");
				for (var i = 0; i<inputs.length; i++) {
					if(document.getElementById(id_bas[0]+"-"+cont).checked){
						cont2++;
					}
					cont ++;
				}
				if(cont2<=1){
					document.getElementById(id_bas[0]+"-0").checked=false;
				}	
			}
		}
	}

</script>
<?php
	$this->endSection();
?>