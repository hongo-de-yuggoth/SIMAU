<?php
class Certificado extends AppModel
{
	var $name = "Certificado";
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
