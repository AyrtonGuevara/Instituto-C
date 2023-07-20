<?php
	$this->extend('Template/Head');
	$this->section('content');
?>

<div class="container">
	<form action="<?php echo base_url() ?>ubicacion/registrar_ubicacion" method="post" accept-charset="utf-8">
		<div class="row">
			<div class="col-sm-6">
				<input type="text" name="zona" id="zona" required>
				<label for="zona">zona</label>
			</div>
			<div class="col-sm-6">
				<input type="text" name="direccion" id="direccion" required>
				<label for="direccion">direccion</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<input type="text" name="detalle" id="detalle">
				<label for="detalle">detalle</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<input type="text" name="descripcion" id="descripcion">
				<label for="descripcion">descripcion</label>
			</div>
		</div>
		<input type="submit" name="Registrar" value="Registrar">
	</form>
</div>

<?php
	$this->endSection();
?>