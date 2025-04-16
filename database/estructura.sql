--
-- PostgreSQL database cluster dump
--

-- Started on 2024-12-05 01:04:28 -04

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

--
-- Roles
--

CREATE ROLE admin;
ALTER ROLE admin WITH NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS PASSWORD 'md5f6fdffe48c908deb0f4c3bd36c032e72';
CREATE ROLE ayrton;
ALTER ROLE ayrton WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN NOREPLICATION NOBYPASSRLS;
CREATE ROLE ayrton2;
ALTER ROLE ayrton2 WITH SUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN NOREPLICATION NOBYPASSRLS PASSWORD 'md5ab2864ece782f26b1bbea79f8952b241';
CREATE ROLE postgres;
ALTER ROLE postgres WITH SUPERUSER INHERIT CREATEROLE CREATEDB LOGIN REPLICATION BYPASSRLS PASSWORD 'md53175bce1d3201d16594cebf9d7eb3f9d';






--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:30 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Completed on 2024-12-05 01:04:32 -04

--
-- PostgreSQL database dump complete
--

--
-- Database "creatica_instituto" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:32 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3289 (class 1262 OID 16415)
-- Name: creatica_instituto; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE creatica_instituto WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_BO.UTF-8' LC_CTYPE = 'es_BO.UTF-8';


ALTER DATABASE creatica_instituto OWNER TO postgres;

\connect creatica_instituto

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 274 (class 1255 OID 17083)
-- Name: fn_agregar_clases(text, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_agregar_clases(v_usuario text, valores_clase json, OUT mensaje text, OUT success boolean) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare
	v2_usuario 		text;
	v2_id_materia 	int;
	v2_id_horario 	int;
	v2_id_aula		int;
	v2_id_persona	int;

	v2_aula integer;
	v2_personal integer;
	v2_verificar boolean;

a_coincidencias integer[]; 

	begin
		v2_verificar:=true;
		mensaje:='';
		success:=true;
	--se extraen valores del json
		begin
			v2_id_materia:=valores_clase->>'id_materia';
			v2_id_horario:=valores_clase->>'id_horario';
			v2_id_aula:=valores_clase->>'id_aula';
			v2_id_persona:=valores_clase->>'id_persona';
			exception
			when others then 
			raise exception 'error al extraer datos del json :%',sqlerrm;
			mensaje:='error al extraer datos del json:'||sqlerrm;
			success:=false;
		end;
	--se comparan los horarios existentes activos con el horario nuevo y se crea una lista de coincidencias
	begin
		
		a_coincidencias:=(select array_agg(id_conf_horarios) 
				from (
				select distinct t2.id_conf_horarios
				from
				
				(select ah.id_horarios,
				ah.id_conf_horarios,
				ah.dias as dias1,
				split_part(ah.horarios,' || ',1) as hora_inicio1, 
				split_part(ah.horarios,' || ',2) as hora_fin1 
				from aca_horarios ah
				where ah.id_conf_horarios = v2_id_horario
				and ah.estado='activo')as t1,
				
				(select ah.id_horarios,
				ah.id_conf_horarios,
				ah.dias as dias2,
				split_part(ah.horarios,' || ',1) as hora_inicio2, 
				split_part(ah.horarios,' || ',2) as hora_fin2 
				from aca_horarios ah
				where ah.estado='activo')as t2
				where (hora_inicio1 <= hora_inicio2 and hora_fin1 >= hora_fin2 and dias1=dias2)::boolean = true)as t3);
		exception 
			when others then
			raise exception 'error al comparar los horarios:%',sqlerrm;
			mensaje:='error al comparar los horarios: '||sqlerrm;
			success:= false;
		end;
		--se compara la lista de coinicidencias con el aula
		begin
			for i in 1 .. greatest(array_length(a_coincidencias,1))
			loop
				v2_aula:=(select id_clase
				from aca_clase ac 
				where id_horarios = a_coincidencias[i]
				and id_aula = v2_id_aula
				and estado= 'activo');
				raise notice 'a_coincidencias:% --- v2_id_aula:%',a_coincidencias[i],v2_id_aula;
			
				--se compara las listas de coinicdencias con el docente
				v2_personal:=(select id_clase
				from aca_clase ac 
				where id_horarios = a_coincidencias[i]
				and id_personal = v2_id_persona
				and estado= 'activo');
				raise notice 'a_coincidencias:% --- v2_id_persona:%',a_coincidencias[i],v2_id_persona;
				raise notice 'v2_aula : % --- v2_personal:% --- coincidencias:%',v2_aula, v2_personal,a_coincidencias;
				--se verifica que no existan coincidencias
				if v2_aula notnull or v2_personal notnull then
					v2_verificar:=false;
				end if;
			end loop;
		exception
			when others then
			raise exception 'error al comparar el personal y el aula:%',sqlerrm;
			mensaje:= 'error al comparar el personal y el aula:%'||sqlerrm;
			success:=false;
		end;
	
	--se registra si no existe ningun cruce de datos
		begin
			if v2_verificar = true then 
				insert into aca_clase (id_materia,id_horarios,id_aula,id_personal, usu_creado,fec_creado,estado) 
				VALUES (v2_id_materia,v2_id_horario,v2_id_aula,v2_id_persona,v_usuario,now(),'activo');
			else
				if v2_aula is null then
					mensaje:='El docente asignado ya existe en otro registro en este horario';
					success:=false;
				else
					mensaje:='El aula asignada ya existe en otro registro en este horario';
					success:=false;
				end if;
			end if;
		exception
			when others then
			raise exception 'error al registrar y mostrar los mensajes finales:%',sqlerrm;
			mensaje:= 'error al registrar y mostrar los mensajes finales:%'||sqlerrm;
			success:=false;
		end;
	exception
		when others then 
		mensaje := 'Error: '||sqlerrm;
		success := false;
	return;
	END;
$$;


ALTER FUNCTION public.fn_agregar_clases(v_usuario text, valores_clase json, OUT mensaje text, OUT success boolean) OWNER TO postgres;

--
-- TOC entry 276 (class 1255 OID 17084)
-- Name: fn_agregar_estudiante(text, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_agregar_estudiante(v_usuario text, estudiante json, OUT mensaje text, OUT success boolean) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare
		v2_id_est				int;
		v2_apellido_paterno 	text; 
		v2_apellido_materno		text;
		v2_nombre				text;
		v2_fecha_nac			text;
		v2_edad					int;
		v2_celular				int;
		v2_fuente				int;
		v2_ue					text;
		v2_turno				int;
		v2_nivel				int;
		v2_grado				int;
		v2_zona					text;
		v2_calle				text;
		
		v2_t_apellido_paterno	text;
		v2_t_apellido_materno	text;
		v2_t_nombre				text;
		v2_t_actividad			text;
		v2_t_trabajo			text;
		v2_t_telefono			int;
		v2_t_celular			int;
		
		v2_f_inicio				text;
		v2_lapso				int;
		v2_cantidad				int;
		v2_materia				int;
		v2_horarios				int;
		v2_aulas				int;
	
		t2_tipo_horario			bool;
		v2_horario 				int;
		a_horario				int[];
		v2_aula					int;
		a_aula					int[];
		v2_horario_esp			text;
		v2_clase_esp 			text;
	
		v2_pago_checkbox		text;
		v2_total				float;
		v2_cuenta				float;
		v2_f_pago				text;
	
		v_id_est 				int;
		v_id_tut 				int;
		v_pago					int;
		v_inscripcion			int;
	BEGIN
		mensaje:='';
		success:=true;
	--se extraen los datos del json monto_cancelado
		begin
			--v2_id_est:=estudiante->>'id_est';
			v2_id_est:=case
				  WHEN estudiante->>'id_est' = '' THEN NULL
				  ELSE (estudiante->>'id_est')::int
				END;
			v2_apellido_paterno:=estudiante->>'apellido_paterno'; 
			v2_apellido_materno:=estudiante->>'apellido_materno';
			v2_nombre:=estudiante->>'nombre';
			v2_fecha_nac:=estudiante->>'fecha_nac';
			v2_celular:=estudiante->>'celular';
			v2_fuente:=estudiante->>'fuente';
			v2_ue:=estudiante->>'ue';
			v2_turno:=estudiante->>'turno';
			v2_nivel:=estudiante->>'nivel';
			v2_grado:=estudiante->>'grado';
			v2_zona:=estudiante->>'zona';
			v2_calle:=estudiante->>'calle';
			
			v2_t_apellido_paterno:=estudiante->>'t_apellido_paterno';
			v2_t_apellido_materno:=estudiante->>'t_apellido_materno';
			v2_t_nombre:=estudiante->>'t_nombre';
			v2_t_actividad:=estudiante->>'t_actividad';
			v2_t_trabajo:=estudiante->>'t_trabajo';
			v2_t_telefono:=estudiante->>'t_telefono';
			v2_t_celular:=estudiante->>'t_celular';
			
			t2_tipo_horario:=estudiante->>'tipo_horarios';
			v2_f_inicio:=estudiante->>'f_inicio';
			v2_lapso:=(select id_categoria  from ral_categoria rc  where nombre_categoria = 'tiempo-inscripcion' and detalle = 'mes' and estado = 'activo');
			v2_cantidad:=estudiante->>'cantidad';
			
			v2_pago_checkbox:=estudiante->>'pago_checkbox';
			v2_total:=case -- v2_monto_cancelado
				  WHEN estudiante->>'total' = '' THEN NULL
				  ELSE (estudiante->>'total')::int
				END;
			v2_cuenta:=CASE
				  WHEN estudiante->>'cuenta' = '' THEN NULL
				  ELSE (estudiante->>'cuenta')::int
				END;
			v2_f_pago:=coalesce(estudiante->>'f_pago');
		
			if t2_tipo_horario then
				v2_materia:=estudiante->>'materia2';
				a_horario:=array_agg(value)::text[] from json_array_elements_text(estudiante->'horario')as value;
				a_aula:=array_agg(value2)::text[] from json_array_elements_text(estudiante->'aula')as value2;
			else
				v2_materia:=estudiante->>'materia';
				v2_horarios:=estudiante->>'horarios';
				v2_aulas:=estudiante->>'aulas';
			end if;
			
			exception
				when others then
				raise exception 'Error al extraer la informacion del JSON: %',sqlerrm;
				mensaje:='Error al extraer la informacion del JSON: '||sqlerrm;
				success:=false;
		end;

	-- se registra al estudiante
		begin
		raise notice 'v2_id_est: %',v2_id_est;
			if v2_id_est isnull then
				-- se registra la persona estudiante
				insert into ral_persona (nom_persona,ap_pat_persona,ap_mat_persona,fec_nacimiento,celular,fec_creado,usu_creado,estado) 
								 values (v2_nombre,v2_apellido_paterno,v2_apellido_materno,v2_fecha_nac::date,v2_celular,now(),v_usuario,'activo');
				-- se recupera el id de la persona-estudiante
				v_id_est:=(select rp.id_persona from ral_persona rp where ap_pat_persona=v2_apellido_paterno and nom_persona=v2_nombre  order by fec_creado desc limit 1); 
				-- se almacena los datos e aca_estudiante 
				insert into aca_estudiante (id_persona,unid_educativa,grado,nivel,turno,zona,direccion,usu_creado,fec_creado,estado) 
								    values (v_id_est,v2_ue,v2_grado,v2_nivel,v2_turno,v2_zona,v2_calle,v_usuario,now(),'activo');
			else
				--se modifica al estudiante
				update aca_estudiante 
				set unid_educativa=v2_ue,grado=v2_grado, nivel=v2_nivel, turno=v2_turno, zona=v2_zona , direccion=v2_calle, usu_modificado=v_usuario, fec_modificado=now(), estado='activo' 
				where id_estudiante=v2_id_est;

				update ral_persona 
				set nom_persona=v2_nombre, ap_pat_persona=v2_apellido_paterno, ap_mat_persona=v2_apellido_materno, fec_nacimiento=v2_fecha_nac::date, celular=v2_celular, usu_modificado=v_usuario, fec_modificado=now(), estado='activo'
				where id_persona=(select id_persona from aca_estudiante where id_estudiante=v2_id_est);
			end if;
			
			exception
				when others then
				raise exception 'error al registrar datos del estudiante:% ',sqlerrm;
				mensaje:='error al registrar datos del estudiante:% '||sqlerrm;
				success:=false;
		end; 
		-- se guarda al tutor
		begin
			--se registra la persona-tutor
			if v2_id_est isnull then
				insert into ral_persona (nom_persona,ap_pat_persona,ap_mat_persona,celular,fec_creado,usu_creado,estado) 
								values (v2_t_nombre,v2_t_apellido_paterno,v2_t_apellido_materno,v2_t_celular,now(),v_usuario,'activo');
				--se recupera su id_persona
				v_id_tut:=(select id_persona  from ral_persona where ap_pat_persona=v2_t_apellido_paterno and nom_persona=v2_t_nombre order by fec_creado desc limit 1);
				--se registra al tutor
				insert into com_tutor (id_persona,act_tutor,trab_tutor,telefono_tutor,fec_creado,usu_creado,estado, fuente) 
							  	values (v_id_tut,v2_t_actividad,v2_t_trabajo,v2_t_telefono,now(),v_usuario,'activo',v2_fuente);
				--se agrega al tutor al registro del estudiante
				v_id_tut:=(select id_tutor from com_tutor order by fec_creado desc limit 1);
				update aca_estudiante set id_tutor=v_id_tut where id_estudiante = (select id_estudiante from aca_estudiante order by fec_creado desc limit 1);
			else
				update com_tutor 
				set act_tutor=v2_t_actividad, trab_tutor=v2_t_trabajo, telefono_tutor=v2_t_telefono, usu_modificado=v_usuario, fec_modificado=now(), estado='activo' 
				where id_tutor=(select id_tutor from aca_estudiante where id_estudiante=v2_id_est);

				update ral_persona
				set nom_persona=v2_t_nombre, ap_pat_persona=v2_t_apellido_paterno, ap_mat_persona=v2_t_apellido_materno, celular=v2_t_celular,usu_modificado=v_usuario, fec_modificado=now(), estado='activo' 
				where id_persona=(select ct.id_persona from com_tutor ct, aca_estudiante ae where ct.id_tutor=ae.id_tutor and ae.id_estudiante=v2_id_est);
			end if;
		
			exception
				when others then
				raise exception 'error al registrar los datos del tutor:% ',sqlerrm;
				mensaje:='error al registrar los datos del tutor:% '||sqlerrm;
				success:=false;
		end;
		--se registra la inscripcion
		begin
		-- se pregunta que tipo de inscripcion es
			v_id_est:=(select id_estudiante  from aca_estudiante order by fec_creado desc limit 1);
			 if t2_tipo_horario then
				 -- 2 se crea un nuevo horario y se da la inscripcion
			 	for i in 1 .. GREATEST(array_length(a_horario,1), array_length(a_aula,1))
			 	loop
			 		if i<=array_length(a_horario,1) then
			 			v2_horario:=(a_horario[i])::integer;
			 		end if;
			 		if i<=array_length(a_aula,1) then
			 			v2_aula:=(a_aula[i])::integer;
			 		end if;
			 		if i>1 then
			 			v2_horario_esp:=v2_horario_esp || '||';
				 		v2_clase_esp:=v2_clase_esp||'||';
			 		end if;
			 		v2_horario_esp:=concat(v2_horario_esp,v2_horario);
				 	v2_clase_esp:=concat(v2_clase_esp,
					 	(select distinct ac.id_clase 
						from aca_clase ac,com_precios cp,(select a.id_horarios, a.id_conf_horarios,a.estado 
										     from aca_horarios a 	
										     where a.id_horarios = v2_horario) as ah
						where ac.estado = 'activo'
						and ah.estado = 'activo'
						and ac.id_horarios = ah.id_conf_horarios
						and ac.id_materia = cp.id_materia
						and cp.id_precios = v2_materia
						and ac.id_aula = v2_aula));
					raise notice 'v2_clase_esp:%',v2_clase_esp;
			 	end loop;
			 		--se registra la inscripcion especial
					insert into aca_inscripcion (id_estudiante,lapso,cantidad,fec_inscripcion,fec_inicio,id_clase_esp ,id_horario_esp ,estado, id_precios) 
								  		 values (v_id_est,v2_lapso,v2_cantidad,now(),v2_f_inicio::date,v2_clase_esp,v2_horario_esp,'activo',v2_materia);
			 else
			 	-- 1 se guarda la inscricion con normalidad
			 	insert into aca_inscripcion (id_estudiante,id_clase,lapso,cantidad,fec_inscripcion,fec_inicio,estado,id_precios) 
			 						values (v_id_est,
			 								(select ac.id_clase 
			 									from aca_clase ac 
			 									where ac.estado = 'activo' 
			 									and ac.id_materia=(select id_materia from com_precios where id_precios=v2_materia and estado= 'activo')
			 									and ac.id_horarios = v2_horarios
			 									and ac.id_aula = v2_aulas),
			 								v2_lapso,v2_cantidad,now(),v2_f_inicio::date,'activo',v2_materia);
			 end if;
			exception
				when others then
				raise exception 'error al registrar la inscripcion: %',sqlerrm;
				mensaje:= 'error al registrar la inscripcion: %'||sqlerrm;
				success:=false;
		end;
		--se guarda el tipo de pago 
		begin
			--se verifica el tipo de pago
		
			case when v2_pago_checkbox = 'contado' then
				v_id_tut:=(select id_tutor from com_tutor order by fec_creado desc limit 1);
				insert into com_pago (id_tutor, usu_creado,fec_creado,estado) 
					values (v_id_tut,v_usuario,now(),'activo');
				v_pago:=(select id_pago from com_pago order by fec_creado desc limit 1);
				v_inscripcion := (select id_inscripcion from aca_inscripcion order by fec_inscripcion desc limit 1);
				insert into com_detalle_pago (id_pago, id_inscripcion,monto_cancelado,usu_creado,fec_creado,estado) 
					values (v_pago,v_inscripcion, v2_total, v_usuario, now(), 'cancelado');
			when v2_pago_checkbox = 'deuda' then
				v_id_tut:=(select id_tutor from com_tutor order by fec_creado desc limit 1);
				insert into com_pago (id_tutor, usu_creado,fec_creado,estado) 
					values (v_id_tut,v_usuario,now(),'activo');
				v_pago:=(select id_pago from com_pago order by fec_creado desc limit 1);
				v_inscripcion := (select id_inscripcion from aca_inscripcion order by fec_inscripcion limit 1);
				insert into com_detalle_pago (id_pago, id_inscripcion,monto_deuda,usu_creado,fec_creado,estado, fec_pago) 
					values (v_pago,v_inscripcion,v2_total, v_usuario, now(), 'deuda', v2_f_pago::date);
			when v2_pago_checkbox = 'plazos' then
				v_id_tut:=(select id_tutor from com_tutor order by fec_creado desc limit 1);
				insert into com_pago (id_tutor, usu_creado,fec_creado,estado) 
					values (v_id_tut,v_usuario,now(),'activo');
				v_pago:=(select id_pago from com_pago order by fec_creado desc limit 1);
				v_inscripcion := (select id_inscripcion from aca_inscripcion order by fec_inscripcion limit 1);
				insert into com_detalle_pago (id_pago, id_inscripcion,monto_cancelado,monto_deuda,usu_creado,fec_creado,estado,fec_pago) 
					values (v_pago,v_inscripcion, v2_cuenta,(v2_total-v2_cuenta), v_usuario, now(), 'plazos', v2_f_pago::date);
			else
				--nada
			end case;
		--select * from aas;
			exception 
				when others then
				raise notice 'error al registrar el pago: %',sqlerrm;
				mensaje:='error al registrar el pago: %'||sqlerrm;
			success:=false;
		end;
		exception
			when others then 
			mensaje := 'Error: '||sqlerrm;
			success := false;
		return;
	END;
$$;


ALTER FUNCTION public.fn_agregar_estudiante(v_usuario text, estudiante json, OUT mensaje text, OUT success boolean) OWNER TO postgres;

--
-- TOC entry 271 (class 1255 OID 17005)
-- Name: fn_agregar_horarios(text, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_agregar_horarios(v_usuario text, valores_hoararios json, OUT success boolean, OUT mensaje text) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare 
	a_dias			text[];
	v2_dias			integer;
	a_hora_inicio	text[];
	v2_hora_inicio	text;
	a_hora_fin		text[];
	v2_hora_fin		text;
	v2_thorario		text;
	v2_id_conf_horarios integer;
	begin
		mensaje:='';
		success:=true;
		--se extraen los valores json
		a_dias:=array_agg(value)::text[]from json_array_elements_text(valores_hoararios->'dias')as value;
		a_hora_inicio:=array_agg(value2)::text[]from json_array_elements_text(valores_hoararios->'hora_inicio')as value2;
		a_hora_fin:=array_agg(value3)::text[] from json_array_elements_text(valores_hoararios->'hora_fin')as value3;
		v2_thorario:=valores_hoararios->>'t_horario';
		begin
			exception
			when others then
			raise exception 'error al extraer datos del json :%',sqlerrm;
			mensaje:='error al extraer datos del json : '||sqlerrm;
			success:=false;
		end;
		--se crea un registro de configuracion de horarios
		begin
			insert into adm_conf_horarios (codigo_conf,usu_creado,fec_creado,estado) 
			values (v2_thorario,v_usuario,now(),'activo');
			v2_id_conf_horarios:=(select ach.id_conf_horarios 
							        from adm_conf_horarios ach 
								   where estado='activo'
								order by fec_creado desc
								   limit 1);
			exception
			when others then
			raise exception 'error al crear la configuracion de horarios :%',sqlerrm;
			mensaje:='error al crear la configuracion de horarios :'||sqlerrm;
			success:=false;
		end;
		--se crean los registros de los horarios de manera individual
		begin
		for  i in 1 .. GREATEST(array_length(a_dias,1),array_length(a_hora_inicio,1),array_length(a_hora_fin,1))
			loop
				if i <= array_length(a_dias,1)then
					v2_dias:=(a_dias[i])::text;
				else
					v2_dias:=null;
				end if;
				if i<= array_length(a_hora_inicio,1)then
					v2_hora_inicio:=(a_hora_inicio[i])::text;
				else
					v2_hora_inicio:=null;
				end if;
				if i<= array_length(a_hora_fin,1)then
					v2_hora_fin:=(a_hora_fin[i])::text;
				else
					v2_hora_fin:=null;
				end if;
				
					insert into aca_horarios (dias,id_conf_horarios,horarios,usu_creado,fec_creado,estado) 
					values (v2_dias,v2_id_conf_horarios,concat(v2_hora_inicio,' || ',v2_hora_fin),v_usuario,now(),'activo');

			end loop;
			exception
			when others then
			raise exception 'error al crear los horarios :%',sqlerrm;
			mensaje:= 'error al crear los horarios :'||sqlerrm;
			success:=false;
		end;
		exception
			when others then 
			mensaje := 'Error: '||sqlerrm;
			success := false;
		return;
	END;
$$;


ALTER FUNCTION public.fn_agregar_horarios(v_usuario text, valores_hoararios json, OUT success boolean, OUT mensaje text) OWNER TO postgres;

--
-- TOC entry 279 (class 1255 OID 17474)
-- Name: fn_agregar_materia(integer, text, text, numeric, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_agregar_materia(v_curso integer, v_nombre_m text, v_detalle_m text, v_precio numeric, v_usuario text, OUT mensaje text, OUT success boolean) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare
		
	BEGIN
		mensaje:='';
		success:=true;
		begin
			--buscar materia si existe, si no crearla
			if (select am.nombre_materia from aca_materia am where nombre_materia ilike v_nombre_m) isnull then
				insert into aca_materia(tipo_materia, nombre_materia, usu_creado,fec_creado,estado) 
				values (v_curso,v_nombre_m,v_usuario,now(),'activo');
			else 
				if (select am.nombre_materia from aca_materia am where nombre_materia ilike v_nombre_m and estado = 'activo') isnull then
				update aca_materia set estado='activo' where nombre_materia ilike v_nombre_m and estado = 'inactivo';
				end if;
			end if;
			exception
			when others then
				raise exception 'error al actualizar/crear la materia: %',sqlerrm;
				mensaje:='error al actualizar/crear la materia: '||sqlerrm;
				success:=false;
		end;
		begin
			--agregar el precio
			insert into com_precios (id_materia, precio, detalle, usu_creado, fec_creado, estado) 
			values ((select id_materia from aca_materia where nombre_materia ilike v_nombre_m and estado='activo'),v_precio,v_detalle_m,v_usuario,now(),'activo');
			exception
			when others then
				raise exception 'error al agregar el precio del curso: %',sqlerrm;
				mensaje:='error al agregar el precio del curso: '||sqlerrm;
				success:=false;
		end;
		exception
			when others then
				raise exception 'error: %',sqlerrm;
				mensaje:='error en la base de datos: '||sqlerrm;
				success:=false;
		return;
	END;
$$;


ALTER FUNCTION public.fn_agregar_materia(v_curso integer, v_nombre_m text, v_detalle_m text, v_precio numeric, v_usuario text, OUT mensaje text, OUT success boolean) OWNER TO postgres;

--
-- TOC entry 268 (class 1255 OID 16948)
-- Name: fn_agregar_ubicacion(character varying, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_agregar_ubicacion(v1_usuario character varying, j_envio json, OUT success boolean, OUT mensaje text) RETURNS record
    LANGUAGE plpgsql
    AS $$
declare 
	v2_zona			text;
	v2_direccion	text;
	v2_detalle		text;
	v2_descripcion	text;
	a_nombre_aula	text[];
	v2_nombre_aula	text;
	a_detalle_aula	text[];
	v2_detalle_aula	integer;
	v2_id_ubicacion	integer;
	
begin
	begin
		success:=true;
		mensaje:='exito';
	--ponemos los valores del json en las variables
	v2_zona := j_envio ->> 'zona';
	v2_direccion := j_envio ->> 'direccion';
	v2_detalle := j_envio ->> 'detalle';
	v2_descripcion := j_envio ->> 'descripcion';
	a_nombre_aula := array_agg(value)::text[] FROM json_array_elements_text(j_envio -> 'nombre_aula') AS value;
	a_detalle_aula := array_agg(value2)::text[] from json_array_elements_text(j_envio -> 'detalle_aula')as value2;

	exception 
	when others then 
		raise exception 'error al extraer datos del json : %',sqlerrm;
		mensaje := 'error al extraer datos del json :'||sqlerrm;
		success := false;
	end;
	begin
		
	--insertamos los datos de la ubicacion
	INSERT INTO adm_ubicacion (zona, direccion, detalle, descripcion,usu_creado,fec_creado , estado) 
	VALUES (v2_zona,v2_direccion,v2_descripcion,v2_detalle,v1_usuario,now(),'activo');
	v2_id_ubicacion:=(select id_ubicacion from adm_ubicacion where estado = 'activo' order by fec_creado desc limit 1);
	exception
	when others then 
		raise exception 'error en el registro de la ubicacion : %',sqlerrm;
		mensaje:='error en el registro de la ubicacion :' || sqlerrm;
		success := false;
	end;
	begin

	--iniciamos eun for para sustraer la informacion de las aulas
	for	i in 1 .. GREATEST(array_length(a_nombre_aula,1),array_length(a_detalle_aula,1))
		loop
			--primer if para agarrar el valor del nombre de aula
			if i <= array_length(a_nombre_aula,1) then
				v2_nombre_aula:= (a_nombre_aula [i])::text;
			else
				v2_nombre_aula := null;
			end if;
			--segundo if para el detalle del aula
			if i <= array_length(a_detalle_aula,1) then
				v2_detalle_aula:=(coalesce(nullif(a_detalle_aula[i],''),null));
			else
				v2_detalle_aula:=0;
			end if;
		
			begin
				v2_nombre_aula:=coalesce(nullif(v2_nombre_aula,''),null);
				if v2_nombre_aula isnull then
				else
					INSERT INTO aca_aula (id_ubicacion,cantidad_estudiantes,nombre_aula,usu_creado ,fec_creado ,estado) 
					VALUES (v2_id_ubicacion,v2_detalle_aula,v2_nombre_aula,v1_usuario,now(),'activo');
				end if;
			exception
				when others then 
					RAISE NOTICE 'Error al insertar en aca_aula: %', SQLERRM;
                	mensaje := 'Error al insertar en aca_aula: ' || SQLERRM;
                	success := false;
            end;
		end loop;
	exception
		when others then
			raise exception 'error al registrar los cursos :%',sqlerrm;
			mensaje := 'error al registrar los cursos : ' || sqlerrm;
			success := false;
	end;

	exception
		when others then 
		mensaje := 'Error: '||sqlerrm;
		success := false;
	return;
	END;
$$;


ALTER FUNCTION public.fn_agregar_ubicacion(v1_usuario character varying, j_envio json, OUT success boolean, OUT mensaje text) OWNER TO postgres;

--
-- TOC entry 277 (class 1255 OID 17248)
-- Name: fn_asistencia_estudiantes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_asistencia_estudiantes() RETURNS json
    LANGUAGE plpgsql
    AS $$
	declare
--	(id_estudiantes character varying, id_clase int, estudiante character varying)
		json_respuesta		jsonb;
		json_alumnos_clase	jsonb;
		a_clases 		int[]; --para contar las clases actuales
		v2_clases		int;
		v2_nombre_clase text;
	begin
		begin
		--se guardan los id de las clases que se esten pasando en la hora actual del sistema
			
		 	a_clases:=(select array_agg(ac.id_clase)
				from aca_horarios ah,ral_categoria rc,adm_conf_horarios ach, aca_clase ac, (select ah2.id_horarios,
					ah2.id_conf_horarios,
					ah2.dias as dias,
					split_part(ah2.horarios,' || ',1) as hora_inicio, 
					split_part(ah2.horarios,' || ',2) as hora_fin
					from aca_horarios ah2
					where ah2.estado='activo')as t1,
				(select case 
					when extract (dow from now()) = 1 then 'lunes'
					when extract (dow from now()) = 2 then 'martes'
					when extract (dow from now()) = 3 then 'miercoles'
					when extract (dow from now()) = 4 then 'jueves'
					when extract (dow from now()) = 5 then 'viernes'
					when extract (dow from now()) = 6 then 'sabado'
					when extract (dow from now()) = 0 then 'domingo'
				end as dia
				)as t2
				where t1.id_horarios = ah.id_horarios 
				and ach.id_conf_horarios = ah.id_conf_horarios 
				and ac.id_horarios = ach.id_conf_horarios 
				and rc.id_categoria = ah.dias 
				and (hora_inicio::time<=localtime and hora_fin::time >= localtime)
				and rc.detalle = t2.dia
				and rc.estado = 'activo'
				and ah.estado = 'activo'
				and ach.estado = 'activo'
				and ac.estado = 'activo'
				);
			exception
			when others then 
			raise exception 'error al recoger las clases actuales:%',sqlerrm;
		end;
		begin
			--json_respuesta=json_build_object('Error',null)::jsonb;
			json_respuesta:='[]'::jsonb;
			if a_clases notnull then
			-- se corre un loop para seleccionar a los estudiantes de las clases encontradas
				for i in 1 .. GREATEST(array_length(a_clases,1))
					loop
						v2_clases:=a_clases[i]::int;
						--se busca el nombre de la clase (nombre de la materia, nombre del aula y direccion del aula)
						v2_nombre_clase:=(select concat(am.nombre_materia , ' - ' , aa.nombre_aula ,' - ', au.direccion )
						from aca_clase ac, aca_materia am, aca_aula aa, adm_ubicacion au
						where am.id_materia = ac.id_materia 
						and aa.id_aula = ac.id_aula 
						and aa.id_ubicacion = au.id_ubicacion 
						and ac.id_clase = v2_clases
						and aa.estado = 'activo'
						and au.estado = 'activo'
						and ac.estado = 'activo'
						and am.estado = 'activo');

						-- se busca a los estudiantes en la clase con horarios regulares
						json_alumnos_clase:=(select json_build_object('id_clase',v2_clases,'nombre_clase',v2_nombre_clase,'alumnos_clase',json_agg(t1))
						FROM (
						select ai.id_inscripcion, ae.id_estudiante, concat(rp.nom_persona,' ', rp.ap_pat_persona,' ',rp.ap_mat_persona) as estudiante
						from aca_estudiante ae, ral_persona rp, aca_inscripcion ai 
						where rp.id_persona = ae.id_persona
						and ai.id_estudiante = ae.id_estudiante 
						and ae.estado='activo'
						and rp.estado = 'activo'
						and ai.estado = 'activo'
						and ai.id_clase = v2_clases
						and ai.fec_inicio <= now()
						and ai.fec_inicio <= (ai.fec_inicio::date + concat((ai.cantidad ),' month')::interval)
						
						union all
						--se busca a los estudiantes en la clase con horarios irregulares
						select ai.id_inscripcion, ae.id_estudiante, concat(rp.nom_persona,' ', rp.ap_pat_persona,' ',rp.ap_mat_persona) as estudiante 
						from aca_inscripcion ai, aca_estudiante ae, ral_persona rp 
						where ai.id_estudiante=ae.id_estudiante 
						and ae.id_persona = rp.id_persona 
						and ai.estado='activo'
						and ae.estado ='activo'
						and rp.estado ='activo'
						and v2_clases::text = any(string_to_array(ai.id_clase_esp, '||'))
						and ai.fec_inicio <= now()
						and ai.fec_inicio <= (ai.fec_inicio::date + concat((ai.cantidad ),' month')::interval)
						
						union all
						--se busca estudiantes que tengasn un permiso programado para esta clase
						select arh.id_inscripcion, ae.id_estudiante, concat(rp.nom_persona,' ', rp.ap_pat_persona,' ',rp.ap_mat_persona) as estudiante
						from aca_reprogramacion_horario arh, aca_estudiante ae, ral_persona rp, aca_inscripcion ai 
						where arh.id_inscripcion = ai.id_inscripcion 
						and ai.id_estudiante =ae.id_estudiante 
						and ae.id_persona = rp.id_persona 
						and arh.estado= 'activo'
						and ae.estado = 'activo'
						and rp.estado = 'activo'
						and ai.estado = 'activo'
						and arh.fec_reemplazo = now()::date
						and arh.id_clase = v2_clases
						
						)as t1 );
						json_respuesta := (json_respuesta || json_alumnos_clase);
					end loop;
			else 
				json_respuesta=json_build_object('Error','No existen clases en estos momentos');
			end if;
				exception
					when others then 
					raise exception 'error al recoger los estudiantes de las clases:%',sqlerrm;
			end;
		return json_respuesta;
	END;
$$;


ALTER FUNCTION public.fn_asistencia_estudiantes() OWNER TO postgres;

--
-- TOC entry 278 (class 1255 OID 17298)
-- Name: fn_crear_reprogramacion(text, text, text, text, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_crear_reprogramacion(v_nombre text, v_f_permiso text, v_f_reemplazo text, v_usuario text, v_id_clase integer, id_mod integer, OUT mensaje text, OUT success boolean) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare
	v2_cont1 	bool;
	v2_cont2	int;
	v2_cont3 	bool;
	v2_id_inscripcion int;
	begin
		v2_id_inscripcion:=(select id_inscripcion 
				from aca_inscripcion ai, aca_estudiante ae , ral_persona rp 
				where ai.id_estudiante = ae.id_estudiante 
				and ae.id_persona = rp.id_persona 
				and ai.estado='activo'
				and ae.estado='activo'
				and rp.estado='activo'
				and concat(nom_persona,' ',ap_pat_persona,' ',ap_mat_persona) ilike v_nombre);
		mensaje:='';
		success:=true;
		--comprobar el nombre y la inscripcion
		begin
			v2_cont1:=(select ae.id_estudiante::bool 
					from aca_estudiante ae , aca_inscripcion ai, ral_persona rp
					where ae.id_estudiante =ai.id_estudiante
					and rp.id_persona = ae.id_persona 
					and ae.estado='activo' 
					and ai.estado ='activo'
					and rp.estado = 'activo'
					and ai.id_inscripcion = v2_id_inscripcion 
					and concat(nom_persona,' ',ap_pat_persona,' ',ap_mat_persona) ilike v_nombre);
			exception
			when others then
				raise exception 'error al buscar la inscripcion: %',sqlerrm;
				mensaje:='error al buscar la inscripcion: '||sqlerrm;
				success:=false;
		end;
		--comprobar la asitencia del dia si este ya pasó, si no existe registro entonces 0
		begin
			v2_cont2:=(select coalesce(
						(select distinct on (aa.id_estudiante) aa.valor_asistencia 
						from aca_asistencia aa, aca_inscripcion ai 
						where aa.fec_asistencia::date=v_f_permiso::date
						and aa.id_estudiante = ai.id_estudiante 
						and ai.id_inscripcion  = v2_id_inscripcion
						group by aa.id_estudiante, aa.valor_asistencia , aa.fec_asistencia 
						order by aa.id_estudiante, aa.valor_asistencia , aa.fec_asistencia desc), 0)
					 );
			exception
			when others then
				raise exception 'error al buscar la asistencia: %',sqlerrm;
				mensaje:='error al buscar la asistencia: '||sqlerrm;
				success:=false;
		end;
		--comprobar si la fecha de permiso coincide con la clase
		begin
			
			v2_cont3:=(select t1.dias::bool from 
					(select ah.dias
						from aca_horarios ah, aca_inscripcion ai , adm_conf_horarios ach , aca_clase ac 
						where ai.id_clase = ac.id_clase 
						and ac.id_horarios = ach.id_conf_horarios 
						and ach.id_conf_horarios = ah.id_conf_horarios 
						and ah.estado ='activo'
						and ai.estado ='activo'
						and ach.estado ='activo'
						and ac.estado ='activo'
						and ai.id_inscripcion = v2_id_inscripcion) as t1,
					(select case
						when extract (dow from v_f_permiso::date) = 1 then (select id_categoria from ral_categoria where detalle='lunes')
						when extract (dow from v_f_permiso::date) = 2 then (select id_categoria from ral_categoria where detalle='martes')
						when extract (dow from v_f_permiso::date) = 3 then (select id_categoria from ral_categoria where detalle='miercoles')
						when extract (dow from v_f_permiso::date) = 4 then (select id_categoria from ral_categoria where detalle='jueves')
						when extract (dow from v_f_permiso::date) = 5 then (select id_categoria from ral_categoria where detalle='sabado')
						when extract (dow from v_f_permiso::date) = 6 then (select id_categoria from ral_categoria where detalle='domingo')
						end as dia) as t2
					where t1.dias = t2.dia);
			exception
			when others then
				raise exception 'error comparar las fechas: %',sqlerrm;
				mensaje:='error al comparar las fechas: '||sqlerrm;
				success:=false;
		end;
		--comprobar todos los requisitos anteriores y proceder al registro
		begin
			if v2_cont1 = true then
				if v2_cont3 = true then
					if v2_cont2 = 0 or v2_cont2=(select id_categoria from ral_categoria where detalle = 'falta' and nombre_categoria='asistencia' and estado='activo') then
						if id_mod = 0 then
							insert into aca_reprogramacion_horario (id_inscripcion,fec_reprogramacion,fec_reemplazo,usu_creado,fec_creado,estado,id_clase) 
							values (v2_id_inscripcion, v_f_permiso::date, v_f_reemplazo::date, v_usuario, now(), 'activo', v_id_clase);
						elseif id_mod <> 0 then
							update aca_reprogramacion_horario set 
								id_inscripcion=v2_id_inscripcion,
								fec_reprogramacion=v_f_permiso::date,
								fec_reemplazo = v_f_reemplazo::date,
								usu_modificado=v_usuario,
								fec_creado =now(),
								id_clase=v_id_clase
								where id_reprogramacion_horario = id_mod;
						end if;
					else 
						mensaje:='EL estudiante asistió esa fecha';
						success:=false;
					end if;
				else
					mensaje:='Día de permiso no valido';
					success:=false;
				end if;
			else
				mensaje:='Nombre del estudiante no enontrado, error en el registro';
				success:=false;
			end if;
		exception
			when others then
				raise exception 'error En el registro del permiso: %',sqlerrm;
				mensaje:='error En el registro del permiso: '||sqlerrm;
				success:=false;
		end;
	return;
	END;
$$;


ALTER FUNCTION public.fn_crear_reprogramacion(v_nombre text, v_f_permiso text, v_f_reemplazo text, v_usuario text, v_id_clase integer, id_mod integer, OUT mensaje text, OUT success boolean) OWNER TO postgres;

--
-- TOC entry 281 (class 1255 OID 17574)
-- Name: fn_inicio(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_inicio(INOUT fecha_param text, OUT conteo1 text, OUT conteo2 text, OUT conteo3 text) RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
    fecha_actual TEXT;
BEGIN
    -- Obtener la fecha actual
    fecha_actual := TO_CHAR(NOW(), 'YYYY-MM-DD');

    -- Verificar si el texto de la fecha es la fecha actual
    IF fecha_param = fecha_actual THEN
        -- La fecha ya es la actual, retornar la fecha y valores vacíos para los otros parámetros
        conteo1 := '';
        conteo2 := '';
       	conteo3 := '';
    ELSE
        -- La fecha no es la actual, actualizar la variable con la fecha actual
        fecha_param := fecha_actual;

        -- Realizar la consulta para buscar una serie de resultados y actualizarlos
        --UPDATE tu_tabla
        --SET fecha = fecha_actual
        --WHERE -- Condiciones de tu consulta;

        -- Retornar la fecha actualizada y otros dos parámetros
        conteo1 := 'valor1';
        conteo2 := 'valor2';
       	conteo3 := 'valor2';
    END IF;
   return;
END;
$$;


ALTER FUNCTION public.fn_inicio(INOUT fecha_param text, OUT conteo1 text, OUT conteo2 text, OUT conteo3 text) OWNER TO postgres;

--
-- TOC entry 275 (class 1255 OID 17579)
-- Name: fn_inicio_sesion(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_inicio_sesion(OUT success boolean, OUT v2_fecha_actual text, OUT mensaje text) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare 
		a_id_inscripcion int[];
		v2_id_inscripcion int;
		v2_id_estudiante int;
		a_id_rep_horarios int[];
		v2_rep_horarios int;
	BEGIN
		--se cambia la fecha a la corecta
		mensaje:='';
		success:=true;
		v2_fecha_actual:=current_date;
		begin
			update ral_conf 
			set detalle=now()::date
			where id_categoria=(select id_categoria 
							from ral_categoria 
							where tipo='configuracion'
							and nombre_categoria='fecha de configuracion'
							and estado='activo');
			exception 
				when others then
				raise exception 'error en la actualizacion de la fecha: %',sqlerrm;
				mensaje:='error en la actualizacion de la fecha: '||sqlerrm;
				success:=false;
		end;
		--se busca estudiantes que tengan inscripcion caducada e inactivos
		begin
			a_id_inscripcion:=array(select ai.id_inscripcion
			from aca_inscripcion ai 
			where current_date>=(fec_inicio+interval '1 month' * ai.cantidad + interval '1 day')::date
			and ai.estado='activo');
			if array_length(a_id_inscripcion,1) is not null then
		--se actualiza
				for i in 1 .. GREATEST(array_length(a_id_inscripcion,1))
					loop
						if i <= array_length(a_id_inscripcion,1)then
							v2_id_estudiante:=(select id_estudiante 
											from aca_inscripcion 
											where id_inscripcion = a_id_inscripcion[i]);
							update aca_inscripcion ai
							set estado='inactivo'
							where ai.id_inscripcion=a_id_inscripcion[i]
							and estado='activo';
							raise notice 'pasa';
							--se actualiza el estado del estudiante
							if (select id_inscripcion 
								from aca_inscripcion 
								where id_estudiante=v2_id_estudiante 
								and estado='activo') isnull then
								update aca_estudiante 
								set estado='inactivo'
								where id_estudiante=v2_id_estudiante;
							end if;
						end if;
					end loop;
				end if;
			exception 
				when others then
				raise exception 'error en la actualizacion de la estudiantes: %',sqlerrm;
				mensaje:='error en la actualizacion de estudiantes: '||sqlerrm;
				success:=false;
		end;
	--se busca los permisos caducados y sin registrar
		begin
			a_id_rep_horarios:=array(select arh.id_reprogramacion_horario
			from aca_reprogramacion_horario arh 
			where current_date >= fec_reemplazo::date
			and estado ='activo');
		--se actualiza a los encontrados como 'inasistencia'
			if array_length(a_id_rep_horarios,1) is not null then
				for i in 1 .. GREATEST(array_length(a_id_rep_horarios,1))
					loop
						if i <=array_length(a_id_rep_horarios,1)then
							update aca_reprogramacion_horario arh
							set estado='inactivo'
							where arh.id_reprogramacion_horario=a_id_rep_horarios[i]
							and estado='activo';
						end if;
					end loop;
			end if;
			exception 
				when others then
				raise exception 'error en la actualizacion de la permisos: %',sqlerrm;
				mensaje:='error en la actualizacion de permisos: '||sqlerrm;
				success:=false;
		end;
		exception
			when others then 
			mensaje := 'Error: '||sqlerrm;
			success := false;
		return;
	END;
$$;


ALTER FUNCTION public.fn_inicio_sesion(OUT success boolean, OUT v2_fecha_actual text, OUT mensaje text) OWNER TO postgres;

--
-- TOC entry 280 (class 1255 OID 17564)
-- Name: fn_listar_clases(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_listar_clases() RETURNS TABLE(nro bigint, id_clase integer, direccion character varying, nombre_aula character varying, cantidad_estudiantes integer, nombre_materia text, id_horarios text, nombre text)
    LANGUAGE plpgsql
    AS $$
	BEGIN
		return query
			select row_number() over(order by au.direccion) as nro, 
			ac.id_clase,
			au.direccion, 
			aa.nombre_aula, 
			aa.cantidad_estudiantes ,
			am.nombre_materia,
			ach.id_horarios,
			ap.nombre
				from aca_clase ac,
				aca_materia am,
				(select ach.id_conf_horarios, ach.codigo_conf , (string_agg(concat(rc.detalle ,': ',replace(ah.horarios,'||','-')), ' || '))as id_horarios 
					from adm_conf_horarios ach,
					aca_horarios ah,
					ral_categoria rc 
					where ah.id_conf_horarios=ach.id_conf_horarios
					and rc.id_categoria = ah.dias 
					and rc.estado = 'activo'
					and ach.estado = 'activo'
					and ah.estado ='activo'
					group by ach.id_conf_horarios, ach.codigo_conf) as ach,
				aca_aula aa,
				(select ap.id_personal, concat(rp.nom_persona,' ',rp.ap_pat_persona ,' ',rp.ap_mat_persona) as nombre
					from adm_personal ap, ral_persona rp 
					where ap.id_persona = rp.id_persona
					and ap.estado = 'activo'
					and rp.estado = 'activo')as ap,
				adm_ubicacion au 
				where ac.id_materia = am.id_materia 
				and ac.id_horarios = ach.id_conf_horarios 
				and ac.id_aula = aa.id_aula 
				and ac.id_personal = ap.id_personal 
				and au.id_ubicacion = aa.id_ubicacion 
				and ac.estado ='activo'
				and am.estado='activo'
				and aa.estado = 'activo'
				and au.estado ='activo'
				order by direccion, ac.fec_creado;
	END;
$$;


ALTER FUNCTION public.fn_listar_clases() OWNER TO postgres;

--
-- TOC entry 269 (class 1255 OID 16996)
-- Name: fn_listar_horarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_listar_horarios() RETURNS TABLE(nro bigint, id_conf_horarios integer, codigo_conf character varying, id_horarios text, dias text, horarios text, estado character varying)
    LANGUAGE plpgsql
    AS $$
	BEGIN
	return query
		select row_number () over () as nro,
		       ach.id_conf_horarios,
		       ach.codigo_conf,
		       (string_agg(h.id_horarios::text,','))as id_horarios,
		       (string_agg(h.dias,','))as dias,
		       (string_agg(h.horarios,','))as horarios,
		       ach.codigo_conf 
		       from adm_conf_horarios ach,
	                (select ah.id_horarios,
			                ah.id_conf_horarios,
		                    (select rc.detalle 
		                       from ral_categoria rc 
		                      where rc.id_categoria=ah.dias)as dias,
		                    replace(ah.horarios ,' || ' , ' - ')as horarios
		              from aca_horarios ah 
		             where ah.estado = 'activo')as h
		       where ach.id_conf_horarios = h.id_conf_horarios
		       and ach.estado = 'activo'
		       group by ach.id_conf_horarios, ach.codigo_conf, ach.estado;
	END;
$$;


ALTER FUNCTION public.fn_listar_horarios() OWNER TO postgres;

--
-- TOC entry 267 (class 1255 OID 16958)
-- Name: fn_listar_ubicacion(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_listar_ubicacion() RETURNS TABLE(nro bigint, id_ubicacion integer, zona character varying, direccion character varying, detalle text, descripcion text, nombre_aula text, detalle_aula text)
    LANGUAGE plpgsql
    AS $$
declare 
	BEGIN
		return QUERY
		select row_number()over()as nro, * from
			(select distinct on (au.id_ubicacion)
			au.id_ubicacion,
			au.zona,
			au.direccion,
			au.detalle,
			au.descripcion,
			case when aula.nombre_aula is null
				then ''
				else aula.nombre_aula
			end as nombre_aula,
			case when aula.detalle_aula is null
				then ''
				else aula.detalle_aula
			end as detalle_aula
			from (
				select aa.id_ubicacion, 
				string_agg(aa.nombre_aula,' || ')as nombre_aula, 
				string_agg(aa.cantidad_estudiantes::text,' || ')as detalle_aula  
				from aca_aula aa
				where aa.estado = 'activo'
				group by aa.id_ubicacion
				order by aa.id_ubicacion 
			)as aula
			right join adm_ubicacion au
			on aula.id_ubicacion=au.id_ubicacion 
			where au.estado='activo' 
			order by au.id_ubicacion 
		)as tabla;
	END;
$$;


ALTER FUNCTION public.fn_listar_ubicacion() OWNER TO postgres;

--
-- TOC entry 270 (class 1255 OID 16961)
-- Name: fn_modificar_aulas(character varying, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_modificar_aulas(v_usuario character varying, json_aulas json, OUT success boolean, OUT mensaje text) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare 
	v2_id_ubicacion 	integer;
	v2_id_aula			integer;
	c_id_aula			integer[];
	v2_id_eliminado		integer;
	c_id_eliminado		integer[];
	v2_nombre_aula		text;
	c_nombre_aula		text[];
	v2_detalle_aula		integer;
	c_detalle_aula		text[];
	begin
		success:=true;
		mensaje:='';
		begin
			--se recuperan los valores del json
			v2_id_ubicacion:=json_aulas->>'id_ubicacion';
		
			--SELECT array_agg(value::integer) INTO c_id_aula
			--FROM json_array_elements_text(json_aulas->'id_aula') as value;

			c_id_aula := (
			    SELECT ARRAY_AGG(
			        CASE
			            WHEN value IS NULL OR value = '' THEN -1
			            ELSE value::integer
			        END
			    )
			    FROM json_array_elements_text(json_aulas->'id_aula') AS value
			)::integer[];
			c_id_eliminado:=array_agg(value2)::integer[] from json_array_elements_text(json_aulas->'ids_eliminados') as value2;
			c_nombre_aula:=array_agg(value3)::text[] from json_array_elements_text(json_aulas->'nombre_aula') as value3;
			c_detalle_aula:=array_agg(value4)::text[] from json_array_elements_text(json_aulas->'detalle_aula') as value4;
			exception 
			when others then 
			raise exception 'error al extraer variables del json : %',sqlerrm;
			success:=false;
			mensaje:='error al extraer datos del json : '||sqlerrm;
		end;
	
		begin
			--se registran las aulas eliminadas
			if array_length(c_id_eliminado,1) notnull then		
			for i in 1 .. GREATEST(array_length(c_id_eliminado,1))
				loop
					if i <= array_length(c_id_eliminado,1) then
						v2_id_eliminado:=(c_id_eliminado[i])::integer;
					else
						v2_id_eliminado:=null;
					end if;
				end loop;
			end if;
			update aca_aula set estado='inactivo', usu_modificado=v_usuario, fec_modificado=now() 
			where id_aula = v2_id_eliminado 
			and estado = 'activo';
		
			exception
			when others then
			raise exception 'error al eliminar aulas : %',sqlerrm;
			mensaje:='Error al eliminar aulas : '||sqlerrm;
			success:=false;
		end;
		begin
			--se registran las modificaciones a las aulas
			if array_length(c_id_aula,1) notnull then
			for i in 1 .. greatest(array_length(c_id_aula,1),array_length(c_nombre_aula,1),array_length(c_detalle_aula,1))
			loop
				begin
					if i <= array_length(c_id_aula,1) then
						v2_id_aula:=(c_id_aula[i])::integer;
					else
						v2_id_aula:=null;
					end if;
					--mensaje:=mensaje||mensaje;
					if i<=array_length(c_nombre_aula,1)then
						v2_nombre_aula:=(c_nombre_aula[i])::text;
					else
						v2_nombre_aula:=null;
					end if;
					if i<=array_length(c_detalle_aula,1)then
						v2_detalle_aula:=(coalesce(nullif(c_detalle_aula[i],''),null));
					else
						v2_detalle_aula:=null;
					end if;
					exception
					when others then
					raise exception 'error al extraer variables de los array : %',sqlerrm;
					mensaje:='error al extraer variables de los array : ' ||sqlerrm;
					success:=false;
				end;
				begin
					if v2_id_aula > 0 then
						--insetando modificacion
						update aca_aula set nombre_aula = v2_nombre_aula, cantidad_estudiantes =v2_detalle_aula, usu_modificado = v_usuario, fec_modificado = now()
						where id_aula = v2_id_aula
						and estado = 'activo';
					else
						--insertando nuevo registro
						insert into aca_aula (id_ubicacion,nombre_aula, cantidad_estudiantes, usu_creado, fec_creado,estado)
						values (v2_id_ubicacion,v2_nombre_aula,v2_detalle_aula,v_usuario,now(),'activo');
					end if;
				exception
					when others then
					raise exception 'error al registrar los cambios : %',sqlerrm;
					mensaje:='error al registrar los cambios : ' ||sqlerrm;
					success:=false;
				end;
			end loop;
			end if;
		end;
	exception
	when others then
	mensaje:='Error : '||sqlerrm;
	success:=false;
	return;
	END;
$$;


ALTER FUNCTION public.fn_modificar_aulas(v_usuario character varying, json_aulas json, OUT success boolean, OUT mensaje text) OWNER TO postgres;

--
-- TOC entry 273 (class 1255 OID 17011)
-- Name: fn_modificar_horarios(text, json); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_modificar_horarios(v_usuario text, valores_hoararios json, OUT success boolean, OUT mensaje text) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare 
	a_ids_eliminados text[];
	v2_ids_eliminados integer;
	a_id_horario	text[];
	v2_id_horario	integer;
	a_dias			text[];
	v2_dias			integer;
	a_hora_inicio	text[];
	v2_hora_inicio	text;
	a_hora_fin		text[];
	v2_hora_fin		text;
	v2_id_conf_horarios integer;
	begin
		mensaje:='';
		success:=true;
		--se extraen los valores json
		a_id_horario := (
			    SELECT ARRAY_AGG(
			        CASE
			            WHEN value IS NULL OR value = '' THEN -1
			            ELSE value::integer
			        END
			    )
			    FROM json_array_elements_text(valores_hoararios->'id_horario') AS value
			)::integer[];
		a_dias:=array_agg(value1)::text[]from json_array_elements_text(valores_hoararios->'dias')as value1;
		a_ids_eliminados:= array_agg(value2)::text[]from json_array_elements_text(valores_hoararios->'id_eliminado')as value2;
		a_hora_inicio:=array_agg(value3)::text[]from json_array_elements_text(valores_hoararios->'hora_inicio')as value3;
		a_hora_fin:=array_agg(value4)::text[] from json_array_elements_text(valores_hoararios->'hora_fin')as value4;
		v2_id_conf_horarios:=valores_hoararios->>'id_conf_horarios';
		begin
			exception
			when others then
			raise exception 'error al extraer datos del json :%',sqlerrm;
			mensaje:='error al extraer datos del json : '||sqlerrm;
			success:=false;
		end;
		--se registran los horarios eliminados
		begin
			if array_length(a_ids_eliminados,1) notnull then		
			for i in 1 .. GREATEST(array_length(a_ids_eliminados,1))
				loop
					if i <= array_length(a_ids_eliminados,1) then
						v2_ids_eliminados:=(a_ids_eliminados[i])::integer;				
					else
						v2_ids_eliminados:=null;
					end if;
				end loop;
			end if;
	
			update aca_horarios set estado='inactivo', usu_modificado=v_usuario, fec_modificado=now() 
			where id_horarios = v2_ids_eliminados 
			and estado = 'activo';
		
			exception
			when others then
			raise exception 'error al eliminar horario : %',sqlerrm;
			mensaje:='Error al eliminar horario : '||sqlerrm;
			success:=false;
		end;
		--se modifican los registros de los horarios de manera individual
		begin
		if array_length(a_id_horario,1) notnull then
		for  i in 1 .. GREATEST(array_length(a_id_horario,1),array_length(a_dias,1),array_length(a_hora_inicio,1),array_length(a_hora_fin,1))
			loop
				if i<= array_length(a_id_horario,1) then
					v2_id_horario:=(a_id_horario[i]);
				else
					v2_id_horario:=null;
				end if;
				if i <= array_length(a_dias,1)then
					v2_dias:=(a_dias[i])::text;
				else
					v2_dias:=null;
				end if;
				if i<= array_length(a_hora_inicio,1)then
					v2_hora_inicio:=(a_hora_inicio[i])::text;
				else
					v2_hora_inicio:=null;
				end if;
				if i<= array_length(a_hora_fin,1)then
					v2_hora_fin:=(a_hora_fin[i])::text;
				else
					v2_hora_fin:=null;
				end if;
				--raise notice 'dias:% horario inicio:% horario fin:%',v2_dias,v2_hora_inicio,v2_hora_fin;
				--raise notice 'usuario:% id_horario:% id_conf:%',v_usuario,v2_id_horario,v2_id_conf_horarios;
				begin
					if v2_id_horario > 0 then
						--insetando modificacion
						update aca_horarios set 
						dias=v2_dias,
						horarios=concat(v2_hora_inicio,' || ',v2_hora_fin),
						usu_modificado=v_usuario,
						fec_modificado=now()
						where id_horarios = v2_id_horario
						and id_conf_horarios = v2_id_conf_horarios;
					else
						--insertando nuevo registro
						insert into aca_horarios (dias,id_conf_horarios,horarios,usu_creado,fec_creado,estado) 
						values (v2_dias,v2_id_conf_horarios,concat(v2_hora_inicio,' || ',v2_hora_fin),v_usuario,now(),'activo');

					end if;
				exception
					when others then
					raise exception 'error al registrar los cambios : %',sqlerrm;
					mensaje:='error al registrar los cambios : ' ||sqlerrm;
					success:=false;
				end;
			end loop;
			end if;
		
			exception
			when others then
			raise exception 'error al modificar los horarios :%',sqlerrm;
			mensaje:= 'error al modificar los horarios :'||sqlerrm;
			success:=false;
		end;
		exception
			when others then 
			mensaje := 'Error: '||sqlerrm;
			success := false;
		return;

	END;
$$;


ALTER FUNCTION public.fn_modificar_horarios(v_usuario text, valores_hoararios json, OUT success boolean, OUT mensaje text) OWNER TO postgres;

--
-- TOC entry 272 (class 1255 OID 17250)
-- Name: fn_registrar_asistencia(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_registrar_asistencia(nombre text, valor text, OUT mensaje text, OUT success boolean) RETURNS record
    LANGUAGE plpgsql
    AS $$
	declare
		v2_id_estudiante int;
		v2_id_clase int;
		v2_valor_asistencia int;
	begin
		mensaje:='';
		success:= true;
		--se separa el id_estudiante y el id_asistencia
		v2_id_estudiante:=(split_part(nombre,'-',2));
		v2_id_clase:=(split_part(nombre,'-',3));
		--se busca el valor que tiene la asistencia en ral_categoria
		v2_valor_asistencia:=(select id_categoria from ral_categoria where detalle=valor); 
		--se registra la asistencia
		insert into aca_asistencia (id_estudiante,id_clase,valor_asistencia,fec_asistencia) 
		values (v2_id_estudiante,v2_id_clase,v2_valor_asistencia,now());
		--se busca si existe una reprogramacion con esta clase y estudiante
		--se cambia el valor del permiso segun la asistencia
		exception
			when others then
			raise exception 'error al registrar asistencia: %',sqlerrm;
			mensaje:='error al registrar asistencia:'||sqlerrm;
			success:= false;
		return;
	END;
$$;


ALTER FUNCTION public.fn_registrar_asistencia(nombre text, valor text, OUT mensaje text, OUT success boolean) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 235 (class 1259 OID 16684)
-- Name: aca_asistencia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_asistencia (
    id_asistencia integer NOT NULL,
    id_estudiante integer NOT NULL,
    id_clase integer NOT NULL,
    valor_asistencia integer NOT NULL,
    fec_asistencia timestamp without time zone NOT NULL
);


ALTER TABLE public.aca_asistencia OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16682)
-- Name: aca_asistencia_id_asistencia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_asistencia_id_asistencia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_asistencia_id_asistencia_seq OWNER TO postgres;

--
-- TOC entry 3290 (class 0 OID 0)
-- Dependencies: 234
-- Name: aca_asistencia_id_asistencia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_asistencia_id_asistencia_seq OWNED BY public.aca_asistencia.id_asistencia;


--
-- TOC entry 225 (class 1259 OID 16588)
-- Name: aca_aula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_aula (
    id_aula integer NOT NULL,
    id_ubicacion integer NOT NULL,
    nombre_aula character varying(15),
    cantidad_estudiantes integer,
    usu_creado character varying(25),
    fec_creado date,
    usu_modificado character varying(25),
    fec_modificado date,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.aca_aula OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16586)
-- Name: aca_aula_id_aula_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_aula_id_aula_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_aula_id_aula_seq OWNER TO postgres;

--
-- TOC entry 3291 (class 0 OID 0)
-- Dependencies: 224
-- Name: aca_aula_id_aula_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_aula_id_aula_seq OWNED BY public.aca_aula.id_aula;


--
-- TOC entry 229 (class 1259 OID 16617)
-- Name: aca_clase; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_clase (
    id_clase integer NOT NULL,
    id_materia integer NOT NULL,
    id_horarios integer NOT NULL,
    id_aula integer NOT NULL,
    id_personal integer NOT NULL,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.aca_clase OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16615)
-- Name: aca_clase_id_clase_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_clase_id_clase_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_clase_id_clase_seq OWNER TO postgres;

--
-- TOC entry 3292 (class 0 OID 0)
-- Dependencies: 228
-- Name: aca_clase_id_clase_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_clase_id_clase_seq OWNED BY public.aca_clase.id_clase;


--
-- TOC entry 221 (class 1259 OID 16546)
-- Name: aca_estudiante; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_estudiante (
    id_estudiante integer NOT NULL,
    id_persona integer NOT NULL,
    unid_educativa text,
    grado smallint,
    nivel integer,
    turno integer,
    zona character varying(25),
    direccion text,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL,
    id_tutor integer
);


ALTER TABLE public.aca_estudiante OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16544)
-- Name: aca_estudiante_id_estudiante_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_estudiante_id_estudiante_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_estudiante_id_estudiante_seq OWNER TO postgres;

--
-- TOC entry 3293 (class 0 OID 0)
-- Dependencies: 220
-- Name: aca_estudiante_id_estudiante_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_estudiante_id_estudiante_seq OWNED BY public.aca_estudiante.id_estudiante;


--
-- TOC entry 227 (class 1259 OID 16604)
-- Name: aca_horarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_horarios (
    id_horarios integer NOT NULL,
    dias integer NOT NULL,
    horarios text,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL,
    id_conf_horarios integer
);


ALTER TABLE public.aca_horarios OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16602)
-- Name: aca_horarios_id_horarios_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_horarios_id_horarios_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_horarios_id_horarios_seq OWNER TO postgres;

--
-- TOC entry 3294 (class 0 OID 0)
-- Dependencies: 226
-- Name: aca_horarios_id_horarios_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_horarios_id_horarios_seq OWNED BY public.aca_horarios.id_horarios;


--
-- TOC entry 233 (class 1259 OID 16656)
-- Name: aca_inscripcion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_inscripcion (
    id_inscripcion integer NOT NULL,
    id_estudiante integer NOT NULL,
    id_clase integer,
    lapso integer NOT NULL,
    cantidad integer NOT NULL,
    fec_inscripcion timestamp without time zone NOT NULL,
    id_clase_esp text,
    id_horario_esp text,
    fec_inicio timestamp without time zone,
    estado character varying(15) NOT NULL,
    id_precios integer NOT NULL
);


ALTER TABLE public.aca_inscripcion OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16654)
-- Name: aca_inscripcion_id_inscripcion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_inscripcion_id_inscripcion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_inscripcion_id_inscripcion_seq OWNER TO postgres;

--
-- TOC entry 3295 (class 0 OID 0)
-- Dependencies: 232
-- Name: aca_inscripcion_id_inscripcion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_inscripcion_id_inscripcion_seq OWNED BY public.aca_inscripcion.id_inscripcion;


--
-- TOC entry 223 (class 1259 OID 16567)
-- Name: aca_materia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_materia (
    id_materia integer NOT NULL,
    tipo_materia integer NOT NULL,
    nombre_materia text NOT NULL,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.aca_materia OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16565)
-- Name: aca_materia_id_materia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_materia_id_materia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_materia_id_materia_seq OWNER TO postgres;

--
-- TOC entry 3296 (class 0 OID 0)
-- Dependencies: 222
-- Name: aca_materia_id_materia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_materia_id_materia_seq OWNED BY public.aca_materia.id_materia;


--
-- TOC entry 243 (class 1259 OID 17261)
-- Name: aca_reprogramacion_horario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.aca_reprogramacion_horario (
    id_reprogramacion_horario integer NOT NULL,
    id_inscripcion integer NOT NULL,
    fec_reprogramacion date NOT NULL,
    fec_reemplazo date NOT NULL,
    usu_creado character varying(25) NOT NULL,
    fec_creado timestamp without time zone NOT NULL,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL,
    id_clase integer NOT NULL
);


ALTER TABLE public.aca_reprogramacion_horario OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 17259)
-- Name: aca_reprogramacion_horario_id_reprogramacion_horario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.aca_reprogramacion_horario_id_reprogramacion_horario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aca_reprogramacion_horario_id_reprogramacion_horario_seq OWNER TO postgres;

--
-- TOC entry 3297 (class 0 OID 0)
-- Dependencies: 242
-- Name: aca_reprogramacion_horario_id_reprogramacion_horario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.aca_reprogramacion_horario_id_reprogramacion_horario_seq OWNED BY public.aca_reprogramacion_horario.id_reprogramacion_horario;


--
-- TOC entry 217 (class 1259 OID 16507)
-- Name: adm_asi_personal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_asi_personal (
    id_asi_personal integer NOT NULL,
    id_personal integer NOT NULL,
    hora_entrada time without time zone NOT NULL,
    hora_salida time without time zone NOT NULL,
    dec_creado date NOT NULL
);


ALTER TABLE public.adm_asi_personal OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 16505)
-- Name: adm_asi_personal_id_asi_personal_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_asi_personal_id_asi_personal_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_asi_personal_id_asi_personal_seq OWNER TO postgres;

--
-- TOC entry 3298 (class 0 OID 0)
-- Dependencies: 216
-- Name: adm_asi_personal_id_asi_personal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_asi_personal_id_asi_personal_seq OWNED BY public.adm_asi_personal.id_asi_personal;


--
-- TOC entry 252 (class 1259 OID 17524)
-- Name: adm_cargo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_cargo (
    id_cargo integer NOT NULL,
    cargo character varying(25) NOT NULL,
    descripcion text,
    estado character varying(15) NOT NULL,
    permisos integer[]
);


ALTER TABLE public.adm_cargo OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 17522)
-- Name: adm_cargo_id_cargo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_cargo_id_cargo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_cargo_id_cargo_seq OWNER TO postgres;

--
-- TOC entry 3299 (class 0 OID 0)
-- Dependencies: 251
-- Name: adm_cargo_id_cargo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_cargo_id_cargo_seq OWNED BY public.adm_cargo.id_cargo;


--
-- TOC entry 211 (class 1259 OID 16470)
-- Name: adm_conf_horarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_conf_horarios (
    id_conf_horarios integer NOT NULL,
    codigo_conf character varying(15) NOT NULL,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.adm_conf_horarios OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 16468)
-- Name: adm_conf_horarios_id_conf_horarios_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_conf_horarios_id_conf_horarios_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_conf_horarios_id_conf_horarios_seq OWNER TO postgres;

--
-- TOC entry 3300 (class 0 OID 0)
-- Dependencies: 210
-- Name: adm_conf_horarios_id_conf_horarios_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_conf_horarios_id_conf_horarios_seq OWNED BY public.adm_conf_horarios.id_conf_horarios;


--
-- TOC entry 254 (class 1259 OID 17535)
-- Name: adm_paginas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_paginas (
    id_paginas integer NOT NULL,
    nombre_pagina text,
    estado character varying(15),
    codigo_modulo integer,
    codigo_submodulo integer
);


ALTER TABLE public.adm_paginas OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 17533)
-- Name: adm_paginas_id_paginas_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_paginas_id_paginas_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_paginas_id_paginas_seq OWNER TO postgres;

--
-- TOC entry 3301 (class 0 OID 0)
-- Dependencies: 253
-- Name: adm_paginas_id_paginas_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_paginas_id_paginas_seq OWNED BY public.adm_paginas.id_paginas;


--
-- TOC entry 219 (class 1259 OID 16520)
-- Name: adm_pago_personal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_pago_personal (
    id_pago_personal integer NOT NULL,
    id_personal integer NOT NULL
);


ALTER TABLE public.adm_pago_personal OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16518)
-- Name: adm_pago_personal_id_pago_personal_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_pago_personal_id_pago_personal_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_pago_personal_id_pago_personal_seq OWNER TO postgres;

--
-- TOC entry 3302 (class 0 OID 0)
-- Dependencies: 218
-- Name: adm_pago_personal_id_pago_personal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_pago_personal_id_pago_personal_seq OWNED BY public.adm_pago_personal.id_pago_personal;


--
-- TOC entry 215 (class 1259 OID 16489)
-- Name: adm_personal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_personal (
    id_personal integer NOT NULL,
    id_persona integer NOT NULL,
    puesto integer,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15)
);


ALTER TABLE public.adm_personal OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 16487)
-- Name: adm_personal_id_personal_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_personal_id_personal_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_personal_id_personal_seq OWNER TO postgres;

--
-- TOC entry 3303 (class 0 OID 0)
-- Dependencies: 214
-- Name: adm_personal_id_personal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_personal_id_personal_seq OWNED BY public.adm_personal.id_personal;


--
-- TOC entry 213 (class 1259 OID 16478)
-- Name: adm_ubicacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.adm_ubicacion (
    id_ubicacion integer NOT NULL,
    descripcion text,
    zona character varying(25) NOT NULL,
    direccion character varying(45) NOT NULL,
    detalle text,
    usu_creado character varying(25),
    fec_creado information_schema.time_stamp,
    usu_modificado character varying(25),
    fec_modificado date,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.adm_ubicacion OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 16476)
-- Name: adm_ubicacion_id_ubicacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.adm_ubicacion_id_ubicacion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.adm_ubicacion_id_ubicacion_seq OWNER TO postgres;

--
-- TOC entry 3304 (class 0 OID 0)
-- Dependencies: 212
-- Name: adm_ubicacion_id_ubicacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.adm_ubicacion_id_ubicacion_seq OWNED BY public.adm_ubicacion.id_ubicacion;


--
-- TOC entry 246 (class 1259 OID 17303)
-- Name: clientes; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.clientes (
    id_clientes bigint NOT NULL,
    estado character varying(255),
    f_nacimiento character varying(255),
    genero character varying(255),
    materno character varying(255),
    nombre character varying(255),
    numero_documento character varying(255),
    paterno character varying(255),
    tipo_documento character varying(255)
);


ALTER TABLE public.clientes OWNER TO ayrton2;

--
-- TOC entry 245 (class 1259 OID 17301)
-- Name: clientes_id_clientes_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.clientes_id_clientes_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.clientes_id_clientes_seq OWNER TO ayrton2;

--
-- TOC entry 3305 (class 0 OID 0)
-- Dependencies: 245
-- Name: clientes_id_clientes_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.clientes_id_clientes_seq OWNED BY public.clientes.id_clientes;


--
-- TOC entry 244 (class 1259 OID 17277)
-- Name: column_names; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.column_names (
    string_agg text
);


ALTER TABLE public.column_names OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 16746)
-- Name: com_detalle_pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.com_detalle_pago (
    id_det_pago integer NOT NULL,
    id_pago integer NOT NULL,
    id_inscripcion integer NOT NULL,
    monto_cancelado numeric(5,2),
    monto_deuda numeric(5,2),
    usu_creado character varying(15) NOT NULL,
    fec_creado timestamp without time zone NOT NULL,
    estado character varying(15) NOT NULL,
    fec_pago timestamp without time zone
);


ALTER TABLE public.com_detalle_pago OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 16744)
-- Name: com_detalle_pago_id_det_pago_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.com_detalle_pago_id_det_pago_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.com_detalle_pago_id_det_pago_seq OWNER TO postgres;

--
-- TOC entry 3306 (class 0 OID 0)
-- Dependencies: 240
-- Name: com_detalle_pago_id_det_pago_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.com_detalle_pago_id_det_pago_seq OWNED BY public.com_detalle_pago.id_det_pago;


--
-- TOC entry 239 (class 1259 OID 16733)
-- Name: com_pago; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.com_pago (
    id_pago integer NOT NULL,
    id_tutor integer NOT NULL,
    usu_creado character varying(15) NOT NULL,
    fec_creado timestamp without time zone NOT NULL,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.com_pago OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 16731)
-- Name: com_pago_id_pago_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.com_pago_id_pago_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.com_pago_id_pago_seq OWNER TO postgres;

--
-- TOC entry 3307 (class 0 OID 0)
-- Dependencies: 238
-- Name: com_pago_id_pago_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.com_pago_id_pago_seq OWNED BY public.com_pago.id_pago;


--
-- TOC entry 250 (class 1259 OID 17438)
-- Name: com_precios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.com_precios (
    id_precios integer NOT NULL,
    id_materia integer NOT NULL,
    precio numeric(5,2) NOT NULL,
    detalle text NOT NULL,
    estado character varying(15) NOT NULL,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone
);


ALTER TABLE public.com_precios OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 17436)
-- Name: com_precios_id_precios_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.com_precios_id_precios_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.com_precios_id_precios_seq OWNER TO postgres;

--
-- TOC entry 3308 (class 0 OID 0)
-- Dependencies: 249
-- Name: com_precios_id_precios_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.com_precios_id_precios_seq OWNED BY public.com_precios.id_precios;


--
-- TOC entry 231 (class 1259 OID 16645)
-- Name: com_promocion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.com_promocion (
    id_promocion integer NOT NULL,
    detalle text,
    monto_promocion numeric(3,2),
    estado character varying(15)
);


ALTER TABLE public.com_promocion OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16643)
-- Name: com_promocion_id_promocion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.com_promocion_id_promocion_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.com_promocion_id_promocion_seq OWNER TO postgres;

--
-- TOC entry 3309 (class 0 OID 0)
-- Dependencies: 230
-- Name: com_promocion_id_promocion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.com_promocion_id_promocion_seq OWNED BY public.com_promocion.id_promocion;


--
-- TOC entry 237 (class 1259 OID 16707)
-- Name: com_tutor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.com_tutor (
    id_tutor integer NOT NULL,
    id_persona integer NOT NULL,
    act_tutor character varying(45),
    trab_tutor character varying(45),
    telefono_tutor integer,
    usu_creado character varying(25),
    fec_creado timestamp without time zone,
    usu_modificado character varying(25),
    fec_modificado timestamp without time zone,
    estado character varying(15),
    fuente integer
);


ALTER TABLE public.com_tutor OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 16705)
-- Name: com_tutor_id_tutor_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.com_tutor_id_tutor_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.com_tutor_id_tutor_seq OWNER TO postgres;

--
-- TOC entry 3310 (class 0 OID 0)
-- Dependencies: 236
-- Name: com_tutor_id_tutor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.com_tutor_id_tutor_seq OWNED BY public.com_tutor.id_tutor;


--
-- TOC entry 203 (class 1259 OID 16418)
-- Name: ral_categoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ral_categoria (
    id_categoria integer NOT NULL,
    cod_categoria character varying(10) NOT NULL,
    tipo character varying(20) NOT NULL,
    nombre_categoria character varying(30) NOT NULL,
    detalle text,
    estado character varying(15) NOT NULL
);


ALTER TABLE public.ral_categoria OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 16416)
-- Name: ral_categoria_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ral_categoria_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ral_categoria_id_categoria_seq OWNER TO postgres;

--
-- TOC entry 3311 (class 0 OID 0)
-- Dependencies: 202
-- Name: ral_categoria_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ral_categoria_id_categoria_seq OWNED BY public.ral_categoria.id_categoria;


--
-- TOC entry 209 (class 1259 OID 16457)
-- Name: ral_conf; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ral_conf (
    id_conf integer NOT NULL,
    detalle character varying(35) NOT NULL,
    id_categoria integer NOT NULL
);


ALTER TABLE public.ral_conf OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 16455)
-- Name: ral_conf_id_conf_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ral_conf_id_conf_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ral_conf_id_conf_seq OWNER TO postgres;

--
-- TOC entry 3312 (class 0 OID 0)
-- Dependencies: 208
-- Name: ral_conf_id_conf_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ral_conf_id_conf_seq OWNED BY public.ral_conf.id_conf;


--
-- TOC entry 205 (class 1259 OID 16429)
-- Name: ral_persona; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ral_persona (
    id_persona integer NOT NULL,
    nom_persona character varying(30),
    ap_pat_persona character varying(30),
    ap_mat_persona character varying(30),
    fec_nacimiento date,
    celular character varying,
    fec_creado timestamp without time zone,
    usu_creado character varying(25),
    fec_modificado timestamp without time zone,
    usu_modificado character varying(25),
    estado character varying(15) NOT NULL
);


ALTER TABLE public.ral_persona OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16427)
-- Name: ral_persona_id_persona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ral_persona_id_persona_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ral_persona_id_persona_seq OWNER TO postgres;

--
-- TOC entry 3313 (class 0 OID 0)
-- Dependencies: 204
-- Name: ral_persona_id_persona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ral_persona_id_persona_seq OWNED BY public.ral_persona.id_persona;


--
-- TOC entry 207 (class 1259 OID 16442)
-- Name: ral_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ral_usuario (
    id_usuario integer NOT NULL,
    id_persona integer NOT NULL,
    usuario character varying(35) NOT NULL,
    psswd text NOT NULL,
    fec_creado date,
    usu_creado character varying(25),
    fec_modificado date,
    usu_modificado character varying(25),
    estado character varying(15) NOT NULL,
    nivel integer NOT NULL,
    salt text NOT NULL
);


ALTER TABLE public.ral_usuario OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 16440)
-- Name: ral_usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ral_usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ral_usuario_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 3314 (class 0 OID 0)
-- Dependencies: 206
-- Name: ral_usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ral_usuario_id_usuario_seq OWNED BY public.ral_usuario.id_usuario;


--
-- TOC entry 248 (class 1259 OID 17314)
-- Name: vehiculos; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.vehiculos (
    id_vehiculo bigint NOT NULL,
    "año" integer NOT NULL,
    marca character varying(255),
    modelo character varying(255),
    placa character varying(255),
    cliente_id_clientes bigint
);


ALTER TABLE public.vehiculos OWNER TO ayrton2;

--
-- TOC entry 247 (class 1259 OID 17312)
-- Name: vehiculos_id_vehiculo_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.vehiculos_id_vehiculo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vehiculos_id_vehiculo_seq OWNER TO ayrton2;

--
-- TOC entry 3315 (class 0 OID 0)
-- Dependencies: 247
-- Name: vehiculos_id_vehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.vehiculos_id_vehiculo_seq OWNED BY public.vehiculos.id_vehiculo;


--
-- TOC entry 3032 (class 2604 OID 16687)
-- Name: aca_asistencia id_asistencia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia ALTER COLUMN id_asistencia SET DEFAULT nextval('public.aca_asistencia_id_asistencia_seq'::regclass);


--
-- TOC entry 3027 (class 2604 OID 16591)
-- Name: aca_aula id_aula; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_aula ALTER COLUMN id_aula SET DEFAULT nextval('public.aca_aula_id_aula_seq'::regclass);


--
-- TOC entry 3029 (class 2604 OID 16620)
-- Name: aca_clase id_clase; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase ALTER COLUMN id_clase SET DEFAULT nextval('public.aca_clase_id_clase_seq'::regclass);


--
-- TOC entry 3025 (class 2604 OID 16549)
-- Name: aca_estudiante id_estudiante; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante ALTER COLUMN id_estudiante SET DEFAULT nextval('public.aca_estudiante_id_estudiante_seq'::regclass);


--
-- TOC entry 3028 (class 2604 OID 16607)
-- Name: aca_horarios id_horarios; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_horarios ALTER COLUMN id_horarios SET DEFAULT nextval('public.aca_horarios_id_horarios_seq'::regclass);


--
-- TOC entry 3031 (class 2604 OID 16659)
-- Name: aca_inscripcion id_inscripcion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion ALTER COLUMN id_inscripcion SET DEFAULT nextval('public.aca_inscripcion_id_inscripcion_seq'::regclass);


--
-- TOC entry 3026 (class 2604 OID 16570)
-- Name: aca_materia id_materia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_materia ALTER COLUMN id_materia SET DEFAULT nextval('public.aca_materia_id_materia_seq'::regclass);


--
-- TOC entry 3036 (class 2604 OID 17264)
-- Name: aca_reprogramacion_horario id_reprogramacion_horario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_reprogramacion_horario ALTER COLUMN id_reprogramacion_horario SET DEFAULT nextval('public.aca_reprogramacion_horario_id_reprogramacion_horario_seq'::regclass);


--
-- TOC entry 3023 (class 2604 OID 16510)
-- Name: adm_asi_personal id_asi_personal; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_asi_personal ALTER COLUMN id_asi_personal SET DEFAULT nextval('public.adm_asi_personal_id_asi_personal_seq'::regclass);


--
-- TOC entry 3040 (class 2604 OID 17527)
-- Name: adm_cargo id_cargo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_cargo ALTER COLUMN id_cargo SET DEFAULT nextval('public.adm_cargo_id_cargo_seq'::regclass);


--
-- TOC entry 3020 (class 2604 OID 16473)
-- Name: adm_conf_horarios id_conf_horarios; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_conf_horarios ALTER COLUMN id_conf_horarios SET DEFAULT nextval('public.adm_conf_horarios_id_conf_horarios_seq'::regclass);


--
-- TOC entry 3041 (class 2604 OID 17538)
-- Name: adm_paginas id_paginas; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_paginas ALTER COLUMN id_paginas SET DEFAULT nextval('public.adm_paginas_id_paginas_seq'::regclass);


--
-- TOC entry 3024 (class 2604 OID 16523)
-- Name: adm_pago_personal id_pago_personal; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_pago_personal ALTER COLUMN id_pago_personal SET DEFAULT nextval('public.adm_pago_personal_id_pago_personal_seq'::regclass);


--
-- TOC entry 3022 (class 2604 OID 16492)
-- Name: adm_personal id_personal; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_personal ALTER COLUMN id_personal SET DEFAULT nextval('public.adm_personal_id_personal_seq'::regclass);


--
-- TOC entry 3021 (class 2604 OID 16481)
-- Name: adm_ubicacion id_ubicacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_ubicacion ALTER COLUMN id_ubicacion SET DEFAULT nextval('public.adm_ubicacion_id_ubicacion_seq'::regclass);


--
-- TOC entry 3037 (class 2604 OID 17306)
-- Name: clientes id_clientes; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.clientes ALTER COLUMN id_clientes SET DEFAULT nextval('public.clientes_id_clientes_seq'::regclass);


--
-- TOC entry 3035 (class 2604 OID 16749)
-- Name: com_detalle_pago id_det_pago; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago ALTER COLUMN id_det_pago SET DEFAULT nextval('public.com_detalle_pago_id_det_pago_seq'::regclass);


--
-- TOC entry 3034 (class 2604 OID 16736)
-- Name: com_pago id_pago; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_pago ALTER COLUMN id_pago SET DEFAULT nextval('public.com_pago_id_pago_seq'::regclass);


--
-- TOC entry 3039 (class 2604 OID 17441)
-- Name: com_precios id_precios; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_precios ALTER COLUMN id_precios SET DEFAULT nextval('public.com_precios_id_precios_seq'::regclass);


--
-- TOC entry 3030 (class 2604 OID 16648)
-- Name: com_promocion id_promocion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_promocion ALTER COLUMN id_promocion SET DEFAULT nextval('public.com_promocion_id_promocion_seq'::regclass);


--
-- TOC entry 3033 (class 2604 OID 16710)
-- Name: com_tutor id_tutor; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_tutor ALTER COLUMN id_tutor SET DEFAULT nextval('public.com_tutor_id_tutor_seq'::regclass);


--
-- TOC entry 3016 (class 2604 OID 16421)
-- Name: ral_categoria id_categoria; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.ral_categoria_id_categoria_seq'::regclass);


--
-- TOC entry 3019 (class 2604 OID 16460)
-- Name: ral_conf id_conf; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_conf ALTER COLUMN id_conf SET DEFAULT nextval('public.ral_conf_id_conf_seq'::regclass);


--
-- TOC entry 3017 (class 2604 OID 16432)
-- Name: ral_persona id_persona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_persona ALTER COLUMN id_persona SET DEFAULT nextval('public.ral_persona_id_persona_seq'::regclass);


--
-- TOC entry 3018 (class 2604 OID 16445)
-- Name: ral_usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.ral_usuario_id_usuario_seq'::regclass);


--
-- TOC entry 3038 (class 2604 OID 17317)
-- Name: vehiculos id_vehiculo; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.vehiculos ALTER COLUMN id_vehiculo SET DEFAULT nextval('public.vehiculos_id_vehiculo_seq'::regclass);


--
-- TOC entry 3079 (class 2606 OID 16689)
-- Name: aca_asistencia aca_asistencia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_pkey PRIMARY KEY (id_asistencia);


--
-- TOC entry 3069 (class 2606 OID 16596)
-- Name: aca_aula aca_aula_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_aula
    ADD CONSTRAINT aca_aula_pkey PRIMARY KEY (id_aula);


--
-- TOC entry 3073 (class 2606 OID 16622)
-- Name: aca_clase aca_clase_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_pkey PRIMARY KEY (id_clase);


--
-- TOC entry 3065 (class 2606 OID 16554)
-- Name: aca_estudiante aca_estudiante_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT aca_estudiante_pkey PRIMARY KEY (id_estudiante);


--
-- TOC entry 3071 (class 2606 OID 16609)
-- Name: aca_horarios aca_horarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_horarios
    ADD CONSTRAINT aca_horarios_pkey PRIMARY KEY (id_horarios);


--
-- TOC entry 3077 (class 2606 OID 16661)
-- Name: aca_inscripcion aca_inscripcion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_pkey PRIMARY KEY (id_inscripcion);


--
-- TOC entry 3067 (class 2606 OID 16575)
-- Name: aca_materia aca_materia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_materia
    ADD CONSTRAINT aca_materia_pkey PRIMARY KEY (id_materia);


--
-- TOC entry 3087 (class 2606 OID 17266)
-- Name: aca_reprogramacion_horario aca_reprogramacion_horario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_reprogramacion_horario
    ADD CONSTRAINT aca_reprogramacion_horario_pkey PRIMARY KEY (id_reprogramacion_horario);


--
-- TOC entry 3061 (class 2606 OID 16512)
-- Name: adm_asi_personal adm_asi_personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_asi_personal
    ADD CONSTRAINT adm_asi_personal_pkey PRIMARY KEY (id_asi_personal);


--
-- TOC entry 3095 (class 2606 OID 17532)
-- Name: adm_cargo adm_cargo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_cargo
    ADD CONSTRAINT adm_cargo_pkey PRIMARY KEY (id_cargo);


--
-- TOC entry 3053 (class 2606 OID 16475)
-- Name: adm_conf_horarios adm_conf_horarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_conf_horarios
    ADD CONSTRAINT adm_conf_horarios_pkey PRIMARY KEY (id_conf_horarios, codigo_conf);


--
-- TOC entry 3097 (class 2606 OID 17543)
-- Name: adm_paginas adm_paginas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_paginas
    ADD CONSTRAINT adm_paginas_pkey PRIMARY KEY (id_paginas);


--
-- TOC entry 3063 (class 2606 OID 16525)
-- Name: adm_pago_personal adm_pago_personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_pago_personal
    ADD CONSTRAINT adm_pago_personal_pkey PRIMARY KEY (id_pago_personal);


--
-- TOC entry 3059 (class 2606 OID 16494)
-- Name: adm_personal adm_personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_personal
    ADD CONSTRAINT adm_personal_pkey PRIMARY KEY (id_personal);


--
-- TOC entry 3057 (class 2606 OID 16486)
-- Name: adm_ubicacion adm_ubicacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_ubicacion
    ADD CONSTRAINT adm_ubicacion_pkey PRIMARY KEY (id_ubicacion);


--
-- TOC entry 3089 (class 2606 OID 17311)
-- Name: clientes clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id_clientes);


--
-- TOC entry 3085 (class 2606 OID 16751)
-- Name: com_detalle_pago com_detalle_pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago
    ADD CONSTRAINT com_detalle_pago_pkey PRIMARY KEY (id_det_pago);


--
-- TOC entry 3083 (class 2606 OID 16738)
-- Name: com_pago com_pago_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_pago
    ADD CONSTRAINT com_pago_pkey PRIMARY KEY (id_pago);


--
-- TOC entry 3093 (class 2606 OID 17446)
-- Name: com_precios com_precios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_precios
    ADD CONSTRAINT com_precios_pkey PRIMARY KEY (id_precios);


--
-- TOC entry 3075 (class 2606 OID 16653)
-- Name: com_promocion com_promocion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_promocion
    ADD CONSTRAINT com_promocion_pkey PRIMARY KEY (id_promocion);


--
-- TOC entry 3081 (class 2606 OID 16712)
-- Name: com_tutor com_tutor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_tutor
    ADD CONSTRAINT com_tutor_pkey PRIMARY KEY (id_tutor);


--
-- TOC entry 3043 (class 2606 OID 16426)
-- Name: ral_categoria ral_categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_categoria
    ADD CONSTRAINT ral_categoria_pkey PRIMARY KEY (id_categoria);


--
-- TOC entry 3051 (class 2606 OID 16462)
-- Name: ral_conf ral_conf_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_conf
    ADD CONSTRAINT ral_conf_pkey PRIMARY KEY (id_conf);


--
-- TOC entry 3045 (class 2606 OID 16434)
-- Name: ral_persona ral_persona_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_persona
    ADD CONSTRAINT ral_persona_pkey PRIMARY KEY (id_persona);


--
-- TOC entry 3047 (class 2606 OID 16447)
-- Name: ral_usuario ral_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario
    ADD CONSTRAINT ral_usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 3049 (class 2606 OID 16449)
-- Name: ral_usuario ral_usuario_usuario_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario
    ADD CONSTRAINT ral_usuario_usuario_key UNIQUE (usuario);


--
-- TOC entry 3055 (class 2606 OID 16990)
-- Name: adm_conf_horarios uk_id_conf_horarios; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_conf_horarios
    ADD CONSTRAINT uk_id_conf_horarios UNIQUE (id_conf_horarios);


--
-- TOC entry 3091 (class 2606 OID 17322)
-- Name: vehiculos vehiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.vehiculos
    ADD CONSTRAINT vehiculos_pkey PRIMARY KEY (id_vehiculo);


--
-- TOC entry 3141 (class 2606 OID 16862)
-- Name: aca_asistencia aca_asistencia_id_clase_aca_clase_id_clase; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_id_clase_aca_clase_id_clase FOREIGN KEY (id_clase) REFERENCES public.aca_clase(id_clase) ON DELETE CASCADE;


--
-- TOC entry 3138 (class 2606 OID 16695)
-- Name: aca_asistencia aca_asistencia_id_clase_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_id_clase_fkey FOREIGN KEY (id_clase) REFERENCES public.aca_clase(id_clase) ON DELETE CASCADE;


--
-- TOC entry 3140 (class 2606 OID 16857)
-- Name: aca_asistencia aca_asistencia_id_estudiante_aca_estudiante_id_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_id_estudiante_aca_estudiante_id_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.aca_estudiante(id_estudiante) ON DELETE CASCADE;


--
-- TOC entry 3137 (class 2606 OID 16690)
-- Name: aca_asistencia aca_asistencia_id_estudiante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_id_estudiante_fkey FOREIGN KEY (id_estudiante) REFERENCES public.aca_estudiante(id_estudiante) ON DELETE CASCADE;


--
-- TOC entry 3139 (class 2606 OID 16700)
-- Name: aca_asistencia aca_asistencia_valor_asistencia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_valor_asistencia_fkey FOREIGN KEY (valor_asistencia) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3142 (class 2606 OID 16867)
-- Name: aca_asistencia aca_asistencia_valor_asistencia_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_asistencia
    ADD CONSTRAINT aca_asistencia_valor_asistencia_ral_categoria_id_categoria FOREIGN KEY (valor_asistencia) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3118 (class 2606 OID 16872)
-- Name: aca_aula aca_aula_id_ubicacion_adm_ubicacion_id_ubicacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_aula
    ADD CONSTRAINT aca_aula_id_ubicacion_adm_ubicacion_id_ubicacion FOREIGN KEY (id_ubicacion) REFERENCES public.adm_ubicacion(id_ubicacion) ON DELETE CASCADE;


--
-- TOC entry 3117 (class 2606 OID 16597)
-- Name: aca_aula aca_aula_id_ubicacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_aula
    ADD CONSTRAINT aca_aula_id_ubicacion_fkey FOREIGN KEY (id_ubicacion) REFERENCES public.adm_ubicacion(id_ubicacion) ON DELETE CASCADE;


--
-- TOC entry 3126 (class 2606 OID 16837)
-- Name: aca_clase aca_clase_id_aula_aca_aula_id_aula; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_aula_aca_aula_id_aula FOREIGN KEY (id_aula) REFERENCES public.aca_aula(id_aula) ON DELETE CASCADE;


--
-- TOC entry 3123 (class 2606 OID 16633)
-- Name: aca_clase aca_clase_id_aula_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_aula_fkey FOREIGN KEY (id_aula) REFERENCES public.aca_aula(id_aula) ON DELETE CASCADE;


--
-- TOC entry 3129 (class 2606 OID 17026)
-- Name: aca_clase aca_clase_id_horarios_adm_conf_horarios_id_conf_horarios; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_horarios_adm_conf_horarios_id_conf_horarios FOREIGN KEY (id_horarios) REFERENCES public.adm_conf_horarios(id_conf_horarios) ON DELETE CASCADE;


--
-- TOC entry 3128 (class 2606 OID 17021)
-- Name: aca_clase aca_clase_id_horarios_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_horarios_fkey FOREIGN KEY (id_horarios) REFERENCES public.adm_conf_horarios(id_conf_horarios) ON DELETE CASCADE;


--
-- TOC entry 3125 (class 2606 OID 16827)
-- Name: aca_clase aca_clase_id_materia_aca_materia_id_materia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_materia_aca_materia_id_materia FOREIGN KEY (id_materia) REFERENCES public.aca_materia(id_materia) ON DELETE CASCADE;


--
-- TOC entry 3122 (class 2606 OID 16623)
-- Name: aca_clase aca_clase_id_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_materia_fkey FOREIGN KEY (id_materia) REFERENCES public.aca_materia(id_materia) ON DELETE CASCADE;


--
-- TOC entry 3127 (class 2606 OID 16842)
-- Name: aca_clase aca_clase_id_personal_adm_personal_id_personal; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_personal_adm_personal_id_personal FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3124 (class 2606 OID 16638)
-- Name: aca_clase aca_clase_id_personal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_clase
    ADD CONSTRAINT aca_clase_id_personal_fkey FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3110 (class 2606 OID 16555)
-- Name: aca_estudiante aca_estudiante_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT aca_estudiante_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3111 (class 2606 OID 16797)
-- Name: aca_estudiante aca_estudiante_id_persona_ral_persona_id_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT aca_estudiante_id_persona_ral_persona_id_persona FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3119 (class 2606 OID 16610)
-- Name: aca_horarios aca_horarios_dias_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_horarios
    ADD CONSTRAINT aca_horarios_dias_fkey FOREIGN KEY (dias) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3120 (class 2606 OID 16877)
-- Name: aca_horarios aca_horarios_dias_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_horarios
    ADD CONSTRAINT aca_horarios_dias_ral_categoria_id_categoria FOREIGN KEY (dias) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3134 (class 2606 OID 16812)
-- Name: aca_inscripcion aca_inscripcion_id_clase_aca_clase_id_clase; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_id_clase_aca_clase_id_clase FOREIGN KEY (id_clase) REFERENCES public.aca_clase(id_clase) ON DELETE CASCADE;


--
-- TOC entry 3131 (class 2606 OID 16667)
-- Name: aca_inscripcion aca_inscripcion_id_clase_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_id_clase_fkey FOREIGN KEY (id_clase) REFERENCES public.aca_clase(id_clase) ON DELETE CASCADE;


--
-- TOC entry 3133 (class 2606 OID 16807)
-- Name: aca_inscripcion aca_inscripcion_id_estudiante_aca_estudiante_id_estudiante; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_id_estudiante_aca_estudiante_id_estudiante FOREIGN KEY (id_estudiante) REFERENCES public.aca_estudiante(id_estudiante) ON DELETE CASCADE;


--
-- TOC entry 3130 (class 2606 OID 16662)
-- Name: aca_inscripcion aca_inscripcion_id_estudiante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_id_estudiante_fkey FOREIGN KEY (id_estudiante) REFERENCES public.aca_estudiante(id_estudiante) ON DELETE CASCADE;


--
-- TOC entry 3136 (class 2606 OID 25840)
-- Name: aca_inscripcion aca_inscripcion_id_precios_com_precios_id_precios; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_id_precios_com_precios_id_precios FOREIGN KEY (id_precios) REFERENCES public.com_precios(id_precios) ON DELETE CASCADE;


--
-- TOC entry 3132 (class 2606 OID 16672)
-- Name: aca_inscripcion aca_inscripcion_lapso_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_lapso_fkey FOREIGN KEY (lapso) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3135 (class 2606 OID 16817)
-- Name: aca_inscripcion aca_inscripcion_lapso_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_inscripcion
    ADD CONSTRAINT aca_inscripcion_lapso_ral_categoria_id_categoria FOREIGN KEY (lapso) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3115 (class 2606 OID 16576)
-- Name: aca_materia aca_materia_tipo_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_materia
    ADD CONSTRAINT aca_materia_tipo_materia_fkey FOREIGN KEY (tipo_materia) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3116 (class 2606 OID 16847)
-- Name: aca_materia aca_materia_tipo_materia_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_materia
    ADD CONSTRAINT aca_materia_tipo_materia_ral_categoria_id_categoria FOREIGN KEY (tipo_materia) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3154 (class 2606 OID 17286)
-- Name: aca_reprogramacion_horario aca_reprogramacion_horario_id_id_clase_aca_clase_id_clase; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_reprogramacion_horario
    ADD CONSTRAINT aca_reprogramacion_horario_id_id_clase_aca_clase_id_clase FOREIGN KEY (id_clase) REFERENCES public.aca_clase(id_clase) ON DELETE CASCADE;


--
-- TOC entry 3152 (class 2606 OID 17267)
-- Name: aca_reprogramacion_horario aca_reprogramacion_horario_id_inscripcion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_reprogramacion_horario
    ADD CONSTRAINT aca_reprogramacion_horario_id_inscripcion_fkey FOREIGN KEY (id_inscripcion) REFERENCES public.aca_inscripcion(id_inscripcion) ON DELETE CASCADE;


--
-- TOC entry 3153 (class 2606 OID 17272)
-- Name: aca_reprogramacion_horario aca_reprogramacion_horario_id_reprogramacion_horario_aca_inscri; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_reprogramacion_horario
    ADD CONSTRAINT aca_reprogramacion_horario_id_reprogramacion_horario_aca_inscri FOREIGN KEY (id_inscripcion) REFERENCES public.aca_inscripcion(id_inscripcion) ON DELETE CASCADE;


--
-- TOC entry 3107 (class 2606 OID 16772)
-- Name: adm_asi_personal adm_asi_personal_id_personal_adm_personal_id_personal; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_asi_personal
    ADD CONSTRAINT adm_asi_personal_id_personal_adm_personal_id_personal FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3106 (class 2606 OID 16513)
-- Name: adm_asi_personal adm_asi_personal_id_personal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_asi_personal
    ADD CONSTRAINT adm_asi_personal_id_personal_fkey FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3109 (class 2606 OID 16787)
-- Name: adm_pago_personal adm_pago_personal_id_personal_adm_personal_id_personal; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_pago_personal
    ADD CONSTRAINT adm_pago_personal_id_personal_adm_personal_id_personal FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3108 (class 2606 OID 16526)
-- Name: adm_pago_personal adm_pago_personal_id_personal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_pago_personal
    ADD CONSTRAINT adm_pago_personal_id_personal_fkey FOREIGN KEY (id_personal) REFERENCES public.adm_personal(id_personal) ON DELETE CASCADE;


--
-- TOC entry 3105 (class 2606 OID 17565)
-- Name: adm_personal adm_personal_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_personal
    ADD CONSTRAINT adm_personal_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3103 (class 2606 OID 16777)
-- Name: adm_personal adm_personal_id_persona_ral_persona_id_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_personal
    ADD CONSTRAINT adm_personal_id_persona_ral_persona_id_persona FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3104 (class 2606 OID 17554)
-- Name: adm_personal adm_personal_puesto_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.adm_personal
    ADD CONSTRAINT adm_personal_puesto_ral_categoria_id_categoria FOREIGN KEY (puesto) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3150 (class 2606 OID 17128)
-- Name: com_detalle_pago com_detalle_pago_id_inscripcion_aca_inscripcion_id_inscripcion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago
    ADD CONSTRAINT com_detalle_pago_id_inscripcion_aca_inscripcion_id_inscripcion FOREIGN KEY (id_inscripcion) REFERENCES public.aca_inscripcion(id_inscripcion) ON DELETE CASCADE;


--
-- TOC entry 3151 (class 2606 OID 17133)
-- Name: com_detalle_pago com_detalle_pago_id_inscripcion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago
    ADD CONSTRAINT com_detalle_pago_id_inscripcion_fkey FOREIGN KEY (id_inscripcion) REFERENCES public.aca_inscripcion(id_inscripcion) ON DELETE CASCADE;


--
-- TOC entry 3149 (class 2606 OID 16897)
-- Name: com_detalle_pago com_detalle_pago_id_pago_com_pago_id_pago; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago
    ADD CONSTRAINT com_detalle_pago_id_pago_com_pago_id_pago FOREIGN KEY (id_pago) REFERENCES public.com_pago(id_pago) ON DELETE CASCADE;


--
-- TOC entry 3148 (class 2606 OID 16752)
-- Name: com_detalle_pago com_detalle_pago_id_pago_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_detalle_pago
    ADD CONSTRAINT com_detalle_pago_id_pago_fkey FOREIGN KEY (id_pago) REFERENCES public.com_pago(id_pago) ON DELETE CASCADE;


--
-- TOC entry 3147 (class 2606 OID 17123)
-- Name: com_pago com_pago_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_pago
    ADD CONSTRAINT com_pago_id_cliente_fkey FOREIGN KEY (id_tutor) REFERENCES public.com_tutor(id_tutor) ON DELETE CASCADE;


--
-- TOC entry 3146 (class 2606 OID 17118)
-- Name: com_pago com_pago_id_tutor_com_tutor_id_tutor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_pago
    ADD CONSTRAINT com_pago_id_tutor_com_tutor_id_tutor FOREIGN KEY (id_tutor) REFERENCES public.com_tutor(id_tutor) ON DELETE CASCADE;


--
-- TOC entry 3156 (class 2606 OID 17447)
-- Name: com_precios com_precios_id_materia_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_precios
    ADD CONSTRAINT com_precios_id_materia_fkey FOREIGN KEY (id_materia) REFERENCES public.aca_materia(id_materia) ON DELETE CASCADE;


--
-- TOC entry 3157 (class 2606 OID 17452)
-- Name: com_precios com_precios_id_precios_aca_materia_id_materia; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_precios
    ADD CONSTRAINT com_precios_id_precios_aca_materia_id_materia FOREIGN KEY (id_materia) REFERENCES public.aca_materia(id_materia) ON DELETE CASCADE;


--
-- TOC entry 3145 (class 2606 OID 17477)
-- Name: com_tutor com_tutor_fuente_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_tutor
    ADD CONSTRAINT com_tutor_fuente_ral_categoria_id_categoria FOREIGN KEY (fuente) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3143 (class 2606 OID 16713)
-- Name: com_tutor com_tutor_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_tutor
    ADD CONSTRAINT com_tutor_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3144 (class 2606 OID 16882)
-- Name: com_tutor com_tutor_id_persona_ral_persona_id_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.com_tutor
    ADD CONSTRAINT com_tutor_id_persona_ral_persona_id_persona FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3114 (class 2606 OID 17113)
-- Name: aca_estudiante fk_aca_estudiante_id_tutor_com_tutor_id_tutor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT fk_aca_estudiante_id_tutor_com_tutor_id_tutor FOREIGN KEY (id_tutor) REFERENCES public.com_tutor(id_tutor);


--
-- TOC entry 3112 (class 2606 OID 17057)
-- Name: aca_estudiante fk_aca_estudiante_nivel_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT fk_aca_estudiante_nivel_ral_categoria_id_categoria FOREIGN KEY (nivel) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3113 (class 2606 OID 17062)
-- Name: aca_estudiante fk_aca_estudiante_turno_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_estudiante
    ADD CONSTRAINT fk_aca_estudiante_turno_ral_categoria_id_categoria FOREIGN KEY (turno) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3121 (class 2606 OID 16991)
-- Name: aca_horarios fk_aca_horarios_id_conf_horarios_adm_conf_horarios_id_conf_hora; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.aca_horarios
    ADD CONSTRAINT fk_aca_horarios_id_conf_horarios_adm_conf_horarios_id_conf_hora FOREIGN KEY (id_conf_horarios) REFERENCES public.adm_conf_horarios(id_conf_horarios) ON DELETE CASCADE;


--
-- TOC entry 3155 (class 2606 OID 17323)
-- Name: vehiculos fksp56ecw0uc1upsj7pt1ecw6uf; Type: FK CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.vehiculos
    ADD CONSTRAINT fksp56ecw0uc1upsj7pt1ecw6uf FOREIGN KEY (cliente_id_clientes) REFERENCES public.clientes(id_clientes);


--
-- TOC entry 3101 (class 2606 OID 16463)
-- Name: ral_conf ral_conf_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_conf
    ADD CONSTRAINT ral_conf_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3102 (class 2606 OID 16767)
-- Name: ral_conf ral_conf_id_categoria_ral_categoria_id_categoria; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_conf
    ADD CONSTRAINT ral_conf_id_categoria_ral_categoria_id_categoria FOREIGN KEY (id_categoria) REFERENCES public.ral_categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 3098 (class 2606 OID 16450)
-- Name: ral_usuario ral_usuario_id_persona_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario
    ADD CONSTRAINT ral_usuario_id_persona_fkey FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3099 (class 2606 OID 16757)
-- Name: ral_usuario ral_usuario_id_persona_ral_persona_id_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario
    ADD CONSTRAINT ral_usuario_id_persona_ral_persona_id_persona FOREIGN KEY (id_persona) REFERENCES public.ral_persona(id_persona) ON DELETE CASCADE;


--
-- TOC entry 3100 (class 2606 OID 17559)
-- Name: ral_usuario ral_usuario_nivel_adm_cargo_id_cargo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ral_usuario
    ADD CONSTRAINT ral_usuario_nivel_adm_cargo_id_cargo FOREIGN KEY (nivel) REFERENCES public.adm_cargo(id_cargo) ON DELETE CASCADE;


-- Completed on 2024-12-05 01:04:34 -04

--
-- PostgreSQL database dump complete
--

--
-- Database "guevara" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:34 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 2997 (class 1262 OID 17383)
-- Name: guevara; Type: DATABASE; Schema: -; Owner: ayrton2
--

CREATE DATABASE guevara WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_BO.UTF-8' LC_CTYPE = 'es_BO.UTF-8';


ALTER DATABASE guevara OWNER TO ayrton2;

\connect guevara

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 203 (class 1259 OID 17386)
-- Name: m_categoria; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.m_categoria (
    id_categoria bigint NOT NULL,
    detalle_categoria character varying(35),
    nombre_categoria character varying(35)
);


ALTER TABLE public.m_categoria OWNER TO ayrton2;

--
-- TOC entry 202 (class 1259 OID 17384)
-- Name: m_categoria_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.m_categoria_id_categoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.m_categoria_id_categoria_seq OWNER TO ayrton2;

--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 202
-- Name: m_categoria_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.m_categoria_id_categoria_seq OWNED BY public.m_categoria.id_categoria;


--
-- TOC entry 205 (class 1259 OID 17394)
-- Name: m_clientes; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.m_clientes (
    id_clientes bigint NOT NULL,
    estado character varying(15),
    f_nacimiento timestamp without time zone,
    materno character varying(35),
    nombre character varying(35),
    numero_documento character varying(15),
    paterno character varying(35),
    t_documento_id bigint,
    genero_id bigint
);


ALTER TABLE public.m_clientes OWNER TO ayrton2;

--
-- TOC entry 204 (class 1259 OID 17392)
-- Name: m_clientes_id_clientes_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.m_clientes_id_clientes_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.m_clientes_id_clientes_seq OWNER TO ayrton2;

--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 204
-- Name: m_clientes_id_clientes_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.m_clientes_id_clientes_seq OWNED BY public.m_clientes.id_clientes;


--
-- TOC entry 207 (class 1259 OID 17402)
-- Name: m_marca; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.m_marca (
    id_marca bigint NOT NULL,
    nombre_marca character varying(35)
);


ALTER TABLE public.m_marca OWNER TO ayrton2;

--
-- TOC entry 206 (class 1259 OID 17400)
-- Name: m_marca_id_marca_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.m_marca_id_marca_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.m_marca_id_marca_seq OWNER TO ayrton2;

--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 206
-- Name: m_marca_id_marca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.m_marca_id_marca_seq OWNED BY public.m_marca.id_marca;


--
-- TOC entry 209 (class 1259 OID 17410)
-- Name: m_vehiculos; Type: TABLE; Schema: public; Owner: ayrton2
--

CREATE TABLE public.m_vehiculos (
    id_vehiculo bigint NOT NULL,
    "año" integer,
    estado character varying(15),
    modelo character varying(35),
    placa character varying(6),
    id_clientes bigint,
    id_marca bigint
);


ALTER TABLE public.m_vehiculos OWNER TO ayrton2;

--
-- TOC entry 208 (class 1259 OID 17408)
-- Name: m_vehiculos_id_vehiculo_seq; Type: SEQUENCE; Schema: public; Owner: ayrton2
--

CREATE SEQUENCE public.m_vehiculos_id_vehiculo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.m_vehiculos_id_vehiculo_seq OWNER TO ayrton2;

--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 208
-- Name: m_vehiculos_id_vehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ayrton2
--

ALTER SEQUENCE public.m_vehiculos_id_vehiculo_seq OWNED BY public.m_vehiculos.id_vehiculo;


--
-- TOC entry 2850 (class 2604 OID 17389)
-- Name: m_categoria id_categoria; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.m_categoria_id_categoria_seq'::regclass);


--
-- TOC entry 2851 (class 2604 OID 17397)
-- Name: m_clientes id_clientes; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_clientes ALTER COLUMN id_clientes SET DEFAULT nextval('public.m_clientes_id_clientes_seq'::regclass);


--
-- TOC entry 2852 (class 2604 OID 17405)
-- Name: m_marca id_marca; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_marca ALTER COLUMN id_marca SET DEFAULT nextval('public.m_marca_id_marca_seq'::regclass);


--
-- TOC entry 2853 (class 2604 OID 17413)
-- Name: m_vehiculos id_vehiculo; Type: DEFAULT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_vehiculos ALTER COLUMN id_vehiculo SET DEFAULT nextval('public.m_vehiculos_id_vehiculo_seq'::regclass);


--
-- TOC entry 2855 (class 2606 OID 17391)
-- Name: m_categoria m_categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_categoria
    ADD CONSTRAINT m_categoria_pkey PRIMARY KEY (id_categoria);


--
-- TOC entry 2857 (class 2606 OID 17399)
-- Name: m_clientes m_clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_clientes
    ADD CONSTRAINT m_clientes_pkey PRIMARY KEY (id_clientes);


--
-- TOC entry 2859 (class 2606 OID 17407)
-- Name: m_marca m_marca_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_marca
    ADD CONSTRAINT m_marca_pkey PRIMARY KEY (id_marca);


--
-- TOC entry 2861 (class 2606 OID 17415)
-- Name: m_vehiculos m_vehiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_vehiculos
    ADD CONSTRAINT m_vehiculos_pkey PRIMARY KEY (id_vehiculo);


--
-- TOC entry 2862 (class 2606 OID 17416)
-- Name: m_clientes fkccmuqvlwm1u63lc9sweyfskfi; Type: FK CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_clientes
    ADD CONSTRAINT fkccmuqvlwm1u63lc9sweyfskfi FOREIGN KEY (t_documento_id) REFERENCES public.m_categoria(id_categoria);


--
-- TOC entry 2864 (class 2606 OID 17426)
-- Name: m_vehiculos fkk5pce680amrfnratlp9jb3k6p; Type: FK CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_vehiculos
    ADD CONSTRAINT fkk5pce680amrfnratlp9jb3k6p FOREIGN KEY (id_clientes) REFERENCES public.m_clientes(id_clientes);


--
-- TOC entry 2865 (class 2606 OID 17431)
-- Name: m_vehiculos fklv1pw6xk9vo976exo361wr1df; Type: FK CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_vehiculos
    ADD CONSTRAINT fklv1pw6xk9vo976exo361wr1df FOREIGN KEY (id_marca) REFERENCES public.m_marca(id_marca);


--
-- TOC entry 2863 (class 2606 OID 17421)
-- Name: m_clientes fkmitx668s7kcqga463iqgo0gok; Type: FK CONSTRAINT; Schema: public; Owner: ayrton2
--

ALTER TABLE ONLY public.m_clientes
    ADD CONSTRAINT fkmitx668s7kcqga463iqgo0gok FOREIGN KEY (genero_id) REFERENCES public.m_categoria(id_categoria);


-- Completed on 2024-12-05 01:04:36 -04

--
-- PostgreSQL database dump complete
--

--
-- Database "postgres" dump
--

\connect postgres

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:36 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

-- Completed on 2024-12-05 01:04:37 -04

--
-- PostgreSQL database dump complete
--

--
-- Database "proyecto_laravel" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:37 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3026 (class 1262 OID 25767)
-- Name: proyecto_laravel; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE proyecto_laravel WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_BO.UTF-8' LC_CTYPE = 'es_BO.UTF-8';


ALTER DATABASE proyecto_laravel OWNER TO postgres;

\connect proyecto_laravel

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 209 (class 1259 OID 25806)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 25804)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 3027 (class 0 OID 0)
-- Dependencies: 208
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 203 (class 1259 OID 25770)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 25768)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 3028 (class 0 OID 0)
-- Dependencies: 202
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 206 (class 1259 OID 25789)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 25797)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 25820)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 25818)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 3029 (class 0 OID 0)
-- Dependencies: 210
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 213 (class 1259 OID 25834)
-- Name: tasks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tasks (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.tasks OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 25832)
-- Name: tasks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tasks_id_seq OWNER TO postgres;

--
-- TOC entry 3030 (class 0 OID 0)
-- Dependencies: 212
-- Name: tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tasks_id_seq OWNED BY public.tasks.id;


--
-- TOC entry 205 (class 1259 OID 25778)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 25776)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 3031 (class 0 OID 0)
-- Dependencies: 204
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 2871 (class 2604 OID 25809)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 2869 (class 2604 OID 25773)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 2873 (class 2604 OID 25823)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 2874 (class 2604 OID 25837)
-- Name: tasks id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tasks ALTER COLUMN id SET DEFAULT nextval('public.tasks_id_seq'::regclass);


--
-- TOC entry 2870 (class 2604 OID 25781)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 2885 (class 2606 OID 25815)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 2887 (class 2606 OID 25817)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 2876 (class 2606 OID 25775)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 2882 (class 2606 OID 25796)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 2889 (class 2606 OID 25828)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 2891 (class 2606 OID 25831)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 2894 (class 2606 OID 25839)
-- Name: tasks tasks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);


--
-- TOC entry 2878 (class 2606 OID 25788)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 2880 (class 2606 OID 25786)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2883 (class 1259 OID 25803)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- TOC entry 2892 (class 1259 OID 25829)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


-- Completed on 2024-12-05 01:04:40 -04

--
-- PostgreSQL database dump complete
--

--
-- Database "prueba0" dump
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.18 (Ubuntu 12.18-0ubuntu0.20.04.1)

-- Started on 2024-12-05 01:04:40 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 2980 (class 1262 OID 16387)
-- Name: prueba0; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE prueba0 WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'es_BO.UTF-8' LC_CTYPE = 'es_BO.UTF-8';


ALTER DATABASE prueba0 OWNER TO postgres;

\connect prueba0

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 219 (class 1255 OID 16408)
-- Name: fn_generar_numeros(integer, integer, integer, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fn_generar_numeros(pinicial integer, pfinal integer, prango integer, ptipo character varying) RETURNS json
    LANGUAGE plpgsql
    AS $$
declare
ArmadoJson JSON;
omensaje text;
obool boolean;
begin
	omensaje:='';
	obool:=true;
	case ptipo
		when 'incremental' then
			if pinicial < pfinal then
				
					select array_to_json(array_agg(row_to_json(t)))
					into ArmadoJson
					from (select ('Nº '||lpad(generate_series(pinicial::integer,pfinal::integer,prango::integer)::text,7,'0'))as ticket)as t;
				
			else 
				omensaje:='el numero inicial debe ser menor al numero final';
				obool:=false;
			end if;
		when 'decremental' then
			if pfinal < pinicial then
				
					select array_to_json(array_agg(row_to_json(t)))
					into ArmadoJson
					from (select ('Nº '||lpad(generate_series(pinicial::integer,pfinal::integer,-prango::integer)::text,7,'0'))as ticket)as t;
				
			else
				omensaje:='el numero inicial debe ser mayor al numero final';
				obool:=false;
			end if;
		else
			omensaje:= 'tipo de enumeracion invalido';
			obool:=false;
	end case;
	ArmadoJson:=json_build_object('data',ArmadoJson,'mensaje',omensaje,'boolean',obool);
	return ArmadoJson;
end;
$$;


ALTER FUNCTION public.fn_generar_numeros(pinicial integer, pfinal integer, prango integer, ptipo character varying) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 206 (class 1259 OID 16409)
-- Name: armadojson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.armadojson (
    array_to_json json
);


ALTER TABLE public.armadojson OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 16402)
-- Name: cargos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cargos (
    id integer NOT NULL,
    value character varying(45)
);


ALTER TABLE public.cargos OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16400)
-- Name: cargos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cargos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cargos_id_seq OWNER TO postgres;

--
-- TOC entry 2981 (class 0 OID 0)
-- Dependencies: 204
-- Name: cargos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cargos_id_seq OWNED BY public.cargos.id;


--
-- TOC entry 202 (class 1259 OID 16394)
-- Name: estudiantes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.estudiantes (
    id integer,
    nombre character varying(45),
    apellido_paterno character varying(45),
    apellido_materno character varying(45),
    edad integer,
    fecha_nacimiento date
);


ALTER TABLE public.estudiantes OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 16397)
-- Name: estudiantes2; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.estudiantes2 (
    id integer,
    nombre character varying(45),
    apellido_paterno character varying(45),
    apellido_materno character varying(45),
    edad integer,
    fecha_nacimiento date
);


ALTER TABLE public.estudiantes2 OWNER TO postgres;

--
-- TOC entry 2846 (class 2604 OID 16405)
-- Name: cargos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cargos ALTER COLUMN id SET DEFAULT nextval('public.cargos_id_seq'::regclass);


--
-- TOC entry 2848 (class 2606 OID 16407)
-- Name: cargos cargos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cargos
    ADD CONSTRAINT cargos_pkey PRIMARY KEY (id);


-- Completed on 2024-12-05 01:04:42 -04

--
-- PostgreSQL database dump complete
--

-- Completed on 2024-12-05 01:04:42 -04

--
-- PostgreSQL database cluster dump complete
--

