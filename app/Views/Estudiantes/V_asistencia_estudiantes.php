<?php
/*
	Ayrton Jhonny Guevara Montaño 13-10-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Asistencia</h2>
		</div>
		<div class="card-body">
			<div id="acordion_clases">
				
			</div>
		</div>
	</div>
</div>
<script>
	var cells;
	document.addEventListener("DOMContentLoaded",function(){
		div_asistencia="";
		//se buscan las clases que se esten llevando ese momento
		$.ajax({
			url:"<?php echo base_url()?>asistencia/clases_activas",
			type:"POST",
			success:function(resp){
				var data = JSON.parse(resp);
				var clases=JSON.parse(data.data[0].fn_asistencia_estudiantes);
				var contador=0;
				div_asistencia="<div>";
				if(clases.Error){
					div_asistencia=div_asistencia+"<h3>No hay clases activas en este momento</h3>";
				}else{		
					for(const key2 of clases){
						contador=0;
						var idClase = key2.id_clase;
						var nombre_clase=key2.nombre_clase;
			        	var alumnosClase = key2.alumnos_clase;
			        	var empty=(alumnosClase!==null)?true:false;
						div_asistencia=div_asistencia+"<div class='accordion-item'>"+
    						"<h2 class='accordion-header' id='headingTwo'>"+
      							"<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse"+idClase+"' aria-expanded='false' aria-controls='collapseTwo' onclick='refresh_asistencias("+empty+","+idClase+")'>"+
        								nombre_clase+
      							"</button>"+
    						"</h2>"+
    						"<div id='collapse"+idClase+"' class='accordion-collapse collapse' aria-labelledby='headingTwo' data-bs-parent='#accordionExample'>"+
						     	"<div class='accordion-body'>";
						    if (empty) { 	
						      	div_asistencia=div_asistencia+"<div class='table-responsive'>"+
						        		"<table class='table table-hover'>"+
								        	"<tr><th>Nº</th>"+
								        	"<th>Esrtudiante</th>"+
								        	"<th>Asistencia</th>"+
								        	"<th>Atraso</th>"+
								        	"<th>Falta</th>"+
								        	"<th>Permiso</th></tr>";
								for(const key3 of alumnosClase){
					        		contador++;
					        		var estudiante = key3.estudiante;
						            var idEstudiante = key3.id_estudiante;
						            var idInscripcion = key3.id_inscripcion;
						            div_asistencia=div_asistencia+"<tr><td>"+contador+"</td>"+
						            "<td>"+estudiante+"</td>"+
						            "<td><input class='form-check-input' type='radio' name='asistencia-"+idEstudiante+"-"+idClase+"' id='a_presente' value='presente' onclick='cambio_asistencia(this)'></td>"+
						            "<td><input class='form-check-input' type='radio' name='asistencia-"+idEstudiante+"-"+idClase+"' id='a_atraso' value='atraso' onclick='cambio_asistencia(this)'></td>"+
						            "<td><input class='form-check-input' type='radio' name='asistencia-"+idEstudiante+"-"+idClase+"' id='a_falta' value='falta' onclick='cambio_asistencia(this)'></td>"+
						            "<td><input class='form-check-input' type='radio' name='asistencia-"+idEstudiante+"-"+idClase+"' id='a_permiso' value='permiso' disabled></td>"+
						            "</tr>"
					        	}
					        	div_asistencia=div_asistencia+"</table>"+
			        		  		"</div>";
							
						}else{
							div_asistencia=div_asistencia+"<h3>No existen alumnos registrados en esta clase</h3>";
						}
			        	div_asistencia=div_asistencia+"</div>"+
						    "</div>"+
						  "</div>";
					//}
					}
				}
				div_asistencia=div_asistencia+"</div>";
				document.getElementById("acordion_clases").innerHTML=div_asistencia;
			},error:function(){
				$('#respuesta').text('Error al conectar con el servidor');
			}
		});
	});
	//se registra el valor de la asistencia
	function cambio_asistencia(checkbox) {
	    const nombre = checkbox.name;
	    const valor = checkbox.value;
	    $.ajax({
	    	url:"<?php echo base_url()?>asistencia/registrar_asistencia",
	    	type:"POST",
	    	data:{nombre:nombre,
	    		valor:valor},
	    	success : function(resp){
	    		resp=JSON.parse(resp);
	    		if(resp.data[0].success==='t'){
	    		}else{
	    			Swal.fire({
						title:'Error inesperado',
						text:resp.data[0].mensaje,
						icon:'error',
						confirmButtonColor:'#111111',
						confirmButtonText:'Aceptar'
					})
	    		}
	    	},error:function(){
	    		$('#respuesta').text('Error al conectar con el servidor');
	    		Swal.fire({
						title:'Error inesperado en el registro',
						icon:'error',
						confirmButtonColor:'#111111',
						confirmButtonText:'Aceptar'
					})
	    	}
	    });
	}
	//se muestra asistencias registradas si ya las habia
	function refresh_asistencias(alumnosClase,id_clase){
		if (alumnosClase) {
			$.ajax({
				url:"<?php echo base_url()?>asistencia/asistencias_guardadas",
				type:"POST",
				data:{id_clase},
				success:function(resp){
					resp=JSON.parse(resp);
					for(const key of resp.data){
						const checkbox=document.querySelector("input[type='radio'][name='"+key.estudiante+"'][value='"+key.detalle+"']");
						checkbox.checked=true;
					}
				},error:function(){

				}
			})
		}else{}
	}

</script>	
<?php
	$this->endSection();
?>

