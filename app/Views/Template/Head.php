<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Latest compiled and minified CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Latest compiled JavaScript -->	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!--JQUERY-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!--SWEET ALERT-->
	<!-- SweetAlert 2 JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

	<!-- SweetAlert 2 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
	<title>
		
	</title>
	<link rel="stylesheet" href="">
	<style type="text/css">
		.sidenav {
		  height: 100%; /* 100% Full-height */
		  width: 0; /* 0 width - change this with JavaScript */
		  position: fixed; /* Stay in place */
		  z-index: 1; /* Stay on top */
		  top: 64px;
		  left: 0;
		  background-color: #111; /* Black*/
		  overflow-x: hidden; /* Disable horizontal scroll */
		  padding-top: 60px; /* Place content 60px from the top */
		  padding-bottom: 80px; /*MArgen top porque si existe una diferencia*/
		  transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
		}
		/* The navigation menu links */
		.sidenav a {
		  padding: 8px 8px 8px 32px;
		  text-decoration: none;
		  font-size: 25px;
		  color: #818181;
		  display: block;
		  transition: 0.3s;
		}
		.sidenav li {
		  padding: 8px 8px 8px 32px;
		  text-decoration: none;
		  font-size: 25px;
		  color: #818181;
		  display: block;
		  transition: 0.3s;
		}

		.sidenav a:hover {
		  color: #f1f1f1;
		}
		/* Position and style the close button (top right corner) */
		.sidenav .closebtn {
		  position: absolute;
		  top: 0;
		  right: 25px;
		  font-size: 36px;
		  margin-left: 50px;
		}

		/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
		#main_content {
		  transition: margin-left .5s;
		  padding: 20px;
		}

		/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
		@media screen and (max-height: 450px) {
		  .sidenav {padding-top: 15px;}
		  .sidenav a {font-size: 18px;}
		}

	</style>
</head>
<body>
	<nav class="navbar bg-info">
		<div class="container-fluid">
			<span style="cursor:pointer" onclick="opensidenav()" id="open_sidenav">&#9776; Men&uacute;</span>
				<?php 
				echo session()->usuario;
				echo "<br>";
				echo session()->nivel;
				?>
				<a href="<?php base_url()?>cerrar_sesion" class="btn btn-danger">Cerrar sesion</a>
		</div>
	</nav>	
	<div class="content" id="main_content"> 
	<script>
		function opensidenav(){
			document.getElementById("sidenav").style.width = "250px";
  			document.getElementById("main_content").style.marginLeft = "250px";
		}
		function closeNav() {
		  document.getElementById("sidenav").style.width = "0";
		  document.getElementById("main_content").style.marginLeft = "0";
		}
	</script>
	<?php include('Menu.php') ?>
	<?= $this -> renderSection('content') ?>
	<?php include('Foot.php')?>

