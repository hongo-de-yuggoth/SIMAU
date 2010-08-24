//-----------------------------------------------------------------------------

function cargar_tabla(datos_json)
{
	// Si se encontraron equipos....
	if ( datos_json.resultado == true )
	{
		// la cargamos en la página.
		var thead = '<thead><tr><th>Placa del<br>Equipo</th><th>Nombre</th><th>Modelo</th><th>Dependencia</th><th>Usuario</th></tr></thead>';
		var tbody = '<tbody></tbody>';
		jQuery('#tabla_resultados').empty().html(thead).append(tbody);
		jQuery('#tabla_resultados tbody').html(datos_json.filas_tabla);
		jQuery('#tabla_resultados').tablesorter({widthFixed: false}).trigger("update");
		jQuery('#resultados').show();
		jQuery('#error_consulta').html('Se encontraron los siguientes equipos.').show();
		jQuery('#total_registros').html('<b>TOTAL:</b> '+datos_json.count+' registro(s)').show();
	}
	else
	{
		jQuery('#tabla_resultados tbody').html('');
		jQuery('#resultados').hide();
		jQuery('#error_consulta').html('No se encontraron equipos.').show();
		jQuery('#total_registros').html('').hide();
	}
}

//-----------------------------------------------------------------------------

function actualizar_link_xls(frase_busqueda, criterio_campo)
{
	$('#archivo_xls a').attr('href', '/equipos/usr_exportar_xls/'+frase_busqueda+'/'+criterio_campo);
	$('#archivo_xls').show();
	$('#archivo_pdf a').attr('href', '#');
	$('#archivo_pdf').hide();
}

//-----------------------------------------------------------------------------

function actualizar_link_pdf(id_equipo)
{
	$('#archivo_pdf a').attr('href', '/equipos/exportar_pdf/'+id_equipo);
	$('#archivo_pdf').show();
	$('#archivo_xls a').attr('href', '#');
	$('#archivo_xls').hide();
}

//-----------------------------------------------------------------------------

function buscar_equipos(frase_busqueda, criterio_campo)
{
	$.ajax(
	{
		type: "POST",
		url: '/equipos/usr_buscar/'+frase_busqueda+'/'+criterio_campo,
		dataType: 'json',
		cache: false,
		async: false,
		success: function(datos_json)
		{
			if ( datos_json.resultado == true )
			{
				actualizar_link_xls(frase_busqueda, criterio_campo);
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

function buscar_equipo(placa)
{
	$.ajax(
	{
		type: "POST",
		url: '/equipos/usr_info_equipo/'+placa,
		dataType: 'json',
		cache: false,
		async: false,
		success: cargar_tabla
	});
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

$(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	
	$('#r_anio_mes').attr('checked', 'true');
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	$('#boton_buscar_equipos').click(function()
	{
		jQuery('#busqueda').val(jQuery.trim(jQuery('#busqueda').val()));
		var frase_busqueda = jQuery('#busqueda').val();
		var criterio_campo = jQuery('#select_campo').val();
		
		if ( criterio_campo == 'prousu_placa' )
		{
			// Validamos "prousu_placa" en el campo BUSCAR.
			if ( frase_busqueda == '' )
			{
				$('#error_placa_inventario').html('Escribe la placa de inventario.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else if ( !es_numero(frase_busqueda) )
			{
				$('#error_placa_inventario').html('Debes escribir un valor numérico y sin puntos.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else
			{
				$('#error_placa_inventario').html('').hide();
				buscar_equipo(frase_busqueda);
			}
		}
		else
		{
			$('#error_placa_inventario').html('').hide();
			if ( frase_busqueda == '' )
			{
				frase_busqueda = 'null';
			}
			
			buscar_equipos(frase_busqueda, criterio_campo);
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#busqueda').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			jQuery('#boton_buscar_equipos').click();
			return false;
		}
		return true;
	});
	//--------------------------------------------------------------------------
});
