function login_vacio()
{
	if ( $('#login_usr').val() == '' )
	{
		$('#error_login').html('Te falta escribir el login.').show();
		return true;
	}
	else
	{
		$('#error_login').html('').hide();
		return false;
	}
}

//--------------------------------------------------------------------------

function clave_vacio()
{
	if ( $('#clave').val() == '' )
	{
		$('#error_clave').html('Te falta escribir la clave.').show();
		return true;
	}
	else
	{
		$('#error_clave').html('').hide();
		return false;
	}
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	if ( jQuery('#cuadro_notificaciones').not(':hidden') )
	{
		jQuery('#cuadro_notificaciones').hide().slideDown('slow');
		jQuery('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	// Validamos los datos del formulario.
	jQuery('#boton_entrar').click(function()
	{
		jQuery('#login_usr').val(jQuery.trim(jQuery('#login_usr').val()));
		jQuery('#clave').val(jQuery.trim(jQuery('#clave').val()));
		
		// Validamos los datos requeridos.
		var lv = login_vacio();
		var cv = clave_vacio();
		
		// Si pasa todas las validaciones hacemos el Submit.
		if ( lv==false && cv==false )
		{
			jQuery('#login_usuario').submit();
		}
	});
});
