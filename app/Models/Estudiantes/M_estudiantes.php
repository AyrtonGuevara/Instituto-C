<?php
/*
	Ayrton Jhonny Guevara Montaño 03-08-2023
*/
	namespace App\Models\Estudiantes;
	use CodeIgniter\Model;

	class M_estudiantes extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
	}
?>