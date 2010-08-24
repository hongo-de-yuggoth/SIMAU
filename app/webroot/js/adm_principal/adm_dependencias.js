//-----------------------------------------------------------------------------

function cargar_edificios_con_dependencias()
{
	$.ajax(
	{
		type: "POST",
		url: '/edificios/cargar_select_edificios_con_dependencias/',
		dataType: 'text',
		cache: false,
		async: false,
		success: function(opciones2_select)
		{
			$('#id_edificio_modificar').html('<option value="0">Dependencias sin edificio asignado</option>'+opciones2_select);
		}
	});
}

//--------------------------------------------------------------------------

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
				$('#id_nuevo_edificio_modificar').html(opciones_select+'<option value="0">Desvincular la dependencia del edificio actual</option>');
			}
		}
	});
}

//-----------------------------------------------------------------------------

function cargar_dependencias()
{
	var edificio = $('#id_edificio_modificar').val();
	$.ajax(
	{
		type: "POST",
		url: '/dependencias/cargar_select/'+edificio,
		dataType: 'text',
		cache: false,
		async: false,
		success: function(opciones_select)
		{
			if ( opciones_select != '' )
			{
				$('#id_dependencia_modificar').html(opciones_select);
			}
		}
	});
}

//-----------------------------------------------------------------------------

function modificar_dependencia()
{
	jQuery.ajax(
	{
		type: "POST",
		url: '/dependencias/modificar/'+jQuery('#id_dependencia_modificar').val()+'/'+jQuery('#id_nuevo_edificio_modificar').val(),
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			if ( resultado == true )
			{
				jQuery('#error_dependencia_modificar').html('La dependencia fué modificada.').show();
				
				// se recarga los selects de edificios y dependencias.
				cargar_edificios_con_dependencias();
				cargar_dependencias();
			}
			else if ( resultado == false )
			{
				jQuery('#error_dependencia_modificar').html('La dependencia no pudo ser modificada.').show();
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
	cargar_edificios_con_dependencias();
	cargar_dependencias();
	cargar_edificios();
	
	//--------------------------------------------------------------------------
	// Programamos los diferentes EVENTOS.
	
	$('#id_edificio_modificar').change(cargar_dependencias);
	
	//--------------------------------------------------------------------------
	
	$('#boton_modificar_dependencia').click(function()
	{
		if ( !($('#id_nuevo_edificio_modificar').val() == 0 && $('#id_edificio_modificar').val() == 0) )
		{
			modificar_dependencia();
		}
		else
		{
			jQuery('#error_dependencia_modificar').hide();
		}
	});

	//--------------------------------------------------------------------------
});
