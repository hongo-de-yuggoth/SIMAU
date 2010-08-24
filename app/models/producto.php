<?php
class Producto extends AppModel
{
	var $name = "Producto";
	var $useTable = 'productosxusuario';
	var $primaryKey = 'Prousu_Pro_id';
	var $useDbConfig ='cencos';
	var $belongsTo = array
	(
		'Usuario' => array
		(
			'className' => 'Usuario',
			'foreignKey' => 'prousu_usu_cedula'
		)
	);
	var $cacheQueries = false;
}
?>
