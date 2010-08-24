<?php
class Cotizacion extends AppModel
{
	var $name = "Cotizacion";
	var $belongsTo = array
	(
		'Producto' => array
		(
			'className' => 'Producto',
			'foreignKey' => 'Prousu_Pro_id'
		)
	);
   var $cacheQueries = false;
}
?>
