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
</head>
<body>
	<nav class="navbar bg-info">
		<div class="container-fluid">
				cabecera? o solo head
		</div>
	</nav>	
	<?php include('Menu.php') ?>
	<?= $this -> renderSection('content') ?>
	<?php include('Foot.php')?>

