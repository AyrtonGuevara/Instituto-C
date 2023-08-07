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
			<li><a href="<?php echo base_url()?>usuario">Usuario</a></li>
		</ul>
		<li><a class="btn" data-bs-toggle="collapse" href="#col2">Personal</a></li>
		<ul id="col2" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>personal">Personal</a></li>
		</ul>
		<li> <a class="btn" data-bs-toggle="collapse" href="#col3">Ubicacion</a></li>
		<ul id="col3" class="collapse" data-bs-parent="#accordion">
			<li><a href="<?php echo base_url()?>ubicacion">Ubicacion</a></li>
		</ul>
	</ul>
</div>

<!--
	<div id="accordion">
		<div class="card">
		    <div class="card-header" style="background-color: black;">
		      <a class="btn" data-bs-toggle="collapse" href="#collapseOne">
		        Collapsible Group Item #1
		      </a>
		    </div>
		    <div id="collapseOne" class="collapse" data-bs-parent="#accordion">
		      <div class="card-body">
		        Lorem ipsum..
		      </div>
		    </div>
		  </div>

		  <div class="card">
		    <div class="card-header">
		      <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseTwo">
		        Collapsible Group Item #2
		      </a>
		    </div>
		    <div id="collapseTwo" class="collapse" data-bs-parent="#accordion">
		      <div class="card-body">
		        Lorem ipsum..a
		      </div>
		    </div>
		  </div>

		  <div class="card">
		    <div class="card-header">
		      <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseThree">
		        Collapsible Group Item #3
		      </a>
		    </div>
		    <div id="collapseThree" class="collapse" data-bs-parent="#accordion">
		      <div class="card-body">
		        Lorem ipsum..
		      </div>
		    </div>
		  </div>
	</div>
</div>-->