//-----------------------------------------------------------------------------

function actualizar_param_anios()
{
	var valor_select = jQuery('#param_años').val();
	if ( valor_select == 'todos' )
	{
		jQuery('#div_rango_años').hide();
	}
	else if ( valor_select == 'rango' )
	{
		jQuery('#div_rango_años').show();
	}
}

//--------------------------------------------------------------------------

function indexOf(array, s)
{
	for ( var x=0; x < array.length; x++ )
	{
		if ( array[x] == s )
			return x;
	}
	return false;
}

//-----------------------------------------------------------------------------

function construir_select_anio_final(anio_inicial)
{
	var temp = jQuery('#listado_años').val();
	var anios = temp.split(',');
	var html = '';
	for ( var i = indexOf(anios, anio_inicial); i < anios.length; i++ )
	{
		html += '<option value="'+anios[i]+'">'+anios[i]+'</option>';
	}
	jQuery('#año_final').html(html);
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	construir_select_anio_final(jQuery('#año_inicial').val());
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	jQuery('#boton_cargar_reporte').click(function()
	{
		var parametro = '/';
		var reporte_seleccionado = jQuery('#select_reporte').val();
		if ( reporte_seleccionado == 'servicios_años' )		// rep + 3 params
		{
			var param_anios = jQuery('#param_años').val();
			parametro = parametro+param_anios;
			if ( param_anios == 'rango' )
			{
				parametro = parametro+'/'+jQuery('#año_inicial').val()+'/'+jQuery('#año_final').val();
			}
		}
		else if ( reporte_seleccionado == 'servicios_meses' )	// rep + 1 params
		{
			parametro = parametro+jQuery('#año_meses').val();
		}
		else if ( reporte_seleccionado == 'solicitudes_dependencia_meses' )
		{
			parametro = parametro+jQuery('#oficina').val()+'/'+jQuery('#año_meses').val();
		}
		else if ( reporte_seleccionado == 'solicitudes_tecnico_años' )	// rep + 2 params
		{
			parametro = parametro+jQuery('#operarios').val()+'/'+jQuery('#año_meses').val();
		}
		else if ( reporte_seleccionado == 'costo_externo_interno_año' )
		{
			parametro = parametro+jQuery('#oficina_costos').val()+'/'+jQuery('#año_meses').val();
		}
		
		$.ajax(
		{
			type: "POST",
			url: '/reportes_estadisticos/chequear/'+reporte_seleccionado+parametro,
			dataType: 'text',
			cache: false,
			async: false,
			success: function(resultado)
			{
				if ( resultado == 'true' )
				{
					jQuery('#error_consulta').hide();
					jQuery('#img_reporte').attr('src', '/reportes_estadisticos/'+reporte_seleccionado+parametro);
					jQuery('#img_reporte').show();
				}
				else
				{
					jQuery('#img_reporte').hide();
					if ( reporte_seleccionado == 'solicitudes_tecnico_años' )
					{
						jQuery('#error_consulta').html('No se encontraron solicitudes solucionadas por este técnico.').show();
					}
					else
					{
						jQuery('#error_consulta').html('No se encontraron solicitudes de esta dependencia').show();
					}
				}
			}
		});
		jQuery('#reporte').show();
	});
	//--------------------------------------------------------------------------
	jQuery('#select_reporte').change(function()
	{
		var reporte_seleccionado = jQuery(this).val();
		if ( reporte_seleccionado == 'servicios_años' )
		{
			jQuery('#div_servicios_oficina').hide();
			jQuery('#div_servicios_tecnico').hide();
			jQuery('#div_servicios_meses').hide();
			jQuery('#div_servicios_años').show();
		}
		else if ( reporte_seleccionado == 'servicios_meses' )
		{
			jQuery('#div_servicios_oficina').hide();
			jQuery('#div_servicios_tecnico').hide();
			jQuery('#div_servicios_años').hide();
			jQuery('#div_servicios_meses').show();
			jQuery('#div_meses_del_año').show();
		}
		else if ( reporte_seleccionado == 'solicitudes_tecnico_años' )
		{
			jQuery('#div_servicios_oficina').hide();
			jQuery('#div_servicios_años').hide();
			jQuery('#div_servicios_tecnico').show();
			jQuery('#div_meses_del_año').show();
			jQuery('#div_servicios_meses').show();
		}
		else if ( reporte_seleccionado == 'solicitudes_dependencia_meses' )
		{
			jQuery('#div_servicios_tecnico').hide();
			jQuery('#div_servicios_años').hide();
			jQuery('#div_oficina_costos').hide();
			jQuery('#div_servicios_meses').show();
			jQuery('#div_servicios_oficina').show();
			jQuery('#div_oficina').show();
			jQuery('#div_meses_del_año').show();
		}
		else if ( reporte_seleccionado == 'costo_externo_interno_año' )
		{
			jQuery('#div_servicios_tecnico').hide();
			jQuery('#div_servicios_años').hide();
			jQuery('#div_oficina').hide();
			jQuery('#div_servicios_meses').show();
			jQuery('#div_servicios_oficina').show();
			jQuery('#div_oficina_costos').show();
			jQuery('#div_meses_del_año').show();
		}
	});
	//--------------------------------------------------------------------------
	jQuery('#param_años').change(actualizar_param_anios);
	//--------------------------------------------------------------------------
	jQuery('#año_inicial').change(function()
	{
		construir_select_anio_final(jQuery(this).val());
	});
	//--------------------------------------------------------------------------
});
