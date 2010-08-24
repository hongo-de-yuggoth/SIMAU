//-----------------------------------------------------------------------------

function existe_cedula(cedula)
{
	var res;
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/existe_cedula/' + cedula,
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			if ( resultado == 'false' )
			{
				res = false;
			}
			else if ( resultado == 'true' )
			{
				res = true;
			}
		}
	});
	
	return res;
}

//-----------------------------------------------------------------------------

function existe_login(login)
{
	$.ajax(
	{
		type: "POST",
		url: '/smuq_usuarios/existe_login/' + login,
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			if ( resultado == 'false' )
			{
				res = false;
			}
			else if ( resultado == 'true' )
			{
				res = true;
			}
		}
	});
	
	return res;
}

//-----------------------------------------------------------------------------

function existe_placa(placa_inventario)
{
	var result;
	$.ajax(
	{
		type: "POST",
		url: '/equipos/existe_placa_inventario/' + placa_inventario,
		dataType: 'text',
		cache: false,
		async: false,
		success: function(resultado)
		{
			//$('#tmp_resultado').val(jQuery.trim(resultado));
			result = resultado;
		}
	});
	
	/*existe = $('#tmp_resultado').val();
	
	if ( existe == 'false' )
	{
		return false;
	}
	else if ( existe == 'true' )
	{
		return true;
	}*/
	return result;
}

//-----------------------------------------------------------------------------
