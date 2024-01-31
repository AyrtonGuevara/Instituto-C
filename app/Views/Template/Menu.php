<?php
/*
	Ayrton Jhonny Guevara Montaño
*/
?>
<div id="sidenav" class="sidenav">
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<ul style="color:white;" id="accordion">
		<?php
		if (array_search('1-0',$menu_permisos)!==false){
		?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col1"><button type="button" class="btn btn-primary btn-menu">Cursos</button></a></li>
		<ul id="col1" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('1-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>cursos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Cursos</button></a></li>
			<?php
			}
			if(array_search('1-2',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>horarios" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Horarios</button></a></li>
			<?php
			}
			if(array_search('1-3',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>materias" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Materias</button></a></li>
			<?php } ?>
		</ul>
		<?php }
		if(array_search('2-0',$menu_permisos)!==false){
		?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col2"><button type="button" class="btn btn-primary btn-menu">Ambientes</button></a></li>
		<ul id="col2" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('2-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>ambientes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Ambientes</button></a></li>
			<?php
			}
			if(array_search('2-2',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>clases" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Clases</button></a></li>
			<?php } ?>
			<!--<li><a href="<?php echo base_url()?>clases_lista" id="item_collapse">Clases Lista</a></li>-->
		</ul>
		<?php }
		if(array_search('3-0',$menu_permisos)!==false){
		?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col3"><button type="button" class="btn btn-primary btn-menu">Estudiantes</button></a></li>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('3-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>estudiantes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Registro Estudiantes</button></a></li>
			<?php
			}
			if(array_search('3-2',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>control_clases" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Control Aulas</button></a></li>
			<?php
			}
			if(array_search('3-3',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>asistencia" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Asistencia Estudiantes</button></a></li>
			<?php
			}
			if(array_search('3-4',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>control_asistencia" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Control asistencia</button></a></li>
			<?php
			}
			if(array_search('3-5',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>lista_estudiantes" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Lista de Estudiantes</button></a></li>
			<?php
			}
			if(array_search('3-6',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>permisos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Permisos</button></a></li>
			<?php
			}
			if(array_search('3-7',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>reinscripcion" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Reinscripciones</button></a></li>
			<?php
			}
			?>
		</ul>
		<?php }
		if(array_search('4-0',$menu_permisos)!==false){
		?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col4"><button type="button" class="btn btn-primary btn-menu">Pagos</button></a></li>
		<ul id="col4" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('4-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>lista_pagos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Lista Pagos</button></a></li>
			<?php } ?>
		</ul>
		<?php }
			if(array_search('5-0',$menu_permisos)!==false){
		?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col5"><button type="button" class="btn btn-primary btn-menu">Personal</button></a></li>
		<ul id="col5" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('5-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>personal" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Personal</button></a></li>
			<?php
			}
			if(array_search('5-2',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>usuario" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Usuarios</button></a></li>
			<?php
			}
			?>
		</ul>
		<?php }
			if(array_search('6-0',$menu_permisos)!==false){
			?>
		<li><a class="btn" data-bs-toggle="collapse" href="#col6"><button type="button" class="btn btn-primary btn-menu">Configuraci&oacute;n</button></a></li>
		<ul id="col6" class="collapse" data-bs-parent="#accordion">
			<?php
			if(array_search('6-1',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>conf_permisos" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Permisos</button></a></li>
			<?php
			}
			if(array_search('6-2',$menu_permisos)!==false){
			?>
			<li><a href="<?php echo base_url()?>categorias" id="item_collapse"><button type="button" class="btn btn-primary btn-menu">Categorias</button></a></li>
			<?php
			}
			?>
		</ul>
		<?php } ?>
	</ul>
</div>