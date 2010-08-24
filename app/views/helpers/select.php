<?php
class SelectHelper extends AppHelper
{
	//--------------------------------------------------------------------------
	
	/**
	 * Crea un HTML Select con las opciones según los elementos encontrados en el arreglo.
	 * @param object $arreglo
	 * @param object $id_select
	 * @return 
	 */
	function crear_select($arreglo, $id_select, $nombre)
	{
		$opciones = '';
		foreach ( $arreglo as $elemento )
		{
			$opciones = $opciones.'<option value="'.$elemento['edificios']['id'].'">'.$elemento['edificios']['name'].'</option>';
		}
		
		return '<select id="'.$id_select.'" name="'.$nombre.'">'.$opciones.'</select>';
	}
	
	//--------------------------------------------------------------------------
}
?>