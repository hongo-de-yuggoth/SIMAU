<?php
// Modelo Usuario de la BD Inventario
class Usuario extends AppModel
{
	var $name = "Usuario";
	var $primaryKey = 'Usu_cedula';
	var $useDbConfig ='cencos';
	var $belongsTo = array
	(
		'CentroCosto' => array
		(
			'className' => 'CentroCosto',
			'foreignKey' => 'Usu_Cencos_id'
		)
	);
	
	//--------------------------------------------------------------------------
} 
?>
