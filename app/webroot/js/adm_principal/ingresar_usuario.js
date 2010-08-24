//-----------------------------------------------------------------------------

function nombre_vacio()
{
	if ( jQuery('#nombre').val() == '' )
	{
		jQuery('#error_nombre').html('Escribe el nombre del usuario.').show();
		return true;
	}
	else
	{
		jQuery('#error_nombre').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------

function cedula_con_logica()
{
	// Primero revisamos que no esté vacia.
	if ( jQuery('#cedula').val() == '' )
	{
		jQuery('#error_cedula').html('Escribe la cédula del usuario.').show();
		return false;
	}
	else
	{
		// Ahora que sea numérico.
		if ( !es_numero(jQuery('#cedula').val()) )
		{
			jQuery('#error_cedula').html('Debes escribir un valor numérico y sin puntos.').show();
			return false;
		}
		else
		{
			// y Por último verificamos que no exista ya un usuario con esta misma cedula.
			if ( existe_cedula(jQuery('#cedula').val()) )
			{
				jQuery('#error_cedula').html('Esta cédula ya existe en el sistema.').show();
				return false;
			}
			else
			{
				jQuery('#error_cedula').html('').hide();
				return true;
			}
		}
	} 
}

//-----------------------------------------------------------------------------

function login_con_logica()
{
	// Primero revisamos que no esté vacia.
	if ( jQuery('#login').val() == '' )
	{
		jQuery('#error_login').html('Escribe el login del usuario.').show();
		return false;
	}
	else
	{
		// y Por último verificamos que no exista ya un usuario con este mismo login.
		if ( existe_login(jQuery('#login').val()) )
		{
			jQuery('#error_login').html('Este login ya está asignado a un usuario.').show();
			return false;
		}
		else
		{
			jQuery('#error_login').html('').hide();
			return true;
		}
	} 
}

//-----------------------------------------------------------------------------

function claves_correctas()
{
	// Primero revisamos que no esté vacia.
	if ( jQuery('#clave').val() == '' )
	{
		jQuery('#error_clave').html('Escribe la clave del usuario.').show();
		jQuery('#error_clave2').html('').hide();
		return false;
	}
	
	if ( jQuery('#clave2').val() == '' )
	{
		jQuery('#error_clave').html('').hide();
	  	jQuery('#error_clave2').html('Debes escribir la misma clave anterior.').show();
		return false;
	}
	
	if ( jQuery('#clave').val() != jQuery('#clave2').val() )
	{
		jQuery('#error_clave').html('').hide();
		jQuery('#error_clave2').html('No concuerda con la primera clave, las dos claves deben ser iguales.').show();
		return false;
	}
	else
	{
		jQuery('#error_clave').html('').hide();
		jQuery('#error_clave2').html('').hide();
		return true;
	}
	
}

//-----------------------------------------------------------------------------

function email_con_logica()
{
	// Primero revisamos que no esté vacia.
	if ( jQuery('#email').val() == '' )
	{
		jQuery('#error_email').html('Escribe el email del usuario.').show();
		return false;
	}
	else if ( !es_email(jQuery('#email').val()) )
	{
		jQuery('#error_email').html('Debes escribir un email válido.').show();
		return false;
	}
	else
	{
		jQuery('#error_email').html('').hide();
		return true;
	}
}

//-----------------------------------------------------------------------------

function cargo_vacio()
{
	if ( jQuery('#cargo').val() == '' )
	{
		jQuery('#error_cargo').html('Escribe el cargo del usuario.').show();
		return true;
	}
	else
	{
		jQuery('#error_cargo').hide();
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
	
function config_input(kp)
{
	if ( kp.which == 13 )
	{
		return false;
	}
	return true;
}
	
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	// Configuración inicial
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	
	jQuery('#nombre').keypress(config_input);
	jQuery('#cedula').keypress(config_input);
	jQuery('#login').keypress(config_input);
	jQuery('#clave').keypress(config_input);
	jQuery('#clave2').keypress(config_input);
	jQuery('#email').keypress(config_input);
	jQuery('#telefono').keypress(config_input);
	jQuery('#cargo').keypress(config_input);
	
	if ( jQuery('#cuadro_notificaciones').not(':hidden') )
	{
		jQuery('#cuadro_notificaciones').hide().slideDown('slow');
		jQuery('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	// Validamos los datos del formulario.
	jQuery('#usuario').submit(function()
	{
		// Validamos los datos requeridos.
		nv = nombre_vacio();
		ccl = cedula_con_logica();
		lcl = login_con_logica();
		clv = claves_correctas();
		ecl = email_con_logica();
		cv = cargo_vacio();
		tv = telefono_vacio();
		
		// Si pasa todas las validaciones hacemos el Submit.
		if ( nv==false && ccl==true && lcl==true && clv==true && ecl==true && cv==false && tv==false )
		{
			jQuery('#nombre').val(jQuery.trim(jQuery('#nombre').val()));
			jQuery('#cedula').val(jQuery.trim(jQuery('#cedula').val()));
			jQuery('#login').val(jQuery.trim(jQuery('#login').val()));
			jQuery('#email').val(jQuery.trim(jQuery('#email').val()));
			jQuery('#telefono').val(jQuery.trim(jQuery('#telefono').val()));
			jQuery('#cargo').val(jQuery.trim(jQuery('#cargo').val()));
			return true;
		}
		else
		{
			return false;
		}
	});
	//--------------------------------------------------------------------------
});
