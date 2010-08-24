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
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.

	// Configuramos el boton para buscar usuarios.
	$('#boton_buscar_usuario').click(function()
	{
		$('#cedula_buscar').val(jQuery.trim($('#cedula_buscar').val()));
		
		// Validamos la casilla de Cédula BUSCAR.
		if ( $('#cedula_buscar').val() == '' || $('#cedula_buscar').val() == '1' )
		{
			// Activamos mensaje de error.
			$('#error_cedula_buscar').html('Escribe la cédula del usuario por favor.').show();
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: '/smuq_usuarios/buscar_usuario_eliminar/'+$('#cedula_buscar').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(resultado)
				{
					$('#escondidos').html(resultado);
					
					// Si Encontró el equipo
					if ( $('#encontro').val() == 'true' )
					{
						// Leemos datos de inputs hidden y ponemos info en los divs de info.
						$('#nombre').html($('#nombre_usuario').val());
						$('#cedula').html($('#cedula_usuario').val());
						$('#login').html($('#login_usuario').val());
						$('#email').html($('#email_usuario').val());
						$('#telefono').html($('#telefono_usuario').val());
						$('#cargo').html($('#cargo_usuario').val());
						$('#edificio').html($('#nombre_edificio').val());
						$('#nombre_dependencia_div').html($('#nombre_dependencia').val());
						$('#error_cedula_buscar').html('Usuario administrador encontrado.').show();
						
						if ( $('#tipo_usuario_usuario').val() == '3' )
						{
							$('#tipo_usuario').html('Usuario de Dependencia');
						}
						else if ( $('#tipo_usuario_usuario').val() == '2' )
						{
							$('#tipo_usuario').html('Administrador de Soluciones');
						}
						else if ( $('#tipo_usuario_usuario').val() == '1' )
						{
							$('#tipo_usuario').html('Administrador Principal');
						}
						
						$('#info_usuario').slideDown('slow');
					}
					else if ( $('#encontro').val() == 'false' )
					{
						// Escondemos DIV de info_usuario.
						$('#info_usuario').slideUp('slow');
						
						$('#nombre').html('');
						$('#cedula').html('');
						$('#login').html('');
						$('#email').html('');
						$('#cargo').html('');
						$('#tipo_usuario').html('');
						$('#edificio').html('');
						$('#nombre_dependencia_div').html('');
						
						$('#error_cedula_buscar').html('No se encontró un usuario administrador con esa cédula.').show();
						$('#usuario').attr('action', '#');
					}
				}
			});
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#usuario').submit(function()
	{
		if ( $('#encontro').val() == 'true' )
		{
			if ( confirm('¿Realmente desea eliminar este usuario?') )
			{
				$('#cedula_usuario').attr('name', 'data[SmuqUsuario][cedula]');
				$('#usuario').attr('action', '/smuq_usuarios/eliminar');
				return true;
			}
			return false;
		}
		else
		{
			return false;
		}
	});
	//--------------------------------------------------------------------------
	
	jQuery('#cedula_buscar').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			if ( jQuery(this).val() != '' )
			{
				jQuery('#boton_buscar_usuario').click();
			}
			return false;
		}
		return true;
	});
	
	//--------------------------------------------------------------------------
});
