<?php
class Notificacion extends AppModel
{
	var $name = 'Notificacion';
	var $belongsTo = array
	(
		'Solicitud' => array
		(
			'className' => 'Solicitud',
			'foreignKey' => 'id_solicitud'
		),
		'Usuario' => array
		(
			'className' => 'Usuario',
			'foreignKey' => 'cedula'
		)
	); 
}
?>
