<?php
class Manual extends AppModel
{
	var $name = "Manual";
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
