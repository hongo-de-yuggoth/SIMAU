//-----------------------------------------------------------------------------

function cargar_tabla(datos_json)
{
	// Si se encontraron solicitudes....
	if ( datos_json.resultado == true )
	{
		// la cargamos en la página.
		var thead = '<thead><tr><th>Número<br>Solicitud&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Dependencia</th><th>Solicitante</th><th>Tipo de Servicio</th><th>Fecha de Solicitud</th><th>Estado</th></tr></thead>';
		var tbody = '<tbody></tbody>';
		jQuery('#tabla_resultados').empty().html(thead).append(tbody);
		jQuery('#tabla_resultados tbody').html(datos_json.filas_tabla);
		jQuery('#tabla_resultados').tablesorter({widthFixed: false}).trigger("update");
		jQuery('#resultados').show();
		jQuery('#error_consulta').html('Se encontraron las siguientes solicitudes.').show();
		jQuery('#total_registros').html('<b>TOTAL:</b> '+datos_json.count+' registro(s)').show();
	}
	else
	{
		jQuery('#tabla_resultados tbody').html('');
		jQuery('#resultados').hide();
		jQuery('#error_consulta').html('No se encontraron solicitudes.').show();
		jQuery('#total_registros').html('').hide();
	}
}

//-----------------------------------------------------------------------------

function buscar_solicitudes(frase_busqueda, criterio_fecha, fecha_1, fecha_2, mostrar_solicitudes, criterio_campo, tipo_servicio)
{
	$.ajax(
	{
		type: "POST",
		url: '/solicitudes/buscar/'+frase_busqueda+'/'+criterio_fecha+'/'+fecha_1+'/'+fecha_2+'/'+mostrar_solicitudes+'/'+criterio_campo+'/'+tipo_servicio,
		dataType: 'json',
		cache: false,
		async: false,
		success: function(datos_json)
		{
			if ( datos_json.resultado == true )
			{
				actualizar_link_xls(frase_busqueda, criterio_fecha, fecha_1, fecha_2, mostrar_solicitudes, criterio_campo, tipo_servicio);
			}
			else
			{
				$('#archivo_xls a').attr('href', '#');
				$('#archivo_xls').hide();
			}
			cargar_tabla(datos_json);
		}
	});
}

//-----------------------------------------------------------------------------

function buscar_solicitud(id)
{
	$.ajax(
	{
		type: "POST",
		url: '/solicitudes/info_solicitud/'+id,
		dataType: 'json',
		cache: false,
		async: false,
		success: cargar_tabla
	});
}

//-----------------------------------------------------------------------------

function actualizar_link_xls(frase_busqueda, criterio_fecha, fecha_1, fecha_2, mostrar_solicitudes, criterio_campo, tipo_servicio)
{
	jQuery('#archivo_xls a').attr('href', '/solicitudes/exportar_xls/'+frase_busqueda+'/'+criterio_fecha+'/'+fecha_1+'/'+fecha_2+'/'+mostrar_solicitudes+'/'+criterio_campo+'/'+tipo_servicio);
	jQuery('#archivo_xls').show();
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	jQuery('#r_anio_mes').attr('checked', 'true');

	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.

	jQuery('#boton_cal_1').click(function()
	{
		displayCalendar(jQuery('#fecha_inicial').get(0),'yyyy-mm-dd',jQuery('#boton_cal_1').get(0));
	});
	jQuery('#boton_cal_2').click(function()
	{
		displayCalendar(jQuery('#fecha_final').get(0),'yyyy-mm-dd',jQuery('#boton_cal_2').get(0));
	});

	jQuery('#r_anio_mes').change(function()
	{
		jQuery('div[class=div_busqueda]').hide();
		jQuery('#fecha_anio_mes').show();
	});

	jQuery('#r_rango_fecha').change(function()
	{
		jQuery('div[class=div_busqueda]').hide();
		jQuery('#fecha_rango').show();
	});

	jQuery('#select_anio').change(function()
	{
		if ( jQuery(this).val() == 0 )
		{
			jQuery('#select_mes option[value=0]').attr('selected', 'true');
			jQuery('#select_mes').attr('disabled', 'true');
		}
		else
		{
			jQuery('#select_mes').removeAttr('disabled');
		}
	});

	jQuery('#boton_buscar_solicitudes').click(function()
	{
		jQuery('#busqueda').val(jQuery.trim(jQuery('#busqueda').val()));
		var frase_busqueda = jQuery('#busqueda').val();
		var criterio_campo = jQuery('#select_campo').val();
		if ( criterio_campo == 'id' )
		{
			// Validamos "id" en el campo BUSCAR.
			if ( frase_busqueda == '' )
			{
				$('#error_numero_solicitud').html('Escribe el número de la solicitud.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else if ( !es_numero(frase_busqueda) )
			{
				$('#error_numero_solicitud').html('Debes escribir un valor numérico y sin puntos.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else
			{
				$('#error_numero_solicitud').html('').hide();
				buscar_solicitud(frase_busqueda);
			}
		}
		else if ( criterio_campo != 'todos' && frase_busqueda == '' )
		{
			// Mostrar error...
			$('#error_numero_solicitud').html('Debes escribir una palabra de búsqueda.').show();
			$('#tabla_resultados tbody').html('');
			$('#resultados').hide();
			$('#error_consulta').hide();
			$('#total_registros').html('').hide();
			$('#archivo_xls').hide();
		}
		else
		{
			$('#error_numero_solicitud').html('').hide();
			var fecha_1;
			var fecha_2;
			var criterio_fecha = jQuery('#criterio_fecha input[checked=true]:radio').val();
			if ( criterio_fecha == 'rango_fecha' )
			{
				if ( !fechas_con_logica() )
				{
					jQuery('#error_consulta').html('').hide();
					return;
				}
				fecha_1 = jQuery('#fecha_inicial').val();
				fecha_2 = jQuery('#fecha_final').val();
			}
			else
			{
				fecha_1 = jQuery('#select_anio').val();
				fecha_2 = jQuery('#select_mes').val();
			}

			if ( frase_busqueda == '' )
			{
				frase_busqueda = 'null';
			}
			var mostrar_solicitudes = jQuery('#select_solicitudes').val();
			var tipo_servicio = jQuery('#select_servicio').val();
			buscar_solicitudes(frase_busqueda, criterio_fecha, fecha_1, fecha_2, mostrar_solicitudes, criterio_campo, tipo_servicio);
		}
	});
	jQuery('#select_campo').change(function()
	{
		// Si se selecciona el Número de solicitud.
		if ( jQuery(this).val() == 'id' )
		{
			jQuery('#select_solicitudes').attr('disabled', 'true');
			jQuery('#select_servicio').attr('disabled', 'true');
			jQuery('#criterio_fecha').hide();
		}
		else
		{
			jQuery('#select_solicitudes').removeAttr('disabled');
			jQuery('#select_servicio').removeAttr('disabled');
			jQuery('#criterio_fecha').show();
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#busqueda').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			jQuery('#boton_buscar_solicitudes').click();
			return false;
		}
		return true;
	});
});
