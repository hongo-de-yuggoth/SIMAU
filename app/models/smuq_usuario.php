<?php
class SmuqUsuario extends AppModel
{
	var $name = "SmuqUsuario";
	var $primaryKey = 'cedula';
	var $belongsTo = array
	(
		'Grupo' => array
		(
			'className' => 'Grupo',
			'foreignKey' => 'id_grupo'
		)
	);
	/*
	'Cencos' => array
	(
		'className' => 'CentroCosto',
		'foreignKey' => 'Cencos_id'
	),
	*/
	
	//--------------------------------------------------------------------------
} 
?>
