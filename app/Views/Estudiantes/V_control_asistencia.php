<?php
/*
	Ayrton Jhonny Guevara Montaño 28-10-2023
*/
	$this->extend("Template/Head");
	$this->section("content");
?>
<div class="container">
	<div class="card">
		<div class="card-header">
			<h2>Clases</h2>
		</div>
		<div class="card-body">
			<label for="clase_busc">Clase:</label>
			<select class="form-control" name="clase_busc" id="clase_busc">
				<option default></option>
				<?php
		      	foreach ($lista_cursos->getResult() as $key) {
		      		echo "<option value='".$key->id_aula."'>".$key->aula."</option>";
		      	}
		      	?>
			</select>
		</div>
		<div class="card-footer">
			<div class="accordion" id="accordionExample">
  				<div class="accordion-item" id="lista_clases">

  				</div>
  			</div>
		</div>
	</div>
</div>
<script defer>
	document.addEventListener("DOMContentLoaded",function(){
		
	});
	document.getElementById("clase_busc").addEventListener("change",function(){
		id=document.getElementById("clase_busc").value;
		limpiar_acordion();
		listar_clases(id);
	});
	function listar_clases(id){
		$.ajax({
			url:'<?php echo base_url()?>control_asistencia/lista_clases_aulas',
			type:'POST',
			data:{id:id},
			success:function(resp){
				resp=JSON.parse(resp);
				cont=0;
				for(key of resp.data){
					console.log(key);
					acordion="<div class='accordion' id='accordionExample'>"+
					  	"<div class='accordion-item'>"+
					    	"<h2 class='accordion-header' id='heading"+cont+"'>"+
					      		"<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse"+cont+"' aria-expanded='true' aria-controls='collapse"+cont+"' onclick='comprobacion("+cont+","+key.id_clase+")'>"+
					        key.nombre_materia+" | HORARIOS: "+key.dias+
					      		"</button>"+
					    	"</h2>"+
					    "<div id='collapse"+cont+"' class='accordion-collapse collapse' aria-labelledby='heading"+cont+"' data-bs-parent='#accordionExample'>"+
					    "<div class='accordion-body'>"+
					    	"<input type='month' name='mes"+cont+"' id='mes"+cont+"' class='form-control' onchange='asistencia_clases("+cont+","+key.id_clase+")'>"+
					    	"<div id='listas_alumnos"+cont+"' class='table-responsive'>"+
							"</div>"+
					    "</div>"+
					"</div>";
					document.getElementById("lista_clases").innerHTML+=acordion;
					cont++;
				}
			},error:function(){

			}
		});
	}
	function comprobacion(cont,id){
		uno=document.getElementById('listas_alumnos'+cont);
		if(uno.innerHTML===''){
			asistencia_clases(cont,id);
		}
	};
	function asistencia_clases(cont, id){
		comp_mes=document.getElementById("mes"+cont).value;
		document.getElementById("listas_alumnos"+cont).innerHTML="";
		if(comp_mes===""){
			comp_mes="2023-11";
			console.log(comp_mes);
		}else{
			console.log(comp_mes);
		}
		//se inicia con la busqueda de fechas de la clase en el mes seleccionado
		$.ajax({
			url:"<?php echo base_url()?>control_asistencia/buscar_fechas_clase",
			type:"POST",
			data:{id:id,
				comp_mes:comp_mes},
			success:function(resp){
				resp=JSON.parse(resp);
				const tabla = document.createElement("table");
			    const thead = tabla.createTHead();
			    const headerRow = thead.insertRow();
			    const th = document.createElement("th");
				th.textContent = "Nº";
			    headerRow.appendChild(th);
			    const th2 = document.createElement("th");
				th2.textContent = "Nombre";
			    headerRow.appendChild(th2);
			    for (const key of resp.data) {
			        const th = document.createElement("th");
			        th.textContent = (key.dia+' '+key.fecha);
			        th.setAttribute("id",key.fecha);
			        headerRow.appendChild(th);

			    }
			    tabla.setAttribute("id","tabla-"+id);
			    tabla.classList.add("table");
			    tabla.classList.add("table-hover");
			    tabla.classList.add("table-basic");
			    tabla.classList.add("text-nowrap");
			    document.getElementById('listas_alumnos'+cont).appendChild(tabla);
			    //ahora se llenan con las filas correspondientes
			    $.ajax({
			    	url:"<?php echo base_url()?>control_asistencia/buscar_estudiantes_asistencias",
			    	type:"POST",
			    	data:{id:id,
			    		comp_mes:comp_mes},
			    	success:function(resp){
			    		resp=JSON.parse(resp);
			    		//buscamos las columnas y la tabla para poder imprimir los datos
			    		const cells_cabs=document.querySelectorAll("table[id='tabla-"+id+"'] th[id*='/']");
			    		const tabla = document.getElementById("tabla-"+id);
			    		const tbody = tabla.createTBody();
			    		//buscamos la fecha actual en formato DD/MM para parar la impresion de celdas en el ultimo bucle
			    		const fechaActual = new Date();
						const dia = fechaActual.getDate().toString().padStart(2, '0');
						const mes = (fechaActual.getMonth() + 1).toString().padStart(2, '0');
						const fechaFormateada = `${dia}/${mes}`;
						//declaramos algunas variable mas
			    		var row;
			    		j=0;
			    		id_est=0;
			    		numero=0
			    		//iniciamos con la lectura de datos
			    		for(const key2 of resp.data){
			    			//variables reutilizables
			    			preg=true;
			    			//verificamos si es nuevo alumno en la tabla
			    			if(id_est!==key2.id_estudiante){
			    				//se comprueba que cells_cabs no sea undefined
			    				if(cells_cabs[j]!=undefined){
			    					//rellenamos celdas restantes siempre y cuando no sea la primera fila
				    				if(id_est!==0 && cells_cabs[j].id<fechaFormateada){
				    					for(k=j;k<cells_cabs.length;k++){
				    						if(cells_cabs[k].id<fechaFormateada){
				    							row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
				    						}else{
				    							row.insertCell();
				    						}
				    					}
				    				}
			    				}
			    				numero++;
			    				j=0;
			    				row = tbody.insertRow();
			    				row.insertCell().textContent=numero;
            					row.insertCell().textContent=key2.nombre;
            					id_est=key2.id_estudiante;
			    			}
			    			//verficamos fechas previas a la clase de la primera asistencia registrada
			    			do{
			    				if(cells_cabs[j]!=undefined){
			    					if(cells_cabs[j].id===key2.fecha){
				    					row.insertCell().innerHTML=key2.detalle;
				    					preg=false;
				    				}else{
				    					row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
				    				}
			    				}		    				
			    				j++;
			    				//por seguridad, establecemos que si j excede el numero de filas que se detenga
			    				if(j>=cells_cabs.length){
			    					preg=false;
			    				}
			    			}while(preg);
			    		}
			    		if(id_est!==0){
			    			for(k=j;k<cells_cabs.length;k++){
			    				if(cells_cabs[k].id<fechaFormateada){
			    					row.insertCell().setAttribute("style","background-color:rgb(204,204,204)");
			    				}else{
			    					row.insertCell();
			    				}
			    			}
			    		}
			    	},error:function(){

			    	}
			    })
			},error:function(){

			}
		})
	}
	function limpiar_acordion(){
		document.getElementById("lista_clases").innerHTML="";
	}
</script>
<?php
	$this->endSection();
?>