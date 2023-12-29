<?php
/*
	Ayrton Jhonny Guevara Montaño 19-12-2023
*/
	namespace App\Models\Configuracion;
	use CodeIgniter\Model;

	class M_permisos extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
	}
?>