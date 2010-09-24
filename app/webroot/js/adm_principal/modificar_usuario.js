//-----------------------------------------------------------------------------

function config_input(kp)
{
	if ( kp.which == 13 )
	{
		return false;
	}
	return true;
}

//--------------------------------------------------------------------------

function cargo_vacio()
{
	if ( jQuery('#cargo').val() == '' )
	{
		jQuery('#error_cargo').html('Escribe el cargo del usuario.').css('display', 'block');
		return true;
	}
	else
	{
		jQuery('#error_cargo').css('display', 'none');
		return false;
	}
}

//-----------------------------------------------------------------------------

function telefono_vacio()
{
	if ( jQuery('#telefono').val() == '' )
	{
		jQuery('#error_telefono').html('Escribe el teléfono del usuario.').show();
		return true;
	}
	else
	{
		jQuery('#error_telefono').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');

	jQuery('#nombre').keypress(config_input);
	jQuery('#login').keypress(config_input);
	jQuery('#clave').keypress(config_input);
	jQuery('#clave2').keypress(config_input);
	jQuery('#email').keypress(config_input);
	jQuery('#telefono').keypress(config_input);
	jQuery('#cargo').keypress(config_input);

	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	jQuery('#tipo_usuario').change(function()
	{
		// Si se selecciona el Adm de soluciones.
		if ( jQuery('#tipo_usuario').val() == '2' )
		{
			jQuery('#id_edificio').attr('disabled', 'true');
			jQuery('#id_dependencia').attr('disabled', 'true');
		}
		else if ( jQuery('#tipo_usuario').val() == '3' )
		{
			jQuery('#id_edificio').removeAttr('disabled');
			jQuery('#id_dependencia').removeAttr('disabled');
		}
	});

	// Configuramos el boton para buscar usuarios.
	jQuery('#boton_buscar_usuario').click(function()
	{
		jQuery('#cedula_buscar').val(jQuery.trim(jQuery('#cedula_buscar').val()));
		// Validamos la casilla de Cédula BUSCAR.
		if ( jQuery('#cedula_buscar').val() == '' || jQuery('#cedula_buscar').val() == '1' )
		{
			// Activamos mensaje de error.
			jQuery('#error_cedula_buscar').html('Escribe la cédula del usuario por favor.').show();
		}
		else
		{
			$.ajax(
			{
				type: "POST",
				url: '/smuq_usuarios/buscar_usuario_modificar/' + jQuery('#cedula_buscar').val(),
				dataType: 'text',
				cache: false,
				async: false,
				success: function(resultado)
				{
					jQuery('#escondidos').html(resultado);

					// Si Encontró el usuario
					if ( jQuery('#encontro').val() == 'true' )
					{
						// Leemos datos de inputs hidden y ponemos info en las casillas.
						tipo_usr = jQuery('#tipo_usuario_usuario').val();
						if ( tipo_usr == '3' )
						{
							jQuery('#div_nombre').html(jQuery('#nombre_usuario').val());
							jQuery('#div_login').html(jQuery('#login_usuario').val());
							jQuery('#clave').attr('disabled', 'true');
							jQuery('#clave2').attr('disabled', 'true');
							jQuery('#div_tipo_usuario').show();
							jQuery('#div_tipo_usuario_select').hide();
							jQuery('#div_edificio').html(jQuery('#nombre_edificio').val());
							jQuery('#div_dependencia').html(jQuery('#nombre_dependencia').val());
							jQuery('#div_nombre_input').hide();
							jQuery('#div_login_input').hide();
							jQuery('#estado_usuario option[value=1]').attr('selected', 'true');
							jQuery('#estado_usuario').attr('disabled', 'true');
						}
						else
						{
							jQuery('#div_nombre').html('');
							jQuery('#div_login').html('');
							jQuery('#div_edificio').html(jQuery('#nombre_edificio').val());
							jQuery('#div_dependencia').html(jQuery('#nombre_dependencia').val());
							jQuery('#clave').removeAttr('disabled');
							jQuery('#clave2').removeAttr('disabled');
							jQuery('#nombre').val(jQuery('#nombre_usuario').val());
							jQuery('#login').val(jQuery('#login_usuario').val());
							jQuery('#div_nombre_input').show();
							jQuery('#div_login_input').show();
							jQuery('#div_tipo_usuario').hide();
							jQuery('#tipo_usuario option[value='+tipo_usr+']').attr('selected', 'true');
							jQuery('#div_tipo_usuario_select').show();
							jQuery('#estado_usuario option[value='+jQuery('#estado_usr').val()+']').attr('selected', 'true');
							jQuery('#estado_usuario').removeAttr('disabled');
						}
						jQuery('#email').val(jQuery('#email_usuario').val());
						jQuery('#telefono').val(jQuery('#telefono_usuario').val());
						jQuery('#cargo').val(jQuery('#cargo_usuario').val());
						jQuery('#cedula').html(jQuery('#cedula_usuario').val());

						jQuery('#error_cedula_buscar').html('Cédula encontrada.').show();
						jQuery('#error_nombre').html('').hide();
						jQuery('#error_login').html('').hide();
						jQuery('#error_clave').html('').hide();
						jQuery('#error_clave2').html('').hide();
						jQuery('#error_email').html('').hide();
						jQuery('#error_telefono').html('').hide();
						jQuery('#error_cargo').html('').hide();
						jQuery('#info_usuario').slideDown('slow');
					}
					else if ( jQuery('#encontro').val() == 'false' )
					{
						// Escondemos DIV de info_usuario.
						jQuery('#info_usuario').slideUp('slow');

						jQuery('#nombre').html('');
						jQuery('#cedula').html('');
						jQuery('#login').html('');
						jQuery('#clave').val('');
						jQuery('#email').val('');
						jQuery('#telefono').val('');
						jQuery('#cargo').val('');

						jQuery('#error_cedula_buscar').html('No se encontró un usuario con esa cédula.').show();
						jQuery('#usuario').attr('action', '#');
					}
				}
			});
		}
	});

	// Validamos los datos del formulario.
	jQuery('#usuario').submit(function()
	{
		var tipo_usr = jQuery('#tipo_usuario_usuario').val();
		jQuery('#email').val(jQuery.trim(jQuery('#email').val()));
		jQuery('#cargo').val(jQuery.trim(jQuery('#cargo').val()));

		ecl = email_con_logica();
		cv = cargo_vacio();
		tv = telefono_vacio();
		if ( tipo_usr == '3' )
		{
			// Si es de CENCOS solo validamos el email y el cargo
			if ( ecl==true && cv==false && tv==false )
			{
				jQuery('#cedula_usuario').attr('name', 'data[SmuqUsuario][cedula]');
				jQuery('#email').attr('name', 'data[SmuqUsuario][email]');
				jQuery('#telefono').attr('name', 'data[SmuqUsuario][telefono]');
				jQuery('#cargo').attr('name', 'data[SmuqUsuario][cargo]');
				jQuery('#usuario').attr('action', '/smuq_usuarios/modificar');
				return true;
			}
		}
		else
		{
			// Si es de Smuq (Administrador) validamos todo.
			jQuery('#nombre').val(jQuery.trim(jQuery('#nombre').val()));
			jQuery('#login').val(jQuery.trim(jQuery('#login').val()));
			nv = nombre_vacio();
			lcl = login_con_logica('login_usuario', 'login');
			clv = claves_correctas();

			if ( nv==false && lcl==true && clv==true && ecl==true && cv==false && tv==false )
			{
				// Si hay una clave nueva, activamos variable data[][].
				if ( jQuery('#clave').val() != '' )
				{
					jQuery('#clave').attr('name', 'data[SmuqUsuario][clave]');
				}
				jQuery('#nombre').attr('name', 'data[SmuqUsuario][nombre]');
				jQuery('#cedula_usuario').attr('name', 'data[SmuqUsuario][cedula]');
				jQuery('#login').attr('name', 'data[SmuqUsuario][login]');
				jQuery('#email').attr('name', 'data[SmuqUsuario][email]');
				jQuery('#telefono').attr('name', 'data[SmuqUsuario][telefono]');
				jQuery('#tipo_usuario').attr('name', 'data[SmuqUsuario][id_grupo]');
				jQuery('#cargo').attr('name', 'data[SmuqUsuario][cargo]');
				jQuery('#estado_usuario').attr('name', 'data[SmuqUsuario][activo]');
				jQuery('#usuario').attr('action', '/smuq_usuarios/modificar');

				return true;
			}
		}
		return false;
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
