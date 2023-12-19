<?php
/*
	Ayrton Jhonny Guevara Montaño 03-09-2023
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Inscripcion de estudiante</h2>
			<input type="button" class="btn-close" name="salir_edicion" id="salir_edicion" onclick="limpiar_form()" title="Cerrar" hidden>
		</div>
		<div class="card-body">
			<form method="post" accept-charset="utf-8" name="form_estudiante" id="form_estudiante" action="<?php base_url() ?>estudiantes/registrar_estudiante">
				<input type="text" name="id" id="id" hidden>
				<div class="card card-internal-card border-primary" id="card-estudiante">
					<div class="card-header">
						<h3>Datos del estudiante</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-3 form-item">
								<label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
								<input type="text" class="form-control" name="apellido_paterno" id="input_apellido_paterno" placeholder="Apellido paterno" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="apellido_materno" class="form-label">Apellido Materno:</label>
								<input type="text" class="form-control" name="apellido_materno" id="input_apellido_materno" placeholder="Apellido materno">
							</div>
							<div class="col-sm-6 form-item">
								<label for="nombre" class="form-label">Nombre:</label>
								<input type="text" class="form-control" name="nombre" id="input_nombre" placeholder="Nombre"required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="fecha_nac" class="form-label">Fecha nacimiento:</label>
								<input type="date" class="form-control" name="fecha_nac" id="input_fecha_nac" value="2010-01-01"required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="edad" class="form-label">Edad:</label>
								<input type="number" class="form-control" name="edad" id="input_edad" placeholder="Edad" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="celular" class="form-label">Celular:</label>
								<input type="number" class="form-control" name="celular" id="input_celular" placeholder="Celular">
							</div>
							<div class="col-sm-3 form-item" >
								<label for="fuente" class="form-label">Fuente:</label>
								<select name="fuente" class="form-control" id="input_fuente" required>
									<option id="input_fuente1" value=""></option>
									<?php
									foreach ($lista_fuentes as $key) {
										echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
									}
									?>
								</select>
							</div>
							<div class="col-sm-6 form-item">
								<label for="ue" class="form-label">Unidad Educativa:</label>
								<input type="text" class="form-control" name="ue" id="input_ue" placeholder="Unidad Educativa">
							</div>
							<div class="col-sm-3 form-item">
								<label for="turno" class="form-label">Turno:</label>
								<select name="turno" class="form-control" id="input_turno">
									<option id="input_turno1" value=""></option>
									<?php
									foreach ($lista_turno as $key) {
										echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
									}
									?>
								</select>
							</div>
							<div class="col-sm-3 form-item">
								<label for="nivel" class="form-label">Nivel:</label>
								<select name="nivel" class="form-control" id="input_nivel">
									<option id="input_nivel1" value=""></option>
									<?php
									foreach ($lista_nivel as $key) {
										echo "<option value='".$key->id_categoria."'>".$key->detalle."</option>";
									}
									?>
								</select>
							</div>
							<div class="col-sm-3 form-item">
								<label for="grado" class="form-label">Grado:</label>
								<input type="number" class="form-control" name="grado" id="input_grado" placeholder="Grado">
							</div>
							<div class="col-sm-3 form-item">
								<label for="zona" class="form-label">Zona:</label>
								<input type="text" class="form-control" name="zona" id="input_zona" placeholder="Zona" required>
								<!--ojo con este-->
							</div>
							<div class="col-sm-6 form-item">
								<label for="calle" class="form-label">Direcci&oacute;n:</label>
								<input type="text" class="form-control" name="calle" id="input_calle" placeholder="Direcci&oacute;n">
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card card-internal-card border-success" id="card-tutor">
					<div class="card-header">
						<h3>Datos del tutor</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-3 form-item">
								<label for="t_apellido_paterno0" class="form-label">Apellido Paterno:</label>
								<input type="text" class="form-control" name="t_apellido_paterno" id="input_t_apellido_paterno0" placeholder="Apellido paterno" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="t_apellido_materno0" class="form-label">Apellido Materno:</label>
								<input type="text" class="form-control" name="t_apellido_materno" id="input_t_apellido_materno0" placeholder="Apellido materno">
							</div>
							<div class="col-sm-6 form-item">
								<label for="t_nombre0" class="form-label">Nombre:</label>
								<input type="text" class="form-control" name="t_nombre" id="input_t_nombre0" placeholder="Nombre" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="t_actividad0" class="form-label">Actividad</label>
								<input type="text" class="form-control" name="t_actividad" id="input_t_actividad0" placeholder="Actividad" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="t_trabajo0" class="form-label">Trabajo:</label>
								<input type="text" class="form-control" name="t_trabajo" id="input_t_trabajo0" placeholder="Trabajo">
							</div>
							<div class="col-sm-3 form-item">
								<label for="t_telefono0" class="form-label">Telefono:</label>
								<input type="number" class="form-control" name="t_telefono" id="input_t_telefono0" placeholder="Telefono">
							</div>
							<div class="col-sm-3 form-item">
								<label for="t_celular0" class="form-label">Celular:</label>
								<input type="number" class="form-control" name="t_celular" id="input_t_celular0" placeholder="Celular" required>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card card-internal-card border-danger" id="card-clase">
					<div class="card-header">
						<h3>Datos de la clase</h3>
					</div>
					<div class="card-body">
						<div class="container form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" name="tipo_horarios" id="input_tipo_checkbox">
					<label class="form-check-label" id="tipo_checkbox_label" for="tipo_horarios">Crear horario especial</label>
						</div>
						<div class="row">
							<div class="col-sm-3 form-item">
								<label for="f_inicio" class="form-label">Fecha de Inicio:</label>
								<input type="date" class="form-control" name="f_inicio" id="input_f_inicio" required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="cantidad"class="form-label">Cantidad de meses:</label>
								<input type="number" class="form-control" name="cantidad" id="input_cant_tiempo" placeholder="Meses"required>
							</div>
							<div class="row" id="div_horario_tradicional">
								<div class="col-sm-6 form-item">
									<label for="materia"class="form-label">Materia:</label>
									<select name="materia" id="input_materia" class="form-control" required>
										<option id="input_materia1" value=""></option>
										<?php
										foreach ($lista_materias as $key) {
											echo "<option value='".$key->id_precios."'>".$key->nombre_materia."</option>";
										}
										?>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="horarios"class="form-label">Horarios:</label>
									<select name="horarios" id="input_horarios" class="form-control" required>
										<option id="input_horarios1" value="">Horarios</option>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="aulas"class="form-label">Aulas:</label>
									<select name="aulas" id="input_aulas" class="form-control" required>
										<option id="input_aulas1" value=""></option>
									</select>
								</div>
							</div>

							<div class="row" id="div_horario_especial" hidden>
								<div class="col-sm-3 form-item">
									<label for="materia2"class="form-label">Materia:</label>
									<select name="materia2" id="input_materia_esp" class="form-control">
										<option id="input_materia21" value="">Materias</option>
										<?php
										foreach ($lista_materias as $key) {
											echo "<option value='".$key->id_precios."'>".$key->nombre_materia."</option>";
										}
										?>
									</select>
								</div>
								<div class=" col-sm-9">
								</div>
								<div class="col-sm-3 form-item">
									<label for="horario"class="form-label">Primer Horario:</label>
									<select name="horario[]" id="input_horario1" class="form-control">
										<option id="input_horario11" value="">horario1</option>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="horario"class="form-label">Segundo Horario:</label>
									<select name="horario[]" id="input_horario2" class="form-control">
										<option id="input_horario21" value="">horario2</option>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="horario"class="form-label">Tercer Horario:</label>
									<select name="horario[]" id="input_horario3" class="form-control">
										<option id="input_horario31" value="">horario3</option>
									</select>
								</div>
								<div class="col-sm-3">
									
								</div>
								<div class="col-sm-3 form-item">
									<label for="aula"class="form-label">Aula:</label>
									<select name="aula[]" id="input_aula1" class="form-control">
										<option id="input_aula11" value="">Aulas</option>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="aula"class="form-label">Aula:</label>
									<select name="aula[]" id="input_aula2" class="form-control">
										<option id="input_aula21" value="">Aulas</option>
									</select>
								</div>
								<div class="col-sm-3 form-item">
									<label for="aula"class="form-label">Aula:</label>
									<select name="aula[]" id="input_aula3" class="form-control">
										<option id="input_aula31" value="">Aulas</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card card-internal-card border border-info" id="card-pago">
					<div class="card-header">
						<h3>Datos del pago</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-6 form-item">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="pago_checkbox" id="input_r_contado" value="contado" checked>
									<label class="form-check-label" for="inlineRadio1">Al contado</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="pago_checkbox" id="input_r_deuda" value="deuda">
									<label class="form-check-label" for="inlineRadio2">En deuda</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="pago_checkbox" id="input_r_plazos" value="plazos">
									<label class="form-check-label" for="inlineRadio3">Plazos</label>
								</div>
							</div>
							<div class="col-sm-6 form-item"></div>
							<div class="col-sm-3 form-item">
								<label for="monto_curso" class="form-label">Monto del curso:</label>
								<input type="number" class="form-control" name="monto_curso" id="input_monto_curso" readonly required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="total" class="form-label">Total:</label>
								<input type="number" class="form-control" name="total" id="input_total" readonly required>
							</div>
							<div class="col-sm-3 form-item">
								<label for="cuenta" class="form-label">Anticipo:</label>
								<input type="number" class="form-control" name="cuenta" id="input_cuenta" readonly>
							</div>
							<div class="col-sm-3 form-item">
								<label for="f_pago" class="form-label">Fecha de pago:</label>
								<input type="date" class="form-control" name="f_pago" id='input_fecha_pago' readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="form-item btn-form">
					<input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
				</div>
			</form>
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
			}else if (session()->getFlashData('id_clase')) {
				$id=session()->getFlashData('id_clase');
				?>
				editar_clase("<?php echo $id?>");
				<?php
			}else if(session()->getFlashData('ver_estudiante')){
				$arr=session()->getFlashData('ver_estudiante');
				$id_est=$arr[0];
				$tipo=$arr[1];
				?>
				ver_estudiante(<?php echo $id_est?>,<?php echo $tipo?>);
				<?php
			}
		?>
	});
	document.getElementById("input_tipo_checkbox").addEventListener('change', function(){
		tipo_horarios=document.getElementById("input_tipo_checkbox").checked;
		horario_especial=document.getElementById("div_horario_especial");
		horario_tradicional=document.getElementById("div_horario_tradicional");
		const input=document.querySelectorAll("div[id=div_horario_tradicional] select");
		const input2=document.querySelectorAll("div[id=div_horario_especial] select");
		if(tipo_horarios==1){
			// se da el horario especial o formulado especialmente a peticion del estudiante
			aviso=document.getElementById("tipo_checkbox_label");
			aviso.innerHTML="Volver a horarios establecidos";
			horario_tradicional.hidden=true;
			horario_especial.hidden=false;
			//se quita el required de la materia, horarios y aulas de este div 
			for (var i = 0; i < input.length; i++) {
				input[i].required=false;
			}
			for (var i = 0; i < input2.length; i++) {
				input2[i].required=true;
			}
		}else if(tipo_horarios==0){
			// se da el horario tradicional o formulado por la institucion
			aviso=document.getElementById("tipo_checkbox_label");
			aviso.innerHTML="Crear horario especial";
			horario_tradicional.hidden=false;
			horario_especial.hidden=true;
			//se quita el required de la materia, horarios y aulas de este div
			for (var i = 0; i < input.length; i++) {
				input[i].required=true;
			}
			for (var i = 0; i < input2.length; i++) {
				input2[i].required=false;
			}
		}
		
	});

	const checkbox_pago=document.querySelectorAll("input[name=pago_checkbox]");
	checkbox_pago.forEach((check)=>{
		check.addEventListener('change',function(){
			monto=document.getElementById("input_cuenta");
			fecha=document.getElementById("input_fecha_pago")
			if(this.value=='contado'){
				monto.readOnly=true;
				fecha.readOnly=true;
				monto.required=false;
				fecha.required=false;
			}else if (this.value=='deuda') {
				monto.readOnly=true;
				monto.required=false;
				fecha.readOnly=false;
				fecha.required=true;
			}else if (this.value=='plazos') {
				monto.readOnly=false;
				monto.required=true;
				fecha.readOnly=false;
				fecha.required=true;
			}
		})
	})

	document.getElementById("input_fecha_nac").addEventListener("change",function(){
		nacimiento=new Date(document.getElementById("input_fecha_nac").value);
		hoy=new Date();
		var edad = hoy.getFullYear() - nacimiento.getFullYear();
	    var m = hoy.getMonth() - nacimiento.getMonth();

	    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
	        edad--;
	    }
	    esp_edad=document.getElementById('input_edad');
	    esp_edad.value=edad;
	})

	document.getElementById("input_materia").addEventListener("change",function(){
		id_materia=document.getElementById("input_materia").value;
		console.log(id_materia);
		$.ajax({
			url:"<?php echo base_url()?>estudiantes/horarios",
			type:"POST",
			data:{id_materia:id_materia},
			success:function(resp){
				resp=JSON.parse(resp);
				console.log(resp);
				horarios=document.getElementById("input_horarios");
				aulas=document.getElementById("input_aulas");
				horarios.innerHTML="<option>Horarios</option>"
				aulas.innerHTML="<option>Aulas</option>";
				for(const info of resp.data){
					horarios.innerHTML+="<option value='"+info.id_conf_horarios+"'>"+info.dias_horarios+"</option>";
					//horarios.innerHTML="<option></option>"
				}
				monto=document.getElementById("input_monto_curso");
				monto.value=resp.data[0].precio;
				calculo_costo();

			},
			error:function(){

			}
		})
	})
	document.getElementById("input_horarios").addEventListener("change",function(){
		id_horarios=document.getElementById("input_horarios").value;
		id_materia=document.getElementById("input_materia").value;
		tipo_horarios=document.getElementById("input_tipo_checkbox").checked;
		console.log(id_horarios);
		console.log(id_materia);
		$.ajax({
			url:"<?php echo base_url()?>estudiantes/aulas",
			method:"POST",
			data:{
				id_horarios:id_horarios,
				id_materia:id_materia,
				tipo_horarios:tipo_horarios
			},
			success:function(resp){
				resp=JSON.parse(resp);
				aulas=document.getElementById("input_aulas");
				aulas.innerHTML="<option>Aulas</option>";
				for(const info of resp.data){
					aulas.innerHTML+="<option value='"+info.id_aula+"'>"+info.aula+"</option>";
				}
			},error:function(){

			}
		})
	})
	document.getElementById("input_materia_esp").addEventListener("change",function(){
		id_materia=document.getElementById("input_materia_esp").value;
		$.ajax({
			url:"<?php echo base_url()?>estudiantes/horarios_materia_esp",
			type:"POST",
			data:{id_materia:id_materia},
			success:function(resp){
				resp=JSON.parse(resp);
				horarios=document.querySelectorAll("select[name='horario[]']");
				aulas=document.querySelectorAll("select[name='aula[]']");
				for(i=0;i<horarios.length;i++){
					horarios[i].innerHTML="<option>Horarios</option>";
					aulas[i].innerHTML="<option>Aulas</option>";

					for(const info of resp.data){
						horarios[i].innerHTML+="<option value='"+info.id_horarios+"'>"+info.horario+"</option>";
					}
				}
				monto=document.getElementById("input_monto_curso");
				monto.value=resp.data[0].precio;
				calculo_costo();

			},
			error:function(){

			}
		})
	})
	const horario=document.querySelectorAll("select[name='horario[]']");
	horario.forEach((horarios)=>{
		horarios.addEventListener("change",function(){
			id_materia=document.getElementById("input_materia_esp").value;
			tipo_horarios=document.getElementById("input_tipo_checkbox").checked;
			var cambio;
			horario_cambio=event.target;
			if(horario_cambio===horario[0]){
				id_horarios=document.getElementById("input_horario1").value;
				cambio=1;
				console.log("1");
			}else if(horario_cambio===horario[1]){
				id_horarios=document.getElementById("input_horario2").value;

				cambio=2;
				console.log("2");
			}else if(horario_cambio===horario[2]){
				id_horarios=document.getElementById("input_horario3").value;
				cambio=3;
				console.log("3");
			}
			console.log(tipo_horarios);
			console.log(id_materia+" "+id_horarios);
			//ante el cambio los demas select ocultan la opcion para que no haya repeticiones
				/*id2=document.querySelectorAll("select[name='horario[]'] option[value='"+id_horarios+"']");
				console.log(id2);
				for(i=0;i<id2.length;i++){
					if(i!=(cambio-1)){
						id2[i].hidden=true;
					}
				}*/
			$.ajax({
				url:"<?php echo base_url()?>estudiantes/aulas",
				type:"POST",
				data:{
					id_materia:id_materia,
					id_horarios:id_horarios,
					tipo_horarios:tipo_horarios
				},
				success:function(resp){
					resp=JSON.parse(resp);
					console.log(resp);
					aulas=document.getElementById("input_aula"+cambio);
					aulas.innerHTML="<option>Aulas</option>";
					for(const info of resp.data){
						aulas.innerHTML+="<option value='"+info.id_aula+"'>"+info.aula+"</option>";
					}
				},error:function(){

				}
			})
		})
	})
	document.getElementById("input_cant_tiempo").addEventListener('change', calculo_costo);
	function calculo_costo(){
		cant_tiempo=document.getElementById("input_cant_tiempo").value;
		monto_curso=document.getElementById("input_monto_curso").value; 
		if (cant_tiempo!=null && monto_curso!=null) {
			nuevo_costo=cant_tiempo*monto_curso;
			document.getElementById("input_total").value=nuevo_costo;
		}
	};
	function ver_estudiante(id,tipo){
		var inputs=document.querySelectorAll("[id^=input]");
		document.getElementById("salir_edicion").hidden=false;
		//0 es para solo ver
		if (tipo===0 || tipo===3) {
			for(var i=0;i<inputs.length;i++){
				inputs[i].disabled=true;
			}
			document.getElementById("Registrar").hidden=true;
		}else if(tipo===1){
			document.getElementById("Registrar").value="Modificar";
			document.getElementById("form_estudiante").action="<?php echo base_url()?>estudiantes/modificar_estudiante_tutor";
			document.getElementById("id").value=id;
			no_requeridos=document.querySelectorAll("#card-clase [id^=input_]");
			for(var i=0;i<no_requeridos.length;i++){
				no_requeridos[i].removeAttribute('required');
			}
		}
		$.ajax({
			url:'<?php echo base_url()?>estudiantes/ver_estudiante',
			type:"POST",
			data:{id:id,tipo:tipo},
			success:function(resp){
				resp=JSON.parse(resp);
				document.getElementById("input_apellido_paterno").value=resp.data[0].ap_pat_persona;
				document.getElementById("input_apellido_materno").value=resp.data[0].ap_mat_persona;
				document.getElementById("input_nombre").value=resp.data[0].nom_persona;
				document.getElementById("input_fecha_nac").value=resp.data[0].fec_nacimiento;
				document.getElementById("input_edad").value=resp.data[0].edad;
				document.getElementById("input_celular").value=resp.data[0].celular;
				document.getElementById("input_fuente1").textContent=resp.data[0].fuente;
				document.getElementById("input_fuente1").value=resp.data[0].id_fuente;
				document.getElementById("input_ue").value=resp.data[0].unid_educativa;
				document.getElementById("input_turno1").textContent=resp.data[0].turno;
				document.getElementById("input_turno1").value=resp.data[0].id_turno;
				document.getElementById("input_nivel1").textContent=resp.data[0].nivel;
				document.getElementById("input_nivel1").value=resp.data[0].id_nivel;
				document.getElementById("input_grado").value=resp.data[0].grado;
				document.getElementById("input_zona").value=resp.data[0].zona;
				document.getElementById("input_calle").value=resp.data[0].direccion;
				document.getElementById("input_t_apellido_paterno0").value=resp.data[0].pat_tutor;
				document.getElementById("input_t_apellido_materno0").value=resp.data[0].mat_tutor;
				document.getElementById("input_t_nombre0").value=resp.data[0].nom_tutor;
				document.getElementById("input_t_actividad0").value=resp.data[0].act_tutor;
				document.getElementById("input_t_trabajo0").value=resp.data[0].trab_tutor;
				document.getElementById("input_t_telefono0").value=resp.data[0].telefono_tutor;
				document.getElementById("input_t_celular0").value=resp.data[0].celular_tutor;
				if(tipo===2){
					document.getElementById("Registrar").value="Re-inscribir";
					document.getElementById("form_estudiante").action="<?php echo base_url()?>estudiantes/registrar_estudiante";
					document.getElementById("id").value=id;
				}else{
					document.getElementById("card-clase").hidden=true;
					document.getElementById("card-pago").hidden=true;
				}
			},error:function(){

			}
		})
	}

	function limpiar_form(){
		document.getElementById("card-clase").hidden=false;
		document.getElementById("card-pago").hidden=false;
		document.getElementById("Registrar").value="Registrar";
		document.getElementById("form_estudiante").action="<?php echo base_url()?>estudiantes/registrar_estudiante";
		limpieza_form();
	}

</script>
<?php
	$this->endSection();



?>

