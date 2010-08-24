<?php
class Factura extends AppModel
{
	var $name = "Factura";
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
