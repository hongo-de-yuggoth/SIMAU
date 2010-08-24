<?php
class Solicitud extends AppModel
{
	var $name = 'Solicitud';
	var $belongsTo = array
	(
		'CentroCosto' => array
		(
			'className' => 'CentroCosto',
			'foreignKey' => 'Cencos_id'
		),
		'Usuario' => array
		(
			'className' => 'Usuario',
			'foreignKey' => 'cedula_usuario'
		)
	);
	var $tipo_servicio = array
	(
		'1' => 'Mantenimiento Preventivo',
		'2' => 'Mantenimiento Correctivo',
		'3' => 'Calibración / Certificación'
	);
}
?>
