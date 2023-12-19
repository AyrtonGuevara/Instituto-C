<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
?>
<div id="sidenav" class="sidenav">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<ul style="color:white;" id="accordion">
		<li><a class="btn" data-bs-toggle="collapse" href="#col1"><button type="button" class="btn btn-primary btn-menu">Cursos</button></a></li>
		<ul id="col1" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>cursos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Cursos</button></a></li>
			<li><a href="<?php echo base_url()?>horarios" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Horarios</button></a></li>
			<li><a href="<?php echo base_url()?>materias" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Materias</button></a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col2"><button type="button" class="btn btn-primary btn-menu">Ambientes</button></a></li>
		<ul id="col2" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>ambientes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Ambientes</button></a></li>
			<li><a href="<?php echo base_url()?>clases" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Clases</button></a></li>
			<!--<li><a href="<?php echo base_url()?>clases_lista" id="item_collapse">Clases Lista</a></li>-->
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col3"><button type="button" class="btn btn-primary btn-menu">Estudiantes</button></a></li>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>estudiantes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Registro Estudiantes</button></a></li>
			<li><a href="<?php echo base_url()?>control_clases" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Control Aulas</button></a></li>
			<li><a href="<?php echo base_url()?>asistencia" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Asistencia Estudiantes</button></a></li>
			<li><a href="<?php echo base_url()?>control_asistencia" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Control asistencia</button></a></li>
			<li><a href="<?php echo base_url()?>lista_estudiantes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Lista de Estudiantes</button></a></li>
			<li><a href="<?php echo base_url()?>permisos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Permisos</button></a></li>
			<li><a href="<?php echo base_url()?>reinscripcion" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Reinscripciones</button></a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col4"><button type="button" class="btn btn-primary btn-menu">Pagos</button></a></li>
		<ul id="col4" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>lista_pagos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Lista Pagos</button></a></li>
		</ul>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
		</ul>

		<li>----------------------</li>
		<li><a class="btn" data-bs-toggle="collapse" href="#col111">Usuario</a></li>
		<ul id="col111" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>usuario" id="item_collapse">Usuario</a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col112">Personal</a></li>
		<ul id="col112" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>personal" id="item_collapse">Personal</a></li>
		</ul>
	</ul>
</div>