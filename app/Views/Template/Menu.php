<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
?>
<div id="sidenav" class="sidenav">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<ul style="color:white;" id="accordion">
		<li><a class="btn" data-bs-toggle="collapse" href="#col1">Cursos</a></li>
		<ul id="col1" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>cursos" id="item_collapse">Cursos</a></li>
			<li><a href="<?php echo base_url()?>horarios" id="item_collapse">Horarios</a></li>
			<li><a href="<?php echo base_url()?>materias" id="item_collapse">Materias</a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col2">Ambientes</a></li>
		<ul id="col2" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>ambientes" id="item_collapse">Ambientes</a></li>
			<li><a href="<?php echo base_url()?>clases" id="item_collapse">Clases</a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col3">Estudiantes</a></li>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>estudiantes" id="item_collapse">Estudiantes</a></li>
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