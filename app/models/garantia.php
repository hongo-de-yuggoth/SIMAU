<?php
class Garantia extends AppModel
{
	var $name = "Garantia";
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
