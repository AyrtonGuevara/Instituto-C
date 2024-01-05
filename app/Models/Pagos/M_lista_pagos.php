<?php
/*
	Ayrton Jhonny Guevara Montaño 18-12-2023
*/
	namespace App\Models\Pagos;
	use CodeIgniter\Model;

	class M_lista_pagos extends Model{
		public function __construct(){
			$this->db=db_connect();
		}
		public function lista_pagos(){
			$respuesta=$this->db->query("
				select row_number()over() as nro, 
					ae.id_estudiante, 
					cdp.id_det_pago, 
					tutor.tutor, 
					tutor.celular, 
					concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona)as estudiante, 
					cdp.monto_cancelado, 
					cdp.monto_deuda, 
					cdp.estado, 
					cdp.fec_pago::date
				from com_pago cp, 
					com_detalle_pago cdp, 
					aca_inscripcion ai, 
					aca_estudiante ae, 
					ral_persona rp, 
					(select ct.id_tutor, rp.celular, concat(rp.nom_persona,' ',rp.ap_pat_persona,' ',rp.ap_mat_persona) as tutor 
						from com_tutor ct, ral_persona rp 
						where ct.id_persona=rp.id_persona 
						and ct.estado='activo' 
						and rp.estado='activo')as tutor
				where cp.id_pago=cdp.id_pago 
				and cdp.id_inscripcion = ai.id_inscripcion
				and ai.id_estudiante=ae.id_estudiante
				and ae.id_persona=rp.id_persona
				and ae.id_tutor=tutor.id_tutor
				and cp.estado='activo';
				");
			return $respuesta->getResult();
		}
		public function registrar_pago($id,$monto_c,$saldo,$fec_pago){
			$respuesta=$this->db->query("
				update com_detalle_pago 
				set 
				monto_cancelado=(case 
					when (select monto_cancelado from com_detalle_pago where id_det_pago=$id) isnull 
					then 
						$monto_c
					else 
						(select monto_cancelado from com_detalle_pago where id_det_pago=$id)+$monto_c
					end),
				monto_deuda=$saldo,
				estado=(case when $saldo = 0 then 'cancelado' 
					else 'plazos'
					end),
				fec_pago = (CASE WHEN '$fec_pago' = '0' THEN null ELSE NULLIF('$fec_pago', '')::date END)
				where id_det_pago=$id;
				");
			return $respuesta;
		}
	}
?>