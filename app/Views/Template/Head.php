<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="public/css/bootstrap.min.css" rel="stylesheet">
	<script src="public/js/bootstrap.bundle.min.js"></script>

	<!--JQUERY-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- jQuery UI -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!--SWEET ALERT-->
	<!-- SweetAlert 2 JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
	<!-- SweetAlert 2 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
	<!--icons-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<title>
		<?php // echo $title; ?>
	</title>
	<link rel="stylesheet" href="public/css/style.css">
	<script src="public/js/functions.js"></script>
</head>
<body>
	<nav class="navbar nav">
		<div class="container-fluid">
			<span style="cursor:pointer" onclick="opensidenav()" id="open_sidenav">&#9776; Men&uacute;</span>
			<div class="studens_ral_info">
				<p class="p1">34</p>
				<p class="p2">50</p>
				<p class="p3">35</p>
			</div>
			<div class="user-info">
			<table>
				<thead>
					<tr>
						<th><?php echo session()->usuario; ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo session()->nivel;?></td>
					</tr>
				</tbody>
			</table>
				<a href="<?php base_url()?>cerrar_sesion" class="btn btn-danger" title="Cerrar Sesion"><i class="bi bi-power"></i></a>
			</div>
		</div>
	</nav>	
	<div class="content" id="main_content"> 
	<?php include('Menu.php') ?>
	<?= $this -> renderSection('content') ?>
	<?php include('Foot.php')?>