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
	jQuery('#email').keypress(config_input);
	jQuery('#telefono').keypress(config_input);
	jQuery('#cargo').keypress(config_input);
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
		$('#email').val(jQuery.trim($('#email').val()));
		$('#telefono').val(jQuery.trim($('#telefono').val()));
		$('#cargo').val(jQuery.trim($('#cargo').val()));
		
		// Validamos los datos requeridos.
		tv = telefono_vacio();
		ecl = email_con_logica();
		
		// Si pasa todas las validaciones hacemos el Submit.
		if ( tv==false && ecl==true )
		{
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
