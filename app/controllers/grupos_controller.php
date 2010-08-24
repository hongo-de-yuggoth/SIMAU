<?php
class GruposController extends AppController
{
	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function crear($nombre)
	{
		$this->Grupo->set('name', $nombre);
		$this->Grupo->save();
	}
	
	//--------------------------------------------------------------------------
	
}
?>