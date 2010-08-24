<?php
class Dependencia extends AppModel
{
	var $name = "Dependencia";
	var $primaryKey = 'Cencos_id';
	/*var $hasMany = array
	(
		'Solicitud' => array
		(
			'className' => 'Solicitud',
			'foreignKey' => 'id_dependencia'
		),
		'Usuario' => array
		(
			'className' => 'Usuario',
			'foreignKey' => 'id_dependencia'
		),
		'Equipo' => array
		(
			'className' => 'Equipo',
			'foreignKey' => 'id_dependencia'
		)
	);*/
	
	var $belongsTo = array
	(
		'Edificio' => array
		(
			'className' => 'Edificio',
			'foreignKey' => 'id_edificio'
		)
	);
}
?>
