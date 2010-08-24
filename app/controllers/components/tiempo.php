<?php
class TiempoComponent extends Object
{
	function fecha_espaniol($fecha)
	{
		// {Y-n-j-N} segun formato de date()
		list($anio, $mes, $dia, $dia_semana) = split('-', $fecha);
		
		if($dia_semana == '1'){ $dia_esp='Lunes'; }
		if($dia_semana == '2'){ $dia_esp='Martes'; }
		if($dia_semana == '3'){ $dia_esp='Miercoles'; }
		if($dia_semana == '4'){ $dia_esp='Jueves'; }
		if($dia_semana == '5'){ $dia_esp='Viernes'; }
		if($dia_semana == '6'){ $dia_esp='Sábado'; }
		if($dia_semana == '7'){ $dia_esp='Domingo'; }
		
		if($mes == '1'){ $mes_esp='Enero'; }
		if($mes == '2'){ $mes_esp='Febrero'; }
		if($mes == '3'){ $mes_esp='Marzo'; }
		if($mes == '4'){ $mes_esp='Abril'; }
		if($mes == '5'){ $mes_esp='Mayo'; }
		if($mes == '6'){ $mes_esp='Junio'; }
		if($mes == '7'){ $mes_esp='Julio'; }
		if($mes == '8'){ $mes_esp='Agosto'; }
		if($mes == '9'){ $mes_esp='Septiembre'; }
		if($mes == '10'){ $mes_esp='Octubre'; }
		if($mes == '11'){ $mes_esp='Noviembre'; }
		if($mes == '12'){ $mes_esp='Diciembre'; }
		
		return $dia_esp.' '.$dia.' de '.$mes_esp.' del '.$anio;
	}
	//--------------------------------------------------------------------------

}
?>