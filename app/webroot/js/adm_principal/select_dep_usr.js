function cargo()
{
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/cargo_ajax/' + $('#id_usuario').val(),
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			$('#cargo').html(resultado);
		}
	});
}

//-----------------------------------------------------------------------------

function cargar_usuarios()
{
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/cargar_select/' + $('#id_dependencia').val(),
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			$('#id_usuario').html(resultado);
			cargo();
		}
	});
}

//-----------------------------------------------------------------------------

function cargar_dependencias()
{
	$.ajax(
	{
		type: "POST",
		url: '/dependencias/cargar_select_con_usuarios/' + $('#id_edificio').val(),
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			$('#id_dependencia').html(resultado);
			cargar_usuarios();
		}
	});
}
