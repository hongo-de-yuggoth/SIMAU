//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

jQuery(document).ready(function()
{
	jQuery('div.cuerpo_menu ul #'+jQuery('#opcion_seleccionada').val()).addClass('selected');
	
	$("#tabs ul").idTabs();
	
	if ( jQuery('#cuadro_notificaciones').not(':hidden') )
	{
		$('#cuadro_notificaciones').hide().slideDown('slow');
		$('#cuadro_notificaciones').fadeTo(10000, 0.9).fadeOut(7000);
	}
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	jQuery('#select_certificados').dblclick(function()
	{
		var certificado_seleccionado = jQuery('#select_certificados').selectedTexts();
		window.open('/equipos/certificados/'+certificado_seleccionado[0]);
	});
	jQuery('#select_garantias').dblclick(function()
	{
		var garantia_seleccionada = jQuery('#select_garantias').selectedTexts();
		window.open('/equipos/garantias/'+garantia_seleccionada[0]);
	});
	jQuery('#select_manuales').dblclick(function()
	{
		var manual_seleccionado = jQuery('#select_manuales').selectedTexts();
		window.open('/equipos/manuales/'+manual_seleccionado[0]);
	});
	jQuery('#select_facturas').dblclick(function()
	{
		var factura_seleccionado = jQuery('#select_facturas').selectedTexts();
		window.open('/equipos/facturas/'+factura_seleccionado[0]);
	});
	jQuery('#select_cotizaciones').dblclick(function()
	{
		var cotizacion_seleccionado = jQuery('#select_cotizaciones').selectedTexts();
		window.open('/equipos/cotizaciones/'+cotizacion_seleccionado[0]);
	});
	
	// Configuramos el botón para buscar equipos.
	jQuery('#boton_buscar_equipo').click(function()
	{
		// Validamos la casilla de Placa de Inventario BUSCAR.
		if ( $('#placa_inventario_buscar').val() == '' )
		{
			// Activamos mensaje de error.
			$('#error_placa_buscar').html('Escribe una placa de inventario por favor.').show();
		}
		else
		{
			jQuery('#placa_inventario_buscar').val(jQuery.trim(jQuery('#placa_inventario_buscar').val()));
			jQuery.ajax(
			{
				type: "POST",
				url: '/equipos/buscar_equipo_archivos/' + $('#placa_inventario_buscar').val(),
				dataType: 'json',
				cache: false,
				async: false,
				success: function(d_json)
				{
					if ( d_json.encontro_equipo == true )
					{
						jQuery('#select_certificados').removeOption(/./);
						jQuery('#select_garantias').removeOption(/./);
						jQuery('#select_manuales').removeOption(/./);
						jQuery('#select_facturas').removeOption(/./);
						jQuery('#select_cotizaciones').removeOption(/./);
						jQuery('#nombre_archivo_certificado').val('');
						jQuery('#nombre_archivo_garantia').val('');
						jQuery('#nombre_archivo_manual').val('');
						jQuery('#nombre_archivo_facturas').val('');
						jQuery('#nombre_archivo_cotizaciones').val('');
						
						jQuery('#link_ver_equipo').attr('href', '/equipos/ver/'+d_json.equipo.placa_inventario).html('#'+d_json.equipo.placa_inventario);
						jQuery('#error_placa_buscar').html('Placa de inventario encontrada.').show();
						jQuery('#placa_inventario_equipo').val(d_json.equipo.placa_inventario);
						jQuery('#id_equipo').val(d_json.equipo.id);
						
						if ( d_json.certificados.length > 0 )
						{
							// Cargamos el SelectBOX de Certificados
							for ( var i=0; i < d_json.certificados.length; i++ )
							{
								jQuery('#select_certificados').addOption(d_json.certificados[i].id, d_json.certificados[i].nombre_archivo);
							}
							
							jQuery('#msj_select').hide();
							jQuery('#cuadro_eliminar_certificado').show();
						}
						else
						{
							jQuery('#select_certificados').removeOption(/./);
							jQuery('#cuadro_eliminar_certificado').hide();
							jQuery('#msj_select').show();
						}
						
						if ( d_json.garantias.length > 0 )
						{
							// Cargamos el SelectBOX de Garantías
							for ( var i=0; i < d_json.garantias.length; i++ )
							{
								jQuery('#select_garantias').addOption(d_json.garantias[i].id, d_json.garantias[i].nombre_archivo);
							}
							
							jQuery('#msj_select_gara').hide();
							jQuery('#cuadro_eliminar_garantia').show();
						}
						else
						{
							jQuery('#select_garantias').removeOption(/./);
							jQuery('#cuadro_eliminar_garantia').hide();
							jQuery('#msj_select_gara').show();
						}
						
						if ( d_json.manuales.length > 0 )
						{
							// Cargamos el SelectBOX de Manuales
							for ( var i=0; i < d_json.manuales.length; i++ )
							{
								jQuery('#select_manuales').addOption(d_json.manuales[i].id, d_json.manuales[i].nombre_archivo);
							}
							
							jQuery('#msj_select_manu').hide();
							jQuery('#cuadro_eliminar_manual').show();
						}
						else
						{
							jQuery('#select_manuales').removeOption(/./);
							jQuery('#cuadro_eliminar_manual').hide();
							jQuery('#msj_select_manu').show();
						}
						
						if ( d_json.facturas.length > 0 )
						{
							// Cargamos el SelectBOX de Facturas
							for ( var i=0; i < d_json.facturas.length; i++ )
							{
								jQuery('#select_facturas').addOption(d_json.facturas[i].id, d_json.facturas[i].nombre_archivo);
							}
							
							jQuery('#msj_select_fact').hide();
							jQuery('#cuadro_eliminar_factura').show();
						}
						else
						{
							jQuery('#select_facturas').removeOption(/./);
							jQuery('#cuadro_eliminar_factura').hide();
							jQuery('#msj_select_fact').show();
						}
						
						if ( d_json.cotizaciones.length > 0 )
						{
							// Cargamos el SelectBOX de Cotizaciones
							for ( var i=0; i < d_json.cotizaciones.length; i++ )
							{
								jQuery('#select_cotizaciones').addOption(d_json.cotizaciones[i].id, d_json.cotizaciones[i].nombre_archivo);
							}
							
							jQuery('#msj_select_coti').hide();
							jQuery('#cuadro_eliminar_cotizacion').show();
						}
						else
						{
							jQuery('#select_cotizaciones').removeOption(/./);
							jQuery('#cuadro_eliminar_cotizacion').hide();
							jQuery('#msj_select_coti').show();
						}
						
						$('#cuadro_archivos').show();
					}
					else
					{
						// Escondemos DIV de info_equipo.
						$('#cuadro_archivos').slideUp('slow');
						$('#error_placa_buscar').html('No se encontró un equipo con esa placa de inventario.').show();
						$('#equipo').attr('action', '#');
					}
				}
			});
		}
	});
	
	//--------------------------------------------------------------------------
	
	jQuery('#boton_certificado').click(function()
	{
		if ( jQuery('#nombre_archivo_certificado').val() != '' )
		{
			jQuery('#reloj_arena_1a').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
			jQuery.ajaxFileUpload
			({
				url:'/certificados/asignar_certificado/'+jQuery('#id_equipo').val()+'/'+jQuery('#placa_inventario_equipo').val(),
				secureuri:false,
				fileElementId:'nombre_archivo_certificado',
				dataType: 'json',
				success: function(data)
				{
					jQuery('#reloj_arena_1a').hide();
					if ( data.resultado == true )
					{
						jQuery('#select_certificados').addOption(data.id, data.nombre_archivo);
						jQuery('#msj_select').hide();
						jQuery('#div_msj_cert_eliminado').hide();
						jQuery('#div_msj_cert_nuevo').html('Se ha añadido el certificado.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
						jQuery('#cuadro_eliminar_certificado').show();
					}
					else
					{
						// Informa ERROR al intentar añadir el certificado.
						jQuery('#div_msj_cert_nuevo').html('No se pudo añadir el certificado.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
					}
				},
				error: function(data, status, e)
				{
					jQuery('#reloj_arena_1a').hide();
					alert(e);
				}
			});
		}
		
		return false;
	});
	
	//--------------------------------------------------------------------------
	
	jQuery('#boton_eliminar_certificado').click(function()
	{
		var certificados_seleccionados = jQuery('#select_certificados').selectedValues();
		if ( certificados_seleccionados.length > 0 )
		{
			var msj = '¿Está seguro de querer borrar el certificado?';
			if ( certificados_seleccionados.length > 1 )
			{
				msj = '¿Está seguro de querer borrar los certificados seleccionados?';
			}
			if ( confirm(msj) )
			{
				jQuery('#reloj_arena_1b').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
				for ( var i=0; i < certificados_seleccionados.length; i++ )
				{
					jQuery.ajax(
					{
						type: "POST",
						url: '/certificados/eliminar_certificado/' + certificados_seleccionados[i],
						dataType: 'text',
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == 'true' )
							{
								jQuery('#select_certificados').removeOption(''+certificados_seleccionados[i]);
							}
						}
					});
				}
				
				if ( jQuery('#select_certificados > option').length == 0 )
				{
					jQuery('#cuadro_eliminar_certificado').hide();
				}
				jQuery('#reloj_arena_1b').hide();
				
				msj = 'Se ha eliminado el certificado.';
				if ( certificados_seleccionados.length > 1 )
				{
					msj = 'Se han eliminado los certificados.';
				}
				jQuery('#div_msj_cert_eliminado').html(msj).show().fadeTo(5000, 1).fadeOut(2000, function()
				{
					if ( jQuery('#select_certificados > option').length == 0 )
					{
						jQuery('#msj_select').show();
					}
				});
			}
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_garantia').click(function()
	{
		if ( jQuery('#nombre_archivo_garantia').val() != '' )
		{
			jQuery('#reloj_arena_2a').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
			jQuery.ajaxFileUpload
			({
				url:'/garantias/asignar_garantia/'+jQuery('#id_equipo').val()+'/'+jQuery('#placa_inventario_equipo').val(),
				secureuri:false,
				fileElementId:'nombre_archivo_garantia',
				dataType: 'json',
				success: function(data)
				{
					jQuery('#reloj_arena_2a').hide();
					if ( data.resultado == true )
					{
						jQuery('#select_garantias').addOption(data.id, data.nombre_archivo);
						jQuery('#msj_select_gara').hide();
						jQuery('#div_msj_gara_eliminado').hide();
						jQuery('#div_msj_gara_nuevo').html('Se ha añadido la garantía.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
						jQuery('#cuadro_eliminar_garantia').show();
					}
					else
					{
						// Informa ERROR al intentar añadir la garantía.
						jQuery('#div_msj_gara_nuevo').html('No se pudo añadir la garantia.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
					}
				},
				error: function(data, status, e)
				{
					jQuery('#reloj_arena_2a').hide();
					alert(e);
				}
			});
		}
		
		return false;
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_eliminar_garantia').click(function()
	{
		var garantias_seleccionadas = jQuery('#select_garantias').selectedValues();
		if ( garantias_seleccionadas.length > 0 )
		{
			var msj = '¿Está seguro de querer borrar la garantía?';
			if ( garantias_seleccionadas.length > 1 )
			{
				msj = '¿Está seguro de querer borrar las garantías seleccionadas?';
			}
			if ( confirm(msj) )
			{
				jQuery('#reloj_arena_2b').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
				for ( var i=0; i < garantias_seleccionadas.length; i++ )
				{
					jQuery.ajax(
					{
						type: "POST",
						url: '/garantias/eliminar_garantia/' + garantias_seleccionadas[i],
						dataType: 'text',
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == 'true' )
							{
								jQuery('#select_garantias').removeOption(''+garantias_seleccionadas[i]);
							}
						}
					});
				}
				
				if ( jQuery('#select_garantias > option').length == 0 )
				{
					jQuery('#cuadro_eliminar_garantia').hide();
				}
				jQuery('#reloj_arena_2b').hide();
				
				msj = 'Se ha eliminado la garantía.';
				if ( garantias_seleccionadas.length > 1 )
				{
					msj = 'Se han eliminado las garantias.';
				}
				jQuery('#div_msj_gara_eliminado').html(msj).show().fadeTo(5000, 1).fadeOut(2000, function()
				{
					if ( jQuery('#select_garantias > option').length == 0 )
					{
						jQuery('#msj_select_gara').show();
					}
				});
			}
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_manual').click(function()
	{
		if ( jQuery('#nombre_archivo_manual').val() != '' )
		{
			jQuery('#reloj_arena_3a').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
			jQuery.ajaxFileUpload
			({
				url:'/manuales/asignar_manual/'+jQuery('#id_equipo').val()+'/'+jQuery('#placa_inventario_equipo').val(),
				secureuri:false,
				fileElementId:'nombre_archivo_manual',
				dataType: 'json',
				success: function(data)
				{
					jQuery('#reloj_arena_3a').hide();
					if ( data.resultado == true )
					{
						jQuery('#select_manuales').addOption(data.id, data.nombre_archivo);
						jQuery('#msj_select_manu').hide();
						jQuery('#div_msj_manu_eliminado').hide();
						jQuery('#div_msj_manu_nuevo').html('Se ha añadido el manual.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
						jQuery('#cuadro_eliminar_manual').show();
					}
					else
					{
						// Informa ERROR al intentar añadir el manual.
						jQuery('#div_msj_manu_nuevo').html('No se pudo añadir el manual.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
					}
				},
				error: function(data, status, e)
				{
					jQuery('#reloj_arena_3a').hide();
					alert(e);
				}
			});
		}
		return false;
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_eliminar_manual').click(function()
	{
		var manuales_seleccionados = jQuery('#select_manuales').selectedValues();
		if ( manuales_seleccionados.length > 0 )
		{
			var msj = '¿Está seguro de querer borrar el manual?';
			if ( manuales_seleccionados.length > 1 )
			{
				msj = '¿Está seguro de querer borrar los manuales seleccionados?';
			}
			if ( confirm(msj) )
			{
				jQuery('#reloj_arena_3b').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
				for ( var i=0; i < manuales_seleccionados.length; i++ )
				{
					jQuery.ajax(
					{
						type: "POST",
						url: '/manuales/eliminar_manual/' + manuales_seleccionados[i],
						dataType: 'text',
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == 'true' )
							{
								jQuery('#select_manuales').removeOption(''+manuales_seleccionados[i]);
							}
						}
					});
				}
				
				if ( jQuery('#select_manuales > option').length == 0 )
				{
					jQuery('#cuadro_eliminar_manual').hide();
				}
				jQuery('#reloj_arena_3b').hide();
				
				msj = 'Se ha eliminado el manual.';
				if ( manuales_seleccionados.length > 1 )
				{
					msj = 'Se han eliminado los manuales.';
				}
				jQuery('#div_msj_manu_eliminado').html(msj).show().fadeTo(5000, 1).fadeOut(2000, function()
				{
					if ( jQuery('#select_manuales > option').length == 0 )
					{
						jQuery('#msj_select_manu').show();
					}
				});
			}
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_factura').click(function()
	{
		if ( jQuery('#nombre_archivo_factura').val() != '' )
		{
			jQuery('#reloj_arena_4a').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
			jQuery.ajaxFileUpload
			({
				url:'/facturas/asignar_factura/'+jQuery('#id_equipo').val()+'/'+jQuery('#placa_inventario_equipo').val(),
				secureuri:false,
				fileElementId:'nombre_archivo_factura',
				dataType: 'json',
				success: function(data)
				{
					jQuery('#reloj_arena_4a').hide();
					if ( data.resultado == true )
					{
						jQuery('#select_facturas').addOption(data.id, data.nombre_archivo);
						jQuery('#msj_select_fact').hide();
						jQuery('#div_msj_fact_eliminado').hide();
						jQuery('#div_msj_fact_nuevo').html('Se ha añadido la factura.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
						jQuery('#cuadro_eliminar_factura').show();
					}
					else
					{
						// Informa ERROR al intentar añadir la factura.
						jQuery('#div_msj_fact_nuevo').html('No se pudo añadir la factura.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
					}
				},
				error: function(data, status, e)
				{
					jQuery('#reloj_arena_4a').hide();
					alert(e);
				}
			});
		}
		return false;
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_eliminar_factura').click(function()
	{
		var facturas_seleccionados = jQuery('#select_facturas').selectedValues();
		if ( facturas_seleccionados.length > 0 )
		{
			var msj = '¿Está seguro de querer borrar la factura?';
			if ( facturas_seleccionados.length > 1 )
			{
				msj = '¿Está seguro de querer borrar las facturas seleccionadas?';
			}
			if ( confirm(msj) )
			{
				jQuery('#reloj_arena_4b').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
				for ( var i=0; i < facturas_seleccionados.length; i++ )
				{
					jQuery.ajax(
					{
						type: "POST",
						url: '/facturas/eliminar_factura/' + facturas_seleccionados[i],
						dataType: 'text',
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == 'true' )
							{
								jQuery('#select_facturas').removeOption(''+facturas_seleccionados[i]);
							}
						}
					});
				}
				
				if ( jQuery('#select_facturas > option').length == 0 )
				{
					jQuery('#cuadro_eliminar_factura').hide();
				}
				jQuery('#reloj_arena_4b').hide();
				
				msj = 'Se ha eliminado la factura.';
				if ( facturas_seleccionados.length > 1 )
				{
					msj = 'Se han eliminado las facturas.';
				}
				jQuery('#div_msj_fact_eliminado').html(msj).show().fadeTo(5000, 1).fadeOut(2000, function()
				{
					if ( jQuery('#select_facturas > option').length == 0 )
					{
						jQuery('#msj_select_fact').show();
					}
				});
			}
		}
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_cotizacion').click(function()
	{
		if ( jQuery('#nombre_archivo_cotizacion').val() != '' )
		{
			jQuery('#reloj_arena_5a').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
			jQuery.ajaxFileUpload
			({
				url:'/cotizaciones/asignar_cotizacion/'+jQuery('#id_equipo').val()+'/'+jQuery('#placa_inventario_equipo').val(),
				secureuri:false,
				fileElementId:'nombre_archivo_cotizacion',
				dataType: 'json',
				success: function(data)
				{
					jQuery('#reloj_arena_5a').hide();
					if ( data.resultado == true )
					{
						jQuery('#select_cotizaciones').addOption(data.id, data.nombre_archivo);
						jQuery('#msj_select_coti').hide();
						jQuery('#div_msj_coti_eliminado').hide();
						jQuery('#div_msj_coti_nuevo').html('Se ha añadido la cotización.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
						jQuery('#cuadro_eliminar_cotizacion').show();
					}
					else
					{
						// Informa ERROR al intentar añadir la cotización.
						jQuery('#div_msj_coti_nuevo').html('No se pudo añadir la cotización.').show().fadeTo(5000, 1).fadeOut(2000, function(){});
					}
				},
				error: function(data, status, e)
				{
					jQuery('#reloj_arena_5a').hide();
					alert(e);
				}
			});
		}
		return false;
	});
	
	//--------------------------------------------------------------------------
	
	$('#boton_eliminar_cotizacion').click(function()
	{
		var cotizaciones_seleccionados = jQuery('#select_cotizaciones').selectedValues();
		if ( cotizaciones_seleccionados.length > 0 )
		{
			var msj = '¿Está seguro de querer borrar la cotización?';
			if ( cotizaciones_seleccionados.length > 1 )
			{
				msj = '¿Está seguro de querer borrar las cotizaciones?';
			}
			if ( confirm(msj) )
			{
				jQuery('#reloj_arena_5b').html('<img border="0" alt="" src="/img/ajaxload.gif">').show();
				for ( var i=0; i < cotizaciones_seleccionados.length; i++ )
				{
					jQuery.ajax(
					{
						type: "POST",
						url: '/cotizaciones/eliminar_cotizacion/' + cotizaciones_seleccionados[i],
						dataType: 'text',
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == 'true' )
							{
								jQuery('#select_cotizaciones').removeOption(''+cotizaciones_seleccionados[i]);
							}
						}
					});
				}
				
				if ( jQuery('#select_cotizaciones > option').length == 0 )
				{
					jQuery('#cuadro_eliminar_cotizacion').hide();
				}
				jQuery('#reloj_arena_5b').hide();
				
				msj = 'Se ha eliminado la cotización.';
				if ( cotizaciones_seleccionados.length > 1 )
				{
					msj = 'Se han eliminado las cotizaciones.';
				}
				jQuery('#div_msj_coti_eliminado').html(msj).show().fadeTo(5000, 1).fadeOut(2000, function()
				{
					if ( jQuery('#select_cotizaciones > option').length == 0 )
					{
						jQuery('#msj_select_coti').show();
					}
				});
			}
		}
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
	
	//--------------------------------------------------------------------------
});
