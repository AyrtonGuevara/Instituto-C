<?php
/*
	Ayrton Jhonny Guevara Montaño 30-07-2023
*/
	namespace App\Models\Ambientes;

	use CodeIgniter\Model;

	class M_ubicacion extends Model{
		public function __constructs(){
			$this->db=db_connect();
		}

		public function agregar_ubicacion($usuario,$j_envio)
		{
			$respuesta=$this->db->query("
				select * from public.fn_agregar_ubicacion('$usuario','$j_envio'::JSON);
			");
			return $respuesta->getResult();
		}
		public function listar_ubicacion()
		{
			$respuesta=$this->db->query("
				select * from public.fn_listar_ubicacion();
			");
			return $respuesta->getResult();
		}
		public function mostrar_ubicacion($id)
		{
			$respuesta=$this->db->query("
				select au.id_ubicacion, au.zona,au.direccion,au.descripcion,au.detalle 
				from adm_ubicacion au 
				where au.id_ubicacion=$id 
				and au.estado = 'activo';
			");
			return $respuesta->getResult();
		}

		public function modificar_ubicacion($usuario,$id,$zona,$direccion,$detalle,$descripcion){
			$respuesta=$this->db->query("
				update adm_ubicacion 
				set zona='$zona',
					direccion='$direccion',
					detalle ='$detalle',
					descripcion='$descripcion', 
					usu_modificado='$usuario', 
					fec_modificado=now() 
				where id_ubicacion=$id;
				");
			return $respuesta;
		}

		public function eliminar_ubicacion($usuario,$id)
		{
			$respuesta=$this->db->query("
				update aca_aula 
				set usu_modificado='$usuario', 
					fec_modificado=now(), 
					estado='inactivo' 
				where id_ubicacion = $id; 
				
				update adm_ubicacion 
				set usu_modificado='$usuario', 
					fec_modificado=now(),
					estado='inactivo' 
				where id_ubicacion = $id;
				");
			return $respuesta;
		}
		public function modal_mostrar_aulas($id){
			$respuesta=$this->db->query("
				select row_number ()over() as nro, aa.id_aula, aa.id_ubicacion, au.direccion, aa.nombre_aula, aa.cantidad_estudiantes 
				from aca_aula aa , adm_ubicacion au 
				where aa.id_ubicacion=au.id_ubicacion 
				and aa.estado = 'activo' 
				and au.estado = 'activo' 
				and au.id_ubicacion=$id 
				order by id_aula;
			");
			return $respuesta->getResult();
		}
		public function modal_modificar_aulas($json_aulas,$usuario){
			$respuesta=$this->db->query("
				select * from public.fn_modificar_aulas('$usuario','$json_aulas'::JSON);
			");
			return $respuesta->getResult();
		}
		public function cascada_ubicacion($id){
			$respuesta=$this->db->query("
				select coalesce(
					(select au.id_ubicacion::bool
					from adm_ubicacion au, aca_aula aa, aca_clase ac
					where au.id_ubicacion=aa.id_ubicacion
					and aa.id_aula=ac.id_aula
					and aa.estado='activo'
					and au.estado='activo'
					and ac.estado='activo'
					and au.id_ubicacion=$id
					limit 1),'f')as column;
			");
			return $respuesta->getResult();
		}
	}
?>