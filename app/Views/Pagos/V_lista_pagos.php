<?php
/*
	Ayrton Jhonny Guevara Montaño 18-12-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Pagos</h2>
		</div>
		<div class="card-body">
			<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-basic text-nowrap" id="tbl_est">
					<caption></caption>
					<thead>
						<tr>
							<th>Nº</th>
							<th>Tutor</th>
							<th>Celular</th>
							<th>Estudiante</th>
							<th>Monto Cancelado</th>
							<th>Monto deuda</th>
							<th>Estado</th>
							<th>Fecha Pago</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($lista_pagos as $key) {
							echo "<tr>";
							echo"<td>".$key->nro."</td>";
							echo"<td>".$key->tutor."</td>";
							echo"<td>".$key->celular."</td>";
							echo"<td>".$key->estudiante."</td>";
							echo"<td>".$key->monto_cancelado."</td>";
							echo"<td>".$key->monto_deuda."</td>";
							echo"<td>".$key->estado."</td>";
							echo"<td>".$key->fec_pago."</td>";
							echo "<td><button class= 'btn btn-warning' name='Asistencia' onclick='ver_estudiante(".$key->id_estudiante.",0)'><i class='bi bi-eye' title='Ver Detalles'></i></button>

								<button class= 'btn btn-success' name='Asistencia' onclick='cancelar_deuda(".$key->id_det_pago.",this)'><i class='bi bi-cash' title='Cancelar'></i></button></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
		<div class="card-footer">
			<div class="accordion" id="accordionExample">
  				<div class="accordion-item" id="lista_clases">
  				</div>
  			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancelar_deuda_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo base_url()?>lista_pagos/registrar_pago" method="post" accept-charset="utf-8">
      <div class="modal-body">
      	<input type="text" name="id" id="id" hidden>
      	<label for="tutor" class="form-label">Tutor:</label>
      	<input type="text" class="form-control" name="tutor" id="input_tutor">
      	<label for="total" class="form-label">Total:</label>
      	<input type="text" class="form-control" name="total" id="input_total">
      	<label for="monto_pre" class="form-label">Monto cancelado previamente:</label>
      	<input type="number"  class="form-control" name="monto_pre" id="input_monto_pre">
      	<label for="monto_c" class="form-label">Monto cancelado:</label>
      	<input type="number" class="form-control" name="monto_c" id="input_monto_c">
      	<label for="saldo" class="form-label">Saldo:</label>
      	<input type="number"class="form-control" name="saldo" id="input_saldo" readonly>
      	<label for="fec_pago" class="form-label">Nueva fecha de pago</label>
      	<input type="date" class="form-control" name="fec_pago" id="input_fec_pago">
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary border-light" name="Registrar" value="Registrar" id="Registrar">
      </div>
  		</form>
    </div>
  </div>
</div>

<script>
	function ver_estudiante(id,cont){
		//ojo, esta buscando inactivos
		$.ajax({
			url:'<?php echo base_url()?>lista_estudiantes/ver_estudiante',
			type:'POST',
			data:{id:id,
				cont:cont},
			success:function(){
				window.location.href="<?php echo base_url()?>estudiantes";
			}
		})
	}
	function cancelar_deuda(id,boton){
		//conseguir la informacion de la fila
		var fila = boton.parentNode.parentNode;
		var celda_tipo = fila.cells[6].textContent;
		if(celda_tipo != 'cancelado'){
			$('#cancelar_deuda_modal').modal('show');
			inputs=document.querySelectorAll("input[id^=input_]");
			document.getElementById("Registrar").disabled=false;
			for (var i = 0; i < inputs.length; i++) {
				inputs[i].value="";
			}
			document.getElementById("id").value=id;
			var celda_tutor = fila.cells[1].textContent;
			document.getElementById("input_tutor").value=celda_tutor;
			document.getElementById("input_tutor").disabled=true;
			var celda_monto_pre = fila.cells[4].textContent;
			document.getElementById("input_monto_pre").value=celda_monto_pre;
			document.getElementById("input_monto_pre").disabled=true;
			var celda_monto_c = fila.cells[5].textContent;
			document.getElementById("input_total").value=parseFloat(celda_monto_pre)+parseFloat(celda_monto_c);
			document.getElementById("input_total").disabled=true;
		}
	}
	document.getElementById("input_monto_c").addEventListener("change",function(){

		input_monto_pre=document.getElementById("input_monto_pre");
		input_monto_c=document.getElementById("input_monto_c");
		input_total=document.getElementById("input_total");
		monto_pre = (input_monto_pre && input_monto_pre.value) ? parseFloat(input_monto_pre.value) : 0;
		monto_c = (input_monto_c && input_monto_c.value) ? parseFloat(input_monto_c.value) : 0;
		total = (input_total && input_total.value) ? parseFloat(input_total.value) : 0;
		nuevo_monto=total-(monto_c+monto_pre);
		if (nuevo_monto===0) {	
			document.getElementById("input_fec_pago").disabled=true;
			document.getElementById("input_saldo").value=nuevo_monto;
		}else if(nuevo_monto>0){
			input_saldo=document.getElementById("input_saldo");
			input_saldo.value=nuevo_monto;
		}else if(nuevo_monto<0){
			document.getElementById("Registrar").disabled=true;
			document.getElementById("input_saldo").value=nuevo_monto;
		}
	});
</script>
<?php
	$this->endSection();
?>