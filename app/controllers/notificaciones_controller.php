<?php
class NotificacionesController extends AppController
{
	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function marcar_como_leida($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->Notificacion->read(null, $id);
		if ( $this->Notificacion->save(array('Notificacion'=>array('leido'=>'si'))) )
		{
			return 'true';
		}
		return 'false';
	}
	
	//--------------------------------------------------------------------------
}
?>
