function limpiar_errores()
{
	jQuery('#error_fecha_recibido_satisfaccion').html('').hide();
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	// Configuración inicial
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');

	if ( jQuery('#cuadro_notificaciones').not(':hidden') )
	{
		jQuery('#cuadro_notificaciones').hide().slideDown('slow');
		jQuery('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}

	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	jQuery('#boton_cal_2').click(function()
	{
		displayCalendar(jQuery('#fecha_recibido_satisfaccion').get(0),'yyyy-mm-dd',jQuery('#boton_cal_2').get(0));
	});

	//--------------------------------------------------------------------------

	// Configuramos el boton para buscar equipos.
	jQuery('#boton_buscar_equipo').click(function()
	{
		// Validamos la casilla de Placa de Inventario BUSCAR.
		if ( jQuery('#placa_inventario_buscar').val() == '' )
		{
			// Activamos mensaje de error.
			jQuery('#error_placa_buscar').html('Escribe una placa de inventario por favor.').show();
		}
		else
		{
			jQuery('#placa_inventario_buscar').val(jQuery.trim(jQuery('#placa_inventario_buscar').val()));
			jQuery('#escondidos').load(
				'/equipos/buscar_equipo_modificar/' + jQuery('#placa_inventario_buscar').val(),
				function()
				{
					// Si Encontró el equipo
					if ( jQuery('#encontro').val() == 'true' )
					{
						limpiar_errores();

						// Leemos datos de inputs hidden y ponemos info en las casillas.
						jQuery('#nombre').html(jQuery('#name_equipo').val());
						jQuery('#marca').html(jQuery('#marca_equipo').val());
						jQuery('#modelo').html(jQuery('#modelo_equipo').val());
						jQuery('#placa_inventario_div').html(jQuery('#placa_inventario_equipo').val());
						jQuery('#placa_inventario').val(jQuery('#placa_inventario_equipo').val());
						jQuery('#responsable').html(jQuery('#usuario_equipo').val());
						jQuery('#cargo').html(jQuery('#cargo_equipo').val());
						jQuery('#edificio').html(jQuery('#edificio_equipo').val());
						jQuery('#dependencia').html(jQuery('#dependencia_equipo').val());
						jQuery('#valor_compra').html('$'+jQuery('#valor_compra_equipo').val());
						jQuery('#fecha_compra').html(jQuery('#fecha_compra_equipo').val());
						jQuery('#fecha_recibido_satisfaccion').val(jQuery('#fecha_recibido_equipo').val());

						if ( jQuery('#nombre_foto_equipo').val() != '' )
						{
							// Cargamos foto_THUMB en IMG
							jQuery('#link_foto').attr('href', '/equipos/fotos/'+jQuery('#nombre_foto_eliminar').val());
							jQuery('#foto_equipo a img').attr('src', jQuery('#nombre_foto_equipo').val());
							jQuery('#div_foto').show();
						}
						else
						{
							jQuery('#link_foto').attr('href', '');
							jQuery('#foto_equipo a img').attr('src', '');
							jQuery('#div_foto').hide();
						}

						jQuery('#error_placa_buscar').html('Placa de inventario encontrada.').show();
						jQuery('#info_equipo').slideDown('slow');
					}
					else if ( jQuery('#encontro').val() == 'false' )
					{
						// Escondemos DIV de info_equipo.
						jQuery('#info_equipo').slideUp('slow');

						jQuery('#nombre').html('');
						jQuery('#marca').html('');
						jQuery('#modelo').html('');
						jQuery('#placa_inventario').html('');
						jQuery('#cedula_responsable').html('');
						jQuery('#dependencia').html('');
						jQuery('#valor_compra').html('');
						jQuery('#fecha_compra').html('');
						jQuery('#fecha_recibido').val('');

						limpiar_errores();

						jQuery('#error_placa_buscar').html('No se encontró un equipo con esa placa de inventario.').css('display', 'block');
						jQuery('#equipo').attr('action', '#');
					}
				}
			);
		}
	});

	//--------------------------------------------------------------------------

	// Configuramos el boton para borrar foto.
	jQuery('#boton_borrar_foto').click(function()
	{
		$.ajax(
		{
			type: "POST",
			url: '/equipos/borrar_foto/' + jQuery('#placa_inventario_equipo').val(),
			dataType: 'text',
			cache: false,
			async: false,
			success: function(resultado)
			{
				if ( resultado == 'true' )
				{
					jQuery('#div_foto').fadeOut(2000, function()
					{
						jQuery('#div_msj_foto').show().fadeTo(5000, 1).fadeOut(2000, function()
						{
							jQuery('#foto_equipo img').attr('src', '');
							jQuery('#nombre_foto_equipo').attr('src', '');
						});
					});

				}
			}
		});
	});

	//--------------------------------------------------------------------------

	jQuery('#equipo').submit(function()
	{
		jQuery('#equipo').attr('action', '/equipos/modificar');
		return true;
	});
	//--------------------------------------------------------------------------

	jQuery('#placa_inventario_buscar').keypress(function(kp)
	{
		if ( kp.which == 13 )
		{
			if ( jQuery(this).val() != '' )
			{
				jQuery('#boton_buscar_equipo').click();
			}
			return false;
		}
		return true;
	});
});
