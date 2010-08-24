<?php
class Edificio extends AppModel
{
	var $name = "Edificio";
	var $hasMany = array
	(
		'Dependencia' => array
		(
			'className' => 'Dependencia',
			'foreignKey' => 'id_edificio'
		)
	);
}
?>