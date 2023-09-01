
	function opensidenav(){
			document.getElementById("sidenav").style.width = "170px";
  			document.getElementById("main_content").style.marginLeft = "170px";
  			document.getElementById("footer_content").style.marginLeft = "170px";
	}
	function closeNav() {
		  document.getElementById("sidenav").style.width = "0";
		  document.getElementById("main_content").style.marginLeft = "0";
		  document.getElementById("footer_content").style.marginLeft = "0";
	}
	/*function limpiar_form(){
		id=document.getElementById('id');
		zona=document.getElementById('zona');
		direccion=document.getElementById('direccion');
		detalle=document.getElementById('detalle');
		descripcion=document.getElementById('descripcion');
		id.value="";
		zona.value="";
		direccion.value="";
		detalle.value="";
		descripcion.value="";
		var btnclosed=document.getElementById('salir_edicion');
		var btnad=document.getElementById('Registrar');
		var btnmd=document.getElementById('Modificar');
		var form=document.getElementById('form_ubicacion');
		btnad.classList.add("btn-primary");
		btnmd.classList.remove("btn-primary");
		btnclosed.hidden=true;
		btnad.disabled=false;
		btnmd.disabled=true;
		form.action="<?php echo base_url()?>ambientes/registrar_ubicacion";
	}*/
	function limpieza_form(){
		var inputs=document.querySelectorAll("[id^=input]");
		for(var i=0;i<inputs.length;i++){
			inputs[i].value="";
			inputs[i].textContent="";
		}
		var btnclosed=document.getElementById('salir_edicion');
		var btnad=document.getElementById('Registrar');
		var btnmd=document.getElementById('Modificar');
		btnad.classList.add("btn-primary");
		btnmd.classList.remove("btn-primary");
		btnclosed.hidden=true;
		btnad.disabled=false;
		btnmd.disabled=true;
	}