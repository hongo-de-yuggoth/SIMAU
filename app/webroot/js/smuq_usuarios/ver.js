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
});
