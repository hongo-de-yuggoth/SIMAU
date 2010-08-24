//-----------------------------------------------------------------------------

function cargar_edificios()
{
	$.ajax(
	{
		type: "POST",
		url: '/edificios/cargar_select/',
		dataType: 'text',
		cache: false,
		async: false,
		success: function(opciones_select)
		{
			if ( opciones_select != '' )
			{
				$('#id_edificio_modificar').html(opciones_select);
				$('#id_edificio_eliminar').html(opciones_select);
			}
		}
	});
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

$(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	cargar_edificios();
	$('#nombre_edificio_modificar').val($('#id_edificio_modificar option[value='+$('#id_edificio_modificar').val()+']').html());
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	$('#boton_crear_edificio').click(function()
	{
		if ( $('#nombre_edificio_crear').val() == '' )
		{
			// Activamos mensaje de error.
			$('#error_edificio_crear').html('Escribe un nombre por favor.').css('display', 'block');
		}
		else
		{
			$('#nombre_edificio_crear').val(jQuery.trim($('#nombre_edificio_crear').val()));
			$.ajax(
			{
				type: "POST",
				url: '/edificios/existe_edificio/' + $('#nombre_edificio_crear').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(existe_edificio)
				{
					if ( existe_edificio == 'false' )
					{
						// Si no existe -> creamos el edificio.
						$.ajax(
						{
							type: "POST",
							url: '/edificios/crear/' + $('#nombre_edificio_crear').val(),
							dataType: 'text',
							cache: false,
							async: false,
							success: function(resultado)
							{
								if ( resultado == 'true' )
								{
									$('#error_edificio_crear').html('El edificio fué creado.').css('display', 'block');
									$('#nombre_edificio_crear').val('');
									
									// se recarga los selects de edificio.
									cargar_edificios();
								}
								else
								{
									$('#error_edificio_crear').html('El edificio no pudo ser creado.').css('display', 'block');
								}
								
								$('#error_edificio_modificar').html('').css('display', 'none');
								$('#error_edificio_eliminar').html('').css('display', 'none');
							}
						});
					}
					else if ( existe_edificio == 'true' )
					{
						$('#error_edificio_crear').html('Este edificio ya existe.').css('display', 'block');
					}
				}
			});
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#id_edificio_modificar').change(function ()
	{
		$('#nombre_edificio_modificar').val($('#id_edificio_modificar option[value='+$('#id_edificio_modificar').val()+']').html());
		$('#error_edificio_modificar').html('').css('display', 'none');
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_modificar_edificio').click(function()
	{
		if ( $('#nombre_edificio_modificar').val() == '' )
		{
			// Activamos mensaje de error.
			$('#error_edificio_modificar').html('Escribe un nombre por favor.').css('display', 'block');
		}
		else if ( $('#nombre_edificio_modificar').val() != $('#id_edificio_modificar option[value='+$('#id_edificio_modificar').val()+']').html() )
		{
			$('#nombre_edificio_modificar').val(jQuery.trim($('#nombre_edificio_modificar').val()));
			
			// Si se cambió el nombre...
			// Verificamos que el nuevo no exista ya...
			$.ajax(
			{
				type: "POST",
				url: '/edificios/existe_edificio/'+$('#nombre_edificio_modificar').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(existe_edificio)
				{
					if ( existe_edificio == 'false' )
					{
						// Si no existe -> modificamos el nombre del edificio.
						$.ajax(
						{
							type: "POST",
							url: '/edificios/modificar/'+$('#id_edificio_modificar').val()+'/'+$('#nombre_edificio_modificar').val(),
							dataType: 'text',
							cache: false,
							async: false,
							success: function(resultado)
							{
								if ( resultado == 'true' )
								{
									$('#error_edificio_modificar').html('El edificio fué modificado.').css('display', 'block');
									$('#nombre_edificio_modificar').val('');
									
									// se recarga los selects de edificio.
									cargar_edificios();
								}
								else if ( resultado == 'false' )
								{
									$('#error_edificio_modificar').html('El edificio no pudo ser modificado.').css('display', 'block');
								}
								
								$('#error_edificio_crear').html('').css('display', 'none');
								$('#error_edificio_eliminar').html('').css('display', 'none');
							}
						});
					}
					else if ( existe_edificio == 'true' )
					{
						$('#error_edificio_modificar').html('Este edificio ya existe, elige otro nombre.').css('display', 'block');
					}
				}
			});
		}
	});

	//--------------------------------------------------------------------------
	
	$('#boton_eliminar_edificio').click(function()
	{
		if ( confirm('¿Realmente desea eliminar este edificio?') )
		{
			$.ajax(
			{
				type: "POST",
				url: '/edificios/eliminar/'+$('#id_edificio_eliminar').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(resultado)
				{
					if ( resultado == 'true' )
					{
						$('#error_edificio_eliminar').html('Se ha eliminado el edificio.').css('display', 'block');
						cargar_edificios();
					}
					else if ( resultado == 'false' )
					{
						$('#error_edificio_eliminar').html('No se pudo eliminar este edificio.').css('display', 'block');
					}
					
					$('#error_edificio_crear').html('').css('display', 'none');
					$('#error_edificio_modificar').html('').css('display', 'none');
				}
			});
		}
	});

	//--------------------------------------------------------------------------
	
	jQuery('#nombre_edificio_crear').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			if ( jQuery(this).val() != '' )
			{
				jQuery('#boton_crear_edificio').click();
			}
			return false;
		}
		return true;
	});
	
	//--------------------------------------------------------------------------
	
	jQuery('#nombre_edificio_modificar').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			if ( jQuery(this).val() != '' )
			{
				jQuery('#boton_modificar_edificio').click();
			}
			return false;
		}
		return true;
	});
	
	//--------------------------------------------------------------------------
});
