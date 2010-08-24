<?php
class DependenciasController extends AppController
{
	var $uses  = array('Dependencia', 'CentroCosto');
	
	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function modificar($id, $id_edificio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		if ( $id_edificio == '0' )
		{
			// Eliminamos el registro de Dependencia. (Desvincular de edificio).
			return $this->Dependencia->delete($id);
		}
		else
		{
			$data = array('Dependencia'=>array('Cencos_id'=>$id, 'id_edificio'=>$id_edificio));
			if ( $this->Dependencia->save($data) )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_select($id_edificio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$opciones = '';
		
		if ( $id_edificio == '0' )
		{
			// Primero obtenemos el listado de cencos que tienen edificio asignado.
			$dependencias_info = $this->Dependencia->find('all', array
			(
				'fields' => array('Dependencia.Cencos_id'),
				'conditions' => array('Dependencia.Cencos_id !=' => 1)
			));
			$dependencias = array();
			foreach ( $dependencias_info as $dependencia )
			{
				$dependencias[] = $dependencia['Dependencia']['Cencos_id'];
			}
			
			// Luego obtenemos los cencos que no se encuentren en el listado de dependencias.
			$cencos = $this->CentroCosto->find('all', array
			(
				'fields' => array('Cencos_id', 'Cencos_nombre'),
				'conditions' => array('NOT' => array('CentroCosto.Cencos_id' => $dependencias)),
				'order' => array('CentroCosto.Cencos_nombre')
			));
			foreach ( $cencos as $cenco )
			{
				$opciones .= '<option value="'.$cenco['CentroCosto']['Cencos_id'].'">'.
				mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</option>';
			}
		}
		else
		{
			$dependencias_info = $this->Dependencia->find('all', array
			(
				'fields' => array('Dependencia.Cencos_id'),
				'conditions' => array
				(
					'Dependencia.Cencos_id !=' => 1,
					'Dependencia.id_edificio' => $id_edificio
				)
			));
			
			
			//----->
			// Se necesita ordenar alfabeticamente.
			//
			// Se debe armar un array ( 'Cencos_is' => 'Cencos_nombre' )
			//
			// Pasamos a arreglo
			$dependencias = array();
			foreach ( $dependencias_info as $dependencia )
			{
				$cenco = array();
				$cenco = $this->CentroCosto->findByCencosId($dependencia['Dependencia']['Cencos_id']);
				$dependencias[mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8")] = $dependencia['Dependencia']['Cencos_id'];
			}
			ksort($dependencias);
			foreach ( $dependencias as $nombre_oficina => $id_oficina )
			{
				$opciones .= '<option value="'.$id_oficina.'">'.$nombre_oficina.'</option>';
			}
			//----->
			
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
	
	// Crea opciones de un select con las dependencias del $id_edificio que tengan
	// al menos un usuario asignado.
	function cargar_select_con_usuarios($id_edificio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');
		$opciones = '';
		
		// Encontramos todos los Cencos ya asignados a algún edificio
		$condiciones_deps = array('Dependencia.Cencos_id !=' => 1);
		if ( $id_edificio != '0' )
		{
			$condiciones_deps['Dependencia.id_edificio'] = $id_edificio;
		}
		$dependencias = $this->Dependencia->find('all', array
		(
			'fields' => array('Dependencia.Cencos_id'),
			'conditions' => $condiciones_deps
		));
		$dependencias_asignadas = array();
		foreach ( $dependencias as $dependencia )
		{
			$dependencias_asignadas[] = $dependencia['Dependencia']['Cencos_id'];
		}
		$cond = array('CentroCosto.Cencos_id' => $dependencias_asignadas);
		if ( $id_edificio == '0' )
		{
			$condiciones_cencos['NOT'] = $cond;
		}
		else
		{
			$condiciones_cencos = $cond;
		}
		$cencos = $this->CentroCosto->find('all', array
		(
			'fields' => array('CentroCosto.Cencos_id', 'CentroCosto.Cencos_nombre'),
			'conditions' => array
			(
				'CentroCosto.Cencos_id !=' => 1,
				$condiciones_cencos
			),
			'order' => array('CentroCosto.Cencos_nombre')
		));
		if ( !empty($cencos) )
		{
			foreach ( $cencos as $cenco )
			{
				// Se busca si $cenco tiene algún usuario.
				$usuarios_info = $this->Usuario->find('first', array
				(
					'fields' => array('Usuario.usu_cedula'),
					'conditions' => array('Usuario.usu_Cencos_id' => $cenco['CentroCosto']['Cencos_id'])
				));
		
				// Si la dependencia tiene algún usuario.
				if ( !empty($usuarios_info) )
				{
					$opciones .= '<option value="'.$cenco['CentroCosto']['Cencos_id'].'">'.
					mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</option>';
				}
			}
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
	
	// Crea opciones de un select con todas las dependencias que tengan
	// al menos un usuario asignado.
	function cargar_select_dependencias_usuarios()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');
		$opciones = '';
		
		$cencos = $this->CentroCosto->find('all', array
		(
			'fields' => array('CentroCosto.Cencos_id', 'CentroCosto.Cencos_nombre'),
			'conditions' => array
			(
				'CentroCosto.Cencos_id !=' => 1
			),
			'order' => array('CentroCosto.Cencos_nombre')
		));
		if ( !empty($cencos) )
		{
			foreach ( $cencos as $cenco )
			{
				// Se busca si $cenco tiene algún usuario.
				$usuarios_info = $this->Usuario->find('first', array
				(
					'fields' => array('Usuario.usu_cedula'),
					'conditions' => array('Usuario.usu_Cencos_id' => $cenco['CentroCosto']['Cencos_id'])
				));
		
				// Si la dependencia tiene algún usuario.
				if ( !empty($usuarios_info) )
				{
					$opciones .= '<option value="'.$cenco['CentroCosto']['Cencos_id'].'">'.
					mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</option>';
				}
			}
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
}
?>

