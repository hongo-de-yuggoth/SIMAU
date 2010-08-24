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
});
