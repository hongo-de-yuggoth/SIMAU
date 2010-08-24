<?php
class EdificiosController extends AppController
{
	var $name = "Edificios";
	
	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function crear($nombre_edificio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$this->Edificio->set('name', $nombre_edificio);
		
		if ( $this->Edificio->save() )
		{
			return 'true';
		}
		else
		{
			return 'false';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function modificar($id, $nuevo_nombre)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$this->Edificio->read(null, $id);
		$this->Edificio->set('name', $nuevo_nombre);
		
		if ( $this->Edificio->save() )
		{
			return 'true';
		}
		else
		{
			return 'false';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function eliminar($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Dependencia');
		
		$dependencia = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.id_edificio'=>$this->data['Edificio']['id'])));
		if ( empty($dependencia) )
		{
			if ( $this->Edificio->delete($id) )
			{
				return 'true';
			}
		}
		
		return 'false';
	}
	
	//--------------------------------------------------------------------------
	
	function existe_edificio($nombre_edificio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		// Aca Standarizamos el formato del nombre.
		$nombre_standard = $nombre_edificio;
		
		$edificio_info = $this->Edificio->find('first', array('conditions' => array('Edificio.name' => $nombre_standard)));
		
		if ( empty($edificio_info) )
		{
			return 'false';
		}
		else
		{
			return 'true';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_select()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$opciones = '';
		$edificios_info = $this->Edificio->query('SELECT * FROM edificios WHERE id!=1 ORDER BY edificios.name');
		if ( !empty($edificios_info) )
		{
			foreach ( $edificios_info as $edificio )
			{
				$opciones .= '<option value="'.$edificio['edificios']['id'].'">'.$edificio['edificios']['name'].'</option>';
			}
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_select_edificios_con_dependencias()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Dependencia');
		
		$edificios_info = $this->Edificio->query('SELECT * FROM edificios WHERE id!=1 ORDER BY edificios.name');
		
		$opciones = '';
		if ( !empty($edificios_info) )
		{
			foreach ( $edificios_info as $edificio )
			{
				// Se busca si $edificio tiene alguna dependencia.
				$dependencias_info = $this->Dependencia->find('first', array('conditions' => array('Dependencia.id_edificio' => $edificio['edificios']['id'])));
		
				// Si el edificio tiene alguna dependencia.
				if ( !empty($dependencias_info) )
				{
					$opciones .= '<option value="'.$edificio['edificios']['id'].'">'.$edificio['edificios']['name'].'</option>';
				}
			}
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_select_edificios_con_dependencias_con_usuarios()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Dependencia');
		$this->loadModel('Usuario');
		$opciones = '';
		$edificios_info = $this->Edificio->query('SELECT * FROM edificios WHERE id!=1 ORDER BY edificios.name');
		if ( !empty($edificios_info) )
		{
			foreach ( $edificios_info as $edificio )
			{
				// Se busca si $edificio tiene alguna dependencia.
				$dependencias_info = array();
				$dependencias_info = $this->Dependencia->find('all', array('conditions' => array('Dependencia.id_edificio' => $edificio['edificios']['id'])));
				if ( !empty($dependencias_info) )
				{
					// Si al menos una de estas dependencias tiene algún usuario,
					// se tiene (el edificio) en cuenta para el select.
					foreach ( $dependencias_info as $dependencia )
					{
						$usuarios_info =  array();
						$usuarios_info = $this->Usuario->find('first', array
						(
							'fields' => array('Usuario.Usu_cedula'),
							'conditions' => array('Usuario.Usu_Cencos_id' => $dependencia['Dependencia']['Cencos_id'])
						));
						if ( !empty($usuarios_info) )
						{
							$opciones .= '<option value="'.$edificio['edificios']['id'].'">'.$edificio['edificios']['name'].'</option>';
							break;
						}
					}
				}
			}
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
}
?>
