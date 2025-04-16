<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
	$this->extend('Template/Head');
	$this->section('content');
?>
<div class="container">
	<div class="card">
		<div class="card-body">
			<h1>Bienvenido</h1>
			<div>
			</div>
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded",function(){
		var fecha="<?php echo $fecha_sistema?>";
		const f=new Date();
		var fecha_actual=f.getFullYear()+"-"+(f.getMonth()+1)+"-"+f.getDate();
		if(fecha!==fecha_actual){
			$.ajax({
				url:'<?php echo base_url()?>inicio/funcion_inicio',
				type:'GET',
				success:function(resp){
					resp=JSON.parse(resp);
					if(!resp.success){
						Swal.fire({
							title:'Error en la funcion de inicio',
							text:resp.mensaje,
							icon:'error',
							confirmButtonColor:'#111111',
							confirmButtonText:'Aceptar'
						})
					}
				},error:function(){
					$('#respuesta').text('Error al conectar con el servidor');
				}
			});
		}else{
			console.log("son iguales");
		}
	})
</script>
<?php
	$this->endSection();
?>