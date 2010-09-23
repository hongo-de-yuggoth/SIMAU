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

$(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	jQuery('#nombre').keypress(config_input);
	jQuery('#login').keypress(config_input);
	jQuery('#clave').keypress(config_input);
	jQuery('#clave2').keypress(config_input);
	jQuery('#email').keypress(config_input);
	jQuery('#telefono').keypress(config_input);
	if ( $('#cuadro_notificaciones').not(':hidden') )
	{
		$('#cuadro_notificaciones').hide().slideDown('slow');
		$('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}

	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.

	// Validamos los datos del formulario.
	$('#usuario').submit(function()
	{
		$('#nombre').val(jQuery.trim($('#nombre').val()));
		$('#login').val(jQuery.trim($('#login').val()));
		$('#email').val(jQuery.trim($('#email').val()));
		$('#telefono').val(jQuery.trim($('#telefono').val()));
		$('#cargo').val(jQuery.trim($('#cargo').val()));

		// Validamos los datos requeridos.
		nv = nombre_vacio();
		tv = telefono_vacio();
		lcl = login_con_logica('h_login', 'login');
		clv = claves_correctas();
		ecl = email_con_logica();

		// Si pasa todas las validaciones hacemos el Submit.
		if ( nv==false && tv==false && lcl==true && clv==true && ecl==true )
		{
			// Si hay una clave nueva, activamos variable data[][].
			if ( $('#clave').val() != '' )
			{
				$('#clave').attr('name', 'data[SmuqUsuario][clave]');
			}

			jQuery('#h_cedula').attr('name', 'data[SmuqUsuario][cedula]');
			$('#usuario').attr('action', '/smuq_usuarios/modificar');
			return true;
		}
		else
		{
			return false;
		}
	});
	//--------------------------------------------------------------------------
});
