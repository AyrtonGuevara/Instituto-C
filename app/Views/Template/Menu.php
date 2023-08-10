<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
?>
<div id="sidenav" class="sidenav">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<ul style="color:white;" id="accordion">
		<li><a class="btn" data-bs-toggle="collapse" href="#col1">Usuario</a></li>
		<ul id="col1" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>usuario" id="item_collapse">Usuario</a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col2">Personal</a></li>
		<ul id="col2" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>personal" id="item_collapse">Personal</a></li>
		</ul>
		<li> <a class="btn" data-bs-toggle="collapse" href="#col3">Aulas</a></li>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>aula" id="item_collapse">Aulas</a></li>
			<li><a href="<?php echo base_url()?>ubicacion" id="item_collapse">Ubicacion</a></li>
		</ul>
	</ul>
</div>