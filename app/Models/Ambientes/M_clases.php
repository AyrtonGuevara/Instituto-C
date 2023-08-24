<?php
/*
	Ayrton Jhonny Guevara Montaño 23-10-2023
*/
	namespace App\Models\Ambientes;
	use CodeIgniter\Model;

	class M_clases extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
	}
?>