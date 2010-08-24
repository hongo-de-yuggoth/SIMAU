//-----------------------------------------------------------------------------

function limpiar()
{
	$('#equipo_confirmado').val('');
	$('#placa_equ').html('');
	$('#nombre_equ').html('');
	$('#modelo_equ').html('');
	$('#marca_equ').html('');
}

//-----------------------------------------------------------------------------

function validar_tipo_servicio()
{
	if ( jQuery('#mp').attr('checked') || jQuery('#mc').attr('checked') || jQuery('#cc').attr('checked') )
	{
		return true;
	}
	return false;
}

//-----------------------------------------------------------------------------

$(document).ready(function()
{
	// Configuración inicial
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	
	$("#definiciones div.content").hide();
	$("#definiciones div.content:first").show();
	$("#definiciones h4").bind("click", function()
	{
		if ( $(this).next().css("display") == 'none' )
		{
			$("#definiciones div.content").hide();
			$(this).next().slideDown(250);
		}
	});
	
	$('#mp').attr('checked', 'true');
	
	if ( $('#cuadro_notificaciones').not(':hidden') )
	{
		$('#cuadro_notificaciones').hide().slideDown('slow');
		$('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	// Configuramos el boton para buscar equipos.
	$('#boton_buscar_equipo').click(function()
	{
		// Validamos la casilla de Placa de Inventario.
		if ( $('#placa_inventario').val() == '' )
		{
			// Activamos mensaje de error.
			$('#error_placa').html('Escribe una placa de inventario por favor.').show();
		}
		else
		{
			// Eliminamos espacios blancos en los campos del formulario a enviar
			$('#placa_inventario').val(jQuery.trim($('#placa_inventario').val()));
			$.ajax(
			{
				type: "POST",
				url: '/equipos/buscar_equipo_ajax/'+$('#placa_inventario').val()+'/'+$('#h_cedula_usuario').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(resultado)
				{
					$('#escondidos').html(resultado);
					// Si Encontró el equipo
					if ( $('#encontro').val() == 'true' )
					{
						// Leemos datos de inputs hidden y ponemos info en las casillas.
						$('#nombre_equ').html($('#nombre_equipo').val());
						$('#marca_equ').html($('#marca_equipo').val());
						$('#modelo_equ').html($('#modelo_equipo').val());
						$('#placa_equ').html($('#equipo_confirmado').val());
						$('#error_placa').html('Placa de inventario encontrada.').show();
					}
					else if ( $('#encontro').val() == 'false' )
					{
						// Blanqueamos las casillas
						limpiar();
						$('#error_placa').html('Este usuario no tiene asignado un equipo con esa placa de inventario.').show();
					}
				}
			});
		}
	});
	
	$('#solicitud_mantenimiento').submit(function()
	{
		var ec = true;
		var desc = true;
		var tipo_serv = true;
		var cadena = ',';
		
		$('#descripcion').val(jQuery.trim($('#descripcion').val()));
		$('#observaciones').val(jQuery.trim($('#observaciones').val()));
		
		// Debemos revisar que haya un equipo confirmado, osea que se halla hecho la busqueda de algun equipo
		// y que lo haya encontrado. Este se almacena en el Hidden Input "equiop_confirmado".
		if ( $('#equipo_confirmado').val() == '' )
		{
			$('#error_placa').html('Debes buscar el equipo que requiere el servicio.').show();
			ec = false;
		}
		else
		{
			$('#error_placa').html('').hide();
		}
		
		if ( $('#descripcion').val() == '' )
		{
			$('#error_descripcion').html('Debes proporcionar una descripción del servicio solicitado.').show();
			desc = false;
		}
		else
		{
			$('#error_descripcion').html('').hide();
		}
		
		// Validamos ke se seleccione al menos un tipo de servicio.
		if ( !validar_tipo_servicio() )
		{
			jQuery('#error_tipo_servicio').html('Debes seleccionar al menos un servicio.').show();
			tipo_serv = false;
		}
		else
		{
			jQuery('#error_tipo_servicio').html('').hide();
			
			// Armamos la cadena para el data[Solicitud][tipo_servicio]
			if ( jQuery('#mp').attr('checked') )
			{
				cadena = '1';
			}
			if ( jQuery('#mc').attr('checked') )
			{
				if ( jQuery('#mp').attr('checked') )
				{
					cadena = cadena + ',2';
				}
				else
				{
					cadena = '2';
				}
			}
			if ( jQuery('#cc').attr('checked') )
			{
				if ( jQuery('#mp').attr('checked') || jQuery('#mc').attr('checked') )
				{
					cadena = cadena + ',3';
				}
				else
				{
					cadena = '3';
				}
			}
			jQuery('#h_tipo_servicio').val(cadena);
		}
		
		if ( ec==true && desc==true && tipo_serv==true )
		{
			return true;
		}
		else
		{
			return false;
		}
	});
	jQuery('#placa_inventario').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			jQuery('#boton_buscar_equipo').click();
			return false;
		}
		return true;
	});
});
