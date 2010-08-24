//-----------------------------------------------------------------------------

function cargar_tabla(datos_json)
{
	// Si se encontraron usuarios....
	if ( datos_json.resultado == true )
	{
		// la cargamos en la página.
		var thead = '<thead><tr><th>Nombre</th><th>Cédula</th><th>Login</th><th>Dependencia</th><th>Tipo de Usuario</th></tr></thead>';
		var tbody = '<tbody></tbody>';
		jQuery('#tabla_resultados').empty().html(thead).append(tbody);
		jQuery('#tabla_resultados tbody').html(datos_json.filas_tabla);
		jQuery('#tabla_resultados').tablesorter({widthFixed: false}).trigger("update");
		$('#info_usuario').html('').hide();
		$('#resultados').show();
		$('#error_consulta').html('Se encontraron los siguientes usuarios.').show();
		$('#total_registros').html('<b>TOTAL:</b> '+datos_json.count+' registro(s)').show();
	}
	else
	{
		$('#tabla_resultados tbody').html('');
		$('#info_usuario').html('').hide();
		$('#resultados').hide();
		$('#total_registros').html('').hide();
		$('#error_consulta').html('No se encontraron usuarios.').show();
	}
}

//-----------------------------------------------------------------------------

function actualizar_link_xls(frase_busqueda, criterio_campo, criterio_dependencia, criterio_tipo_usuario)
{
	$('#archivo_xls a').attr('href', '/smuq_usuarios/exportar_xls/'+frase_busqueda+'/'+criterio_campo+'/'+criterio_dependencia+'/'+criterio_tipo_usuario);
	$('#archivo_xls').show();
}

//-----------------------------------------------------------------------------

function buscar_usuarios(frase_busqueda, criterio_campo, criterio_dependencia, criterio_tipo_usuario)
{
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/buscar/'+frase_busqueda+'/'+criterio_campo+'/'+criterio_dependencia+'/'+criterio_tipo_usuario,
		dataType: 'json',
		cache: false,
		async: false,
		success: function(datos_json)
		{
			if ( datos_json.resultado == true )
			{
				actualizar_link_xls(frase_busqueda, criterio_campo, criterio_dependencia, criterio_tipo_usuario);
			}
			else
			{
				$('#archivo_xls a').attr('href', '#');
				$('#archivo_xls').hide();
				$('#archivo_pdf a').attr('href', '#');
				$('#archivo_pdf').hide();
			}
			cargar_tabla(datos_json);
		}
	});
}

//-----------------------------------------------------------------------------

function buscar_usuario(cedula)
{
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/con_cedula/'+cedula,
		dataType: 'text',
		cache: false,
		async: false,
		success: function(tabla_resultados)
		{
			if ( tabla_resultados != 'false' )
			{
				$('#total_registros').html('').hide();
				$('#archivo_xls a').attr('href', '#');
				$('#archivo_xls').hide();
				
				// las cargamos en la página.
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#info_usuario').html(tabla_resultados).show();
				$('#error_consulta').html('Se encontró el usuario.').show();
			}
			else
			{
				$('#total_registros').html('').hide();
				$('#archivo_xls a').attr('href', '#');
				$('#archivo_xls').hide();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#info_usuario').html('').hide();
				$('#error_consulta').html('No se encontró el usuario.').show();
			}
		}
	});
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	jQuery('#select_campo').change(function()
	{
		jQuery('#select_dependencia [value=0]').attr('selected', 'selected');
		jQuery('#select_tipo_usuario [value=0]').attr('selected', 'selected');
		
		// Si se selecciona "cedula del usuario"
		if ( jQuery(this).val() == 'cedula' )
		{
			jQuery('#select_dependencia').attr('disabled', 'true');
			jQuery('#select_tipo_usuario').attr('disabled', 'true');
		}
		else
		{
			jQuery('#select_dependencia').removeAttr('disabled');
			jQuery('#select_tipo_usuario').removeAttr('disabled');
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#select_dependencia').change(function()
	{
		// Si se selecciona alguna dependencia.
		if ( jQuery(this).val() != '0' )
		{
			jQuery('#select_tipo_usuario [value=3]').attr('selected', 'selected');
			jQuery('#select_tipo_usuario').removeAttr('disabled');
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#select_tipo_usuario').change(function()
	{
		// Si se selecciona alguno de los admins.
		if ( jQuery(this).val() != '3' )
		{
			jQuery('#select_dependencia [value=0]').attr('selected', 'selected');
			jQuery('#select_dependencia').attr('disabled', 'true');
		}
		else
		{
			jQuery('#select_dependencia').removeAttr('disabled');
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#boton_buscar_usuarios').click(function()
	{
		jQuery('#busqueda').val(jQuery.trim(jQuery('#busqueda').val()));
		var frase_busqueda = jQuery('#busqueda').val();
		var criterio_campo = jQuery('#select_campo').val();
		var criterio_dependencia = jQuery('#select_dependencia').val();
		var criterio_tipo_usuario = jQuery('#select_tipo_usuario').val();
		
		if ( criterio_campo == 'cedula' )
		{
			// Validamos "Usu_cedula" en el campo BUSCAR.
			if ( frase_busqueda == '' || frase_busqueda == '1' )
			{
				$('#error_cedula').html('Escribe la cédula del usuario.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#info_usuario').html('').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else if ( !es_numero(frase_busqueda) )
			{
				$('#error_cedula').html('Debes escribir un valor numérico y sin puntos.').show();
				$('#tabla_resultados tbody').html('');
				$('#resultados').hide();
				$('#info_usuario').html('').hide();
				$('#error_consulta').hide();
				$('#total_registros').html('').hide();
				$('#archivo_xls').hide();
			}
			else
			{
				$('#error_cedula').html('').hide();
				buscar_usuario(frase_busqueda);
			}
		}
		else
		{
			if ( frase_busqueda == '' )
			{
				frase_busqueda = 'null';
			}
			
			buscar_usuarios(frase_busqueda, criterio_campo, criterio_dependencia, criterio_tipo_usuario);
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#busqueda').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			jQuery('#boton_buscar_usuarios').click();
			return false;
		}
		return true;
	});
	//--------------------------------------------------------------------------
});
