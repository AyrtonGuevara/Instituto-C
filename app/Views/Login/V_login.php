<?php
/*
	Ayrton Jhonny Guevara Montaño 03-08-2023
*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Latest compiled JavaScript -->	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!--JQUERY-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded",function(){
			console.log("pasajson");
			error=document.getElementById('anuncio_error');
			<?php 
			if (session()->getFlashData('error_usuario')) {
			?>
				error.textContent="Usuario erroneo";
				error.hidden=false;
			<?php
			}else if (session()->getFlashData("error_contraseña")) {
			?>
				error.textContent="Contraseña erronea";
				error.hidden=false;
			<?php
			}else{}
			?>
		});
	</script>
</head>
<body>
	<div class="content">
		<form action="<?php echo base_url();?>login/autenticar" method="POST" accept-charset="utf-8">
			<div class="position_relative">
				<div class="row position-absolute top-50 start-50 translate-middle">
					<div class="">
						<label for="usuario" class="col-sm-12 form-label">Usuario:</label>
						<input type="text" class="form-control" name="usuario" value="" placeholder="Ingrese el Usuario" required>
						<label for="psswd" class="form-label">Contraseña:</label>
						<input type="password" class="col-sm-12 form-control" name="psswrd" value="" placeholder="Ingrese Contraseña" required>
						<div class="container alert alert-danger" id="anuncio_error" hidden ></div>
					</div>
					<div>
						<input type="submit" class="btn btn-info" name="Ingresar" value="Ingresar">
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
<?php
?>