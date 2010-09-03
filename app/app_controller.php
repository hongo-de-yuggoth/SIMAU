<?php
class AppController extends Controller
{
	var $id_grupo;

	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->disableCache();
		
		if ( $this->Session->check('Usuario.id_grupo')  )
		{
			if ( $this->id_grupo != '*' && $this->Session->read('Usuario.id_grupo') != $this->id_grupo )
			{
				$this->Session->write('referer', $this->referer());
				$this->redirect(array('controller' => 'smuq_usuarios',
											'action' => 'denegado'));
			}
			$this->set('display_contexto', 'block');
			$this->set('contexto', 'Bienvenido, '.$this->Session->read('Usuario.nombre'));
		}
		else if ( $this->params['controller'] == 'smuq_usuarios' && $this->params['action'] == 'login' )
		{
			$this->set('display_contexto', 'none');
		}
		else
		{
			$this->redirect(array
			(
				'controller' => 'smuq_usuarios',
				'action' => 'login'
			));
		}
	}
}
?>
