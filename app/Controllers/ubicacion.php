<?php
	namespace App\Controllers;

	class ubicacion extends BaseController{
		public function index(){
			return view('welcome_message');
		}
		public function dos(){
			return view('categoria/categoria');
		}
	}
?>