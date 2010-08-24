function costo_externo_numero()
{
	$('#costo_externo').val(jQuery.trim($('#costo_externo').val()));
	
	if ( $('#costo_externo').val() == '' )
	{
		$('#error_costo_externo').html('').hide();
		return true;
	}
	else if ( !es_numero($('#costo_externo').val()) )
	{
		$('#error_costo_externo').html('Escribe un valor numérico sin puntos.').show();
		return false;
	}
	else
	{
		$('#error_costo_externo').html('').hide();
		return true;
	}
}

//-----------------------------------------------------------------------------

function costo_interno_numero()
{
	$('#costo_interno').val(jQuery.trim($('#costo_interno').val()));
	
	if ( $('#costo_interno').val() == '' )
	{
		$('#error_costo_interno').html('').hide();
		return true;
	}
	else if ( !es_numero($('#costo_interno').val()) )
	{
		$('#error_costo_interno').html('Escribe un valor numérico sin puntos.').show();
		return false;
	}
	else
	{
		$('#error_costo_interno').html('').hide();
		return true;
	}
}

//-----------------------------------------------------------------------------

function costo_externo_vacio()
{
	$('#costo_externo').val(jQuery.trim($('#costo_externo').val()));
	
	if ( $('#costo_externo').val() == '' )
	{
		$('#error_costo_externo').html('Escribe el costo externo.').show();
		return true;
	}
	else if ( !es_numero($('#costo_externo').val()) )
	{
		$('#error_costo_externo').html('Escribe un valor numérico sin puntos.').show();
		return true;
	}
	else
	{
		$('#error_costo_externo').html('').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------

function costo_interno_vacio()
{
	$('#costo_interno').val(jQuery.trim($('#costo_interno').val()));
	
	if ( $('#costo_interno').val() == '' )
	{
		$('#error_costo_interno').html('Escribe el costo interno.').show();
		return true;
	}
	else if ( !es_numero($('#costo_interno').val()) )
	{
		$('#error_costo_interno').html('Escribe un valor numérico sin puntos.').show();
		return true;
	}
	else
	{
		$('#error_costo_interno').html('').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------

function contratista_vacio()
{
	$('#contratista').val(jQuery.trim($('#contratista').val()));
	
	if ( $('#contratista').val() == '' || es_numero($('#contratista').val()) )
	{
		$('#error_contratista').html('Escribe el contratista.').show();
		return true;
	}
	else
	{
		$('#error_contratista').html('').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------

function repuestos_mano_obra_vacio()
{
	$('#repuestos_mano_obra').val(jQuery.trim($('#repuestos_mano_obra').val()));
	
	if ( $('#repuestos_mano_obra').val() == '' || es_numero($('#repuestos_mano_obra').val()) )
	{
		$('#error_repuestos').html('Escribe los repuestos requeridos y/o la mano de obra realizada.').show();
		return true;
	}
	else
	{
		$('#error_repuestos').html('').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

$(document).ready(function()
{
	// Configuración inicial
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	if ( $('#cuadro_notificaciones').not(':hidden') )
	{
		$('#cuadro_notificaciones').hide().slideDown('slow');
		$('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}
	
	if ($('#estado').html() == 'p' )
	{
		if ( $('#id_grupo_usuario').val() != '3' )
		{
			// Activamos los TextAreas e Inputs.
			$('#contratista').attr('name', 'data[Solicitud][contratista]');
			$('#contratista').val($('#nombre_contratista').val());
			$('#div_contratista').hide();
			$('#div_contratista_input').show();
			
			$('#repuestos_mano_obra').attr('name', 'data[Solicitud][repuestos_mano_obra]');
			$('#repuestos_mano_obra').html($('#repuestos').val());
			$('#div_repuestos_mano_obra').hide();
			$('#div_repuestos_mano_obra_textarea').show();
			
			$('#costo_externo').attr('name', 'data[Solicitud][costo_externo]');
			$('#costo_externo').val($('#cst_externo').val());
			$('#div_costo_externo').hide();
			$('#div_costo_externo_input').show();
			
			$('#costo_interno').attr('name', 'data[Solicitud][costo_interno]');
			$('#costo_interno').val($('#cst_interno').val());
			$('#div_costo_interno').hide();
			$('#div_costo_interno_input').show();
			
			$('#observaciones_solucion').attr('name', 'data[Solicitud][observaciones_solucion]');
			$('#observaciones_solucion').html($('#observaciones').val());
			$('#div_observaciones_solucion').hide();
			$('#div_observaciones_solucion_textarea').show();
			
			$('#botones_solucion').show();
			$('#div_solucion').show();
		}
		
		$('#estado').html('Pendiente').attr('style', 'color:red').show();
	}
	else if ($('#estado').html() == 's' )
	{
		// Ponemos los TEXTOS en los divs de Solución
		$('#div_nombre_adm_sol').html($('#nombre_adm_sol').val());
		$('#div_solucionado_por').show();
		$('#div_contratista').html($('#nombre_contratista').val());
		$('#div_repuestos_mano_obra').html($('#repuestos').val()).addClass('div_solucion');
		$('#div_costo_externo').html('$'+$('#cst_externo').val());
		$('#div_costo_interno').html('$'+$('#cst_interno').val());
		$('#div_observaciones_solucion').html($('#observaciones').val()).addClass('div_solucion');
		$('#div_solucion').show();
		
		$('#estado').html('Solucionado').attr('style', 'color:green').show();
	}
	
	$('#boton_guardar').click(function()
	{
		ce = costo_externo_numero();
		ci = costo_interno_numero();
		
		// Si pasa todas las validaciones hacemos el Submit.
		if ( ce==true && ci==true )
		{
			$('#contratista').val(jQuery.trim($('#contratista').val()));
			$('#repuestos_mano_obra').val(jQuery.trim($('#repuestos_mano_obra').val()));
			$('#observaciones_solucion').val(jQuery.trim($('#observaciones_solucion').val()));

			$('#solucion').attr('action', '/solicitudes/guardar_solucion').submit();
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_solucionar').click(function()
	{
		conv = contratista_vacio();
		rmov = repuestos_mano_obra_vacio();
		cev = costo_externo_vacio();
		civ = costo_interno_vacio();
		
		// Si pasa todas las validaciones hacemos el Submit.
		if ( cev==false && civ==false && conv==false && rmov==false )
		{
			$('#observaciones_solucion').val(jQuery.trim($('#observaciones_solucion').val()));
			
			if ( confirm('¿Está seguro de querer dar por solucionada esta solicitud?') )
			{
				jQuery('#cedula_usr_autenticado').attr('name', 'data[Solicitud][cedula_adm_sol]');
				$('#solucion').attr('action', '/solicitudes/solucionar').submit();
			}
		}
	});
});
