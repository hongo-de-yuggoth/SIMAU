<?php
class SmuqUsuariosController extends AppController
{
	var $name = 'SmuqUsuarios';
	var $helpers = array('Html', 'Form', 'Javascript');
	var $uses  = array('SmuqUsuario', 'Usuario');
	var $id_grupo = '*';
	
	//--------------------------------------------------------------------------
	
	function exportar_xls($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_tipo_usuario)
	{
		$datos = json_decode($this->requestAction('/smuq_usuarios/buscar_xls/'.$frase_busqueda.'/'.$criterio_campo.'/'.$criterio_dependencia.'/'.$criterio_tipo_usuario));
		$this->set('filas_tabla',utf8_decode($datos->filas_tabla));
		$this->set('total_registros',$datos->count);
		$this->render('exportar_xls','exportar_xls');
	}
	
	//--------------------------------------------------------------------------
	
	function _autenticado($data)
	{
		if ( !empty($data) )
		{
			// Primero buscamos en CENCOS.
			$db = &ConnectionManager::getDataSource($this->Usuario->useDbConfig);
			$usuario = $this->Usuario->find('first', array
			(
				'fields' => array
				(
					'Usuario.Usu_cedula',
					'Usuario.Usu_nombre'
				),
				'conditions' => array
				(
					'Usu_login' => $data['SmuqUsuario']['login'],
					'Usu_password' => $db->expression("old_password('".$data['SmuqUsuario']['clave']."')" )
				)
			));
			if ( !empty($usuario) )
			{
				$this->Session->write('Usuario.cedula', $usuario['Usuario']['Usu_cedula']);
				$this->Session->write('Usuario.nombre', mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8"));
				$this->Session->write('Usuario.id_grupo', 3);
				return true;
			}
			else
			{
				$usuario = $this->SmuqUsuario->find('first', array
				(
					'fields' => array
					(
						'SmuqUsuario.cedula',
						'SmuqUsuario.nombre',
						'SmuqUsuario.id_grupo'
					),
					'conditions' => array
					(
						'SmuqUsuario.login' => strtolower($data['SmuqUsuario']['login']),
						'SmuqUsuario.clave' => Security::hash($data['SmuqUsuario']['clave'], null, true)
					)
				));
				if ( !empty($usuario) )
				{
					$this->Session->write('Usuario.cedula', $usuario['SmuqUsuario']['cedula']);
					$this->Session->write('Usuario.nombre', $usuario['SmuqUsuario']['nombre']);
					$this->Session->write('Usuario.id_grupo', $usuario['SmuqUsuario']['id_grupo']);
					return true;
				}
				else
				{
					$this->Session->delete('Usuario.cedula');
					$this->Session->delete('Usuario.nombre');
					$this->Session->delete('Usuario.id_grupo');
				}
			}
		}
		return false;
	}

	//--------------------------------------------------------------------------

	function login()
	{
		// Si no está autenticado, mire a ver si autentica o nó.
		if ( !$this->Session->check('Usuario.cedula') )
		{
			$this->set('opcion_seleccionada', 'login');
			// Si se envió datos de login -> Autenticar
			if ( !empty($this->data) )
			{
				if ( $this->_autenticado($this->data) )
				{
					$this->data = null;
					$this->Session->write('Controlador.resultado', '');
					$this->redirect(array('controller' => 'smuq_usuarios',
												 'action' => 'ir_a_casa'));
				}
				else
				{
					$this->data = null;
					$this->set('display_notificacion', 'block');
					$this->set('clase_notificacion', 'clean-error');
					$this->set('mensaje_notificacion', 'No se pudo iniciar la sesión. Por favor revise su login y clave.');
				}
			}
			else
			{
				$this->data = null;
				$this->set('display_notificacion', 'none');
				$this->set('clase_notificacion', '');
				$this->set('mensaje_notificacion', '');
			}
		}
		else
		{
			$this->data = null;
			$this->Session->write('Controlador.resultado', '');
			$this->redirect(array('controller' => 'smuq_usuarios',
										'action' => 'ir_a_casa'));
		}
	}
	
	//--------------------------------------------------------------------------
	
	function logout()
	{
		$this->Session->destroy();
		$this->redirect(array('controller' => 'smuq_usuarios',
									 'action' => 'login'));
	}
	
	//--------------------------------------------------------------------------
	
	function denegado()
	{
		$this->set('refererido', $this->Session->read('referer'));
		//$this->Session->delete('referer');
	}
	
	//--------------------------------------------------------------------------
	
	function index()
	{
	}
	
	//--------------------------------------------------------------------------
	
	function ir_a_casa()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		if ( $this->Session->check('Usuario.id_grupo') )
		{
			$id_grupo = $this->Session->read('Usuario.id_grupo');
			// Se define a que grupo pertenece el usuario y luego se le lleva a su "casa"
			if ( $id_grupo == '1' )
			{
				$this->redirect(array('controller' => 'adm_principal',
											'action' => 'crear_solicitud_mantenimiento'));
			}
			else if ( $id_grupo == '2' )
			{
				$this->redirect(array('controller' => 'adm_soluciones',
											'action' => 'crear_solicitud_mantenimiento'));
			}
			else if ( $id_grupo == '3' )
			{
				$this->redirect(array('controller' => 'usr_dependencia',
											'action' => 'crear_solicitud_mantenimiento'));
			}
		}
		
		$this->redirect($this->referer());
	}
	
	//--------------------------------------------------------------------------
	
	function existe_cedula($cedula)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$usuario = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.cedula' => $cedula)));
		if ( !empty($usuario) )
		{
			return 'true';
		}
		else
		{
			$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.Usu_cedula' => $cedula)));
			if ( !empty($usuario) )
			{
				return 'true';
			}
			else
			{
				return 'false';
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function existe_login($login)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$usuario = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.login' => strtolower($login))));
		if ( !empty($usuario) )
		{
			echo 'true';
		}
		else
		{
			$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.Usu_login' => strtolower($login))));
			if ( !empty($usuario) )
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_select($id_dependencia)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$modelo = 'Usuario';
		$modelo_cedula = 'cedula';
		$modelo_nombre = 'nombre';
		if ( $id_dependencia == 1 )
		{
			$usuarios_info = $this->SmuqUsuario->find('all', array
			(
				'conditions' => array('SmuqUsuario.id_dependencia' => 1),
				'order' => array('SmuqUsuario.nombre')
			));
			$modelo = 'Smuq'.$modelo;
		}
		else
		{
			$usuarios_info = $this->Usuario->find('all', array
			(
				'conditions' => array('Usuario.Usu_Cencos_id' => $id_dependencia),
				'order' => array('Usuario.Usu_nombre')
			));
			$modelo_cedula = 'Usu_'.$modelo_cedula;
			$modelo_nombre = 'Usu_'.$modelo_nombre;
		}
		$opciones = '';
		foreach ( $usuarios_info as $usuario )
		{
			$opciones .= '<option value="'.$usuario[$modelo][$modelo_cedula].'">'.
			mb_convert_case($usuario[$modelo][$modelo_nombre], MB_CASE_TITLE, "UTF-8").'</option>';
		}
		return $opciones;
	}
	
	//--------------------------------------------------------------------------
	
	function cargo_ajax($cedula)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		// Se busca solo en SmuqUsuario, y si no está, entonces no tiene aun
		// un cargo asignado en la BD.
		$usuario = $this->SmuqUsuario->findByCedula($cedula);
		if ( !empty($usuario) )
		{
			return $usuario['SmuqUsuario']['cargo'];
		}
		else
		{
			return 'No disponible.';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function buscar_usuario_modificar($cedula)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		$this->loadModel('CentroCosto');
		
		$usuario_info = $this->Usuario->find('first', array('conditions' => array('Usuario.Usu_cedula' => $cedula)));
		$smuq_usuario_info = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.cedula' => $cedula)));
		
		// Es un usuario CENCOS?
		if ( !empty($usuario_info) )
		{
			$centro_costo_info = $this->CentroCosto->findByCencosId($usuario_info['Usuario']['Usu_Cencos_id']);
			$dependencia_info = $this->Dependencia->findByCencosId($usuario_info['Usuario']['Usu_Cencos_id']);
			
			if ( !empty($centro_costo_info) )
			{
				// Revizamos si la dependencia tiene un edificio asignado.
				if ( !empty($dependencia_info) )
				{
					$edificio_info = $this->Edificio->findById($dependencia_info['Dependencia']['id_edificio']);
					if ( empty($edificio_info) )
					{
						$edificio_info['Edificio']['name'] = 'No tiene un edificio asignado.';
					}
				}
				else
				{
					$dependencia_info['Dependencia']['id_edificio'] = 0;
					$edificio_info['Edificio']['name'] = 'No tiene un edificio asignado.';
				}
				
				
				
				// creamos inputs hidden
				$input_login = '<input id="login_usuario" type="hidden" value="'.$usuario_info['Usuario']['Usu_login'].'"/>';
				$input_tipo_usuario = '<input id="tipo_usuario_usuario" type="hidden" value="3"/>';
				$input_id_dependencia = '<input id="id_dependencia_usuario" type="hidden" value="'.$usuario_info['Usuario']['Usu_Cencos_id'].'"/>';
				$input_nombre = '<input id="nombre_usuario" type="hidden" value="'.mb_convert_case($usuario_info['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'"/>';
				$input_cedula = '<input id="cedula_usuario" type="hidden" value="'.$cedula.'"/>';
				$input_cargo = '<input id="cargo_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['cargo'].'"/>';
				$input_email = '<input id="email_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['email'].'"/>';
				$input_telefono = '<input id="telefono_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['telefono'].'"/>';
				$input_id_edificio = '<input id="id_edificio_usuario" type="hidden" value="'.$dependencia_info['Dependencia']['id_edificio'].'"/>';
				$input_nombre_edificio = '<input id="nombre_edificio" type="hidden" value="'.$edificio_info['Edificio']['name'].'"/>';
				$input_nombre_dependencia = '<input id="nombre_dependencia" type="hidden" value="'.mb_convert_case($centro_costo_info['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'"/>';
				$input_encontro ='<input id="encontro" type="hidden" value="true"/>';
				
				return	$input_nombre.
							$input_cedula.
							$input_login.
							$input_email.
							$input_telefono.
							$input_cargo.
							$input_tipo_usuario.
							$input_id_edificio.
							$input_id_dependencia.
							$input_nombre_edificio.
							$input_nombre_dependencia.
							$input_encontro;
			}
		}
		else if ( !empty($smuq_usuario_info) )
		{
			// Creamos cadena de Inputs Hidden.
			$input_login = '<input id="login_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['login'].'"/>';
			$input_tipo_usuario = '<input id="tipo_usuario_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['id_grupo'].'"/>';
			$input_id_dependencia = '<input id="id_dependencia_usuario" type="hidden" value="1"/>';
			$input_nombre = '<input id="nombre_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['nombre'].'"/>';
			$input_cedula = '<input id="cedula_usuario" type="hidden" value="'.$cedula.'"/>';
			$input_cargo = '<input id="cargo_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['cargo'].'"/>';
			$input_email = '<input id="email_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['email'].'"/>';
			$input_telefono = '<input id="telefono_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['telefono'].'"/>';
			$input_id_edificio = '<input id="id_edificio_usuario" type="hidden" value="1"/>';
			$input_nombre_edificio = '<input id="nombre_edificio" type="hidden" value="Mantenimiento Activos Fijos"/>';
			$input_nombre_dependencia = '<input id="nombre_dependencia" type="hidden" value="Mantenimiento Activos Fijos"/>';
			$input_encontro ='<input id="encontro" type="hidden" value="true"/>';
			
			return	$input_nombre.
						$input_cedula.
						$input_login.
						$input_email.
						$input_telefono.
						$input_cargo.
						$input_tipo_usuario.
						$input_id_edificio.
						$input_id_dependencia.
						$input_nombre_edificio.
						$input_nombre_dependencia.
						$input_encontro;
		}
		return '<input id="encontro" type="hidden" value="false" />';
	}
	
	//--------------------------------------------------------------------------
	
	function buscar_usuario_eliminar($cedula)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		
		$smuq_usuario_info = $this->SmuqUsuario->find('first', array
		(
			'conditions' => array
			(
				'SmuqUsuario.cedula' => $cedula,
				'SmuqUsuario.id_grupo <>'=>3
			)
		));
		if ( !empty($smuq_usuario_info) )
		{
			// Creamos cadena de Inputs Hidden.
			$input_login = '<input id="login_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['login'].'"/>';
			$input_tipo_usuario = '<input id="tipo_usuario_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['id_grupo'].'"/>';
			$input_id_dependencia = '<input id="id_dependencia_usuario" type="hidden" value="1"/>';
			$input_nombre = '<input id="nombre_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['nombre'].'"/>';
			$input_cedula = '<input id="cedula_usuario" type="hidden" value="'.$cedula.'"/>';
			$input_cargo = '<input id="cargo_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['cargo'].'"/>';
			$input_email = '<input id="email_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['email'].'"/>';
			$input_telefono = '<input id="telefono_usuario" type="hidden" value="'.$smuq_usuario_info['SmuqUsuario']['telefono'].'"/>';
			$input_id_edificio = '<input id="id_edificio_usuario" type="hidden" value="1"/>';
			$input_nombre_edificio = '<input id="nombre_edificio" type="hidden" value="Mantenimiento Activos Fijos"/>';
			$input_nombre_dependencia = '<input id="nombre_dependencia" type="hidden" value="Mantenimiento Activos Fijos"/>';
			$input_encontro ='<input id="encontro" type="hidden" value="true"/>';
			
			return	$input_nombre.
						$input_cedula.
						$input_login.
						$input_email.
						$input_telefono.
						$input_cargo.
						$input_tipo_usuario.
						$input_id_edificio.
						$input_id_dependencia.
						$input_dependencia.
						$input_nombre_edificio.
						$input_nombre_dependencia.
						$input_encontro;
		}
		return '<input id="encontro" type="hidden" value="false" />';
	}
	
	//--------------------------------------------------------------------------
	
	function crear()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		if ( !empty($this->data) )
		{
			$this->data['SmuqUsuario']['clave'] = Security::hash($this->data['SmuqUsuario']['clave'], 'sha1', true);
			$this->data['SmuqUsuario']['login'] = strtolower($this->data['SmuqUsuario']['login']);
			$this->Usuario->create();
			
			if ( $this->SmuqUsuario->save($this->data) )
			{
				$this->Session->write('Controlador.resultado_guardar', 'exito');
			}
			else
			{
				$this->Session->write('Controlador.resultado_guardar', 'error');
			}
			$this->redirect($this->referer());
		}
	}
	
	//--------------------------------------------------------------------------
	
	function modificar()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		
		//print_r($this->data); return;
		
		
		if ( !empty($this->data) )
		{
			if ( isset($this->data['SmuqUsuario']['clave']) )
			{
				$this->data['SmuqUsuario']['clave'] = Security::hash($this->data['SmuqUsuario']['clave'], 'sha1', true);
			}
			
			if ( isset($this->data['SmuqUsuario']['login']) )
			{
				$this->data['SmuqUsuario']['login'] = strtolower($this->data['SmuqUsuario']['login']);
			}
			
			$smuq_usuario = $this->SmuqUsuario->read(null, $this->data['SmuqUsuario']['cedula']);
			if ( empty($smuq_usuario) )
			{
				$this->data['SmuqUsuario']['id_grupo'] = 3;
			}
			
			if ( $this->SmuqUsuario->save($this->data) )
			{
				$this->Session->write('Controlador.resultado_guardar', 'exito');
			}
			else
			{
				$this->Session->write('Controlador.resultado_guardar', 'error');
			}
			$this->redirect($this->referer());
		}
	}
	
	//--------------------------------------------------------------------------
	
	function eliminar()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Equipo');
		$this->loadModel('Solicitud');
		
		$this->Session->write('Controlador.resultado_guardar', 'error');
		
		if ( !empty($this->data) )
		{
			// Primero se verifica si hay solicitudes y equipos dependientes.
			$solicitud = $this->Solicitud->find('first', array('conditions'=>array('Solicitud.cedula_usuario'=>$this->data['SmuqUsuario']['cedula'])));
			if ( empty($solicitud) )
			{
				$equipo = $this->Equipo->find('first', array('conditions'=>array('Equipo.cedula_usuario'=>$this->data['SmuqUsuario']['cedula'])));
				if ( empty($equipo) )
				{
					if ( $this->SmuqUsuario->delete($this->data['SmuqUsuario']['cedula']) )
					{
						$this->Session->write('Controlador.resultado_guardar', 'exito');
					}
				}
			}
		}
		$this->redirect($this->referer());
	}
	
	//--------------------------------------------------------------------------
	
	function __crear_filas($usuarios_info)
	{
		$datos_json['resultado'] = false;
		if ( isset($usuarios_info[0]['Usuario']) )
		{
			$filas_tabla = '';
			foreach ( $usuarios_info as $usuario )
			{
				$filas_tabla .= '<tr><td><a target="_self" href="/smuq_usuarios/ver/'.$usuario['Usuario']['Usu_cedula'].'" title="Ver información completa del usuario">'.mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</a></td>';
				$filas_tabla .= '<td>'.$usuario['Usuario']['Usu_cedula'].'</td>';
				$filas_tabla .= '<td>'.$usuario['Usuario']['Usu_login'].'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($usuario['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>Usuario de Dependencia</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($usuarios_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}
	
	//--------------------------------------------------------------------------
	
	function _crear_filas_xls($usuarios_info)
	{
		$datos_json['resultado'] = false;
		if ( isset($usuarios_info[0]['Usuario']) )
		{
			$filas_tabla = '';
			foreach ( $usuarios_info as $usuario )
			{
				$smuq_usr_info = $this->SmuqUsuario->find('first', array
				(
					'fields' => array('SmuqUsuario.cargo', 'SmuqUsuario.email'),
					'conditions' => array('SmuqUsuario.cedula' => $usuario['Usuario']['Usu_cedula'])
				));
				$cargo = 'No Disponible';
				$correo = 'No Disponible';
				if ( !empty($smuq_usr_info['SmuqUsuario']['cargo']) )
				{
					$cargo = $smuq_usr_info['SmuqUsuario']['cargo'];
				}
				if ( !empty($smuq_usr_info['SmuqUsuario']['email']) )
				{
					$correo = $smuq_usr_info['SmuqUsuario']['email'];
				}
				$filas_tabla .= '<tr><td>'.mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$usuario['Usuario']['Usu_cedula'].'</td>';
				$filas_tabla .= '<td>'.$usuario['Usuario']['Usu_login'].'</td>';
				$filas_tabla .= '<td>'.$correo.'</td>';
				$filas_tabla .= '<td>Usuario de Dependencia</td>';
				$filas_tabla .= '<td>'.mb_convert_case($usuario['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$cargo.'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($usuarios_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}
	
	//--------------------------------------------------------------------------
	
	function _crear_filas_adm($usuarios_info)
	{
		$datos_json['resultado'] = false;
		if ( isset($usuarios_info[0]['SmuqUsuario']) )
		{
			$filas_tabla = '';
			foreach ( $usuarios_info as $usuario )
			{
				$filas_tabla .= '<tr><td><a target="_self" href="/smuq_usuarios/ver/'.$usuario['SmuqUsuario']['cedula'].'" title="Ver información completa del usuario">'.$usuario['SmuqUsuario']['nombre'].'</a></td>';
				$filas_tabla .= '<td>'.$usuario['SmuqUsuario']['cedula'].'</td>';
				$filas_tabla .= '<td>'.$usuario['SmuqUsuario']['login'].'</td>';
				$filas_tabla .= '<td>Equipo Mantenimiento - Activos Fijos</td>';
				$filas_tabla .= '<td>'.$usuario['Grupo']['name'].'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($usuarios_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}
	
	//--------------------------------------------------------------------------
	
	function _crear_filas_adm_xls($usuarios_info)
	{
		$datos_json['resultado'] = false;
		if ( isset($usuarios_info[0]['SmuqUsuario']) )
		{
			$filas_tabla = '';
			foreach ( $usuarios_info as $usuario )
			{
				$correo = 'No Disponible';
				if ( !empty($usuario['SmuqUsuario']['email']) )
				{
					$correo = $usuario['SmuqUsuario']['email'];
				}
				$cargo = 'No Disponible';
				if ( !empty($usuario['SmuqUsuario']['cargo']) )
				{
					$cargo = $usuario['SmuqUsuario']['cargo'];
				}
				
				$filas_tabla .= '<tr><td>'.$usuario['SmuqUsuario']['nombre'].'</td>';
				$filas_tabla .= '<td>'.$usuario['SmuqUsuario']['cedula'].'</td>';
				$filas_tabla .= '<td>'.$usuario['SmuqUsuario']['login'].'</td>';
				$filas_tabla .= '<td>'.$correo.'</td>';
				$filas_tabla .= '<td>'.$usuario['Grupo']['name'].'</td>';
				$filas_tabla .= '<td>Equipo Mantenimiento - Activos Fijos</td>';
				$filas_tabla .= '<td>'.$cargo.'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($usuarios_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}
	
	//--------------------------------------------------------------------------
	
	function con_cedula($cedula)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Dependencia');
		$this->SmuqUsuario->recursive = 2;
		$filas_tabla = 'false';
		
		$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.Usu_cedula' => $cedula)));
		$smuq_usuario = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.cedula' => $cedula)));
		
		if ( !empty($usuario) )	// Si se encuentra en CENCOS.
		{
			if ( empty($smuq_usuario) )
			{
				$smuq_usuario['SmuqUsuario']['email'] = 'No disponible';
				$smuq_usuario['SmuqUsuario']['telefono'] = 'No disponible';
				$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible';
			}
			else
			{
				if ( $smuq_usuario['SmuqUsuario']['email'] == '' )
				{
					$smuq_usuario['SmuqUsuario']['email'] = 'No disponible';
				}
				if ( $smuq_usuario['SmuqUsuario']['cargo'] == '' )
				{
					$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible';
				}
				if ( $smuq_usuario['SmuqUsuario']['telefono'] == '' )
				{
					$smuq_usuario['SmuqUsuario']['telefono'] = 'No disponible';
				}
			}
			
			$dep_info = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.Cencos_id'=>$usuario['Usuario']['Usu_Cencos_id'])));
			if ( empty($dep_info) )
			{
				$dep_info['Edificio']['name'] = 'No tiene un edificio asignado.';
			}
			
			$filas_tabla = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
			<tr align="left">
				<td class="subtitulo" width="50">Nombre:</td>
				<td width="210">'.mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Cédula:</td>
				<td width="210">'.$usuario['Usuario']['Usu_cedula'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Login:</td>
				<td width="210">'.$usuario['Usuario']['Usu_login'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Email:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['email'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Teléfono:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['telefono'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr><td height="1" class="linea" colspan="2"/></tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Tipo de Usuario:</td>
				<td>Usuario de Dependencia</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Edificio:</td>
				<td>'.$dep_info['Edificio']['name'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Dependencia:</td>
				<td>'.mb_convert_case($usuario['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Cargo:</td>
				<td>'.$smuq_usuario['SmuqUsuario']['cargo'].'</td>
			</tr>
			</tbody></table>';
		}
		else if ( !empty($smuq_usuario) )	// Entonces es un administrador en SMUQ ?
		{
			$dep_info = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.Cencos_id'=>1)));
			if ( empty($dep_info) )
			{
				$dep_info['Edificio']['name'] = 'No tiene un edificio asignado.';
				$dep_info['Dependencia']['name'] = 'No tiene una dependencia asignada.';
			}
			else
			{
				$dep_info['Dependencia']['name'] = 'SMUQ-Lab (Mantenimiento)';
			}
			
			$filas_tabla = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
			<tr align="left">
				<td class="subtitulo" width="50">Nombre:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['nombre'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Cédula:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['cedula'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Login:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['login'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Email:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['email'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Teléfono:</td>
				<td width="210">'.$smuq_usuario['SmuqUsuario']['telefono'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr><td height="1" class="linea" colspan="2"/></tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Tipo de Usuario:</td>
				<td>'.$smuq_usuario['Grupo']['name'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Edificio:</td>
				<td>'.$dep_info['Edificio']['name'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Dependencia:</td>
				<td>'.$dep_info['Dependencia']['name'].'</td>
			</tr>
			<tr><td height="10" colspan="2"/></tr>
			<tr align="left">
				<td class="subtitulo" width="50">Cargo:</td>
				<td>'.$smuq_usuario['SmuqUsuario']['cargo'].'</td>
			</tr>
			</tbody></table>';
		}
		return $filas_tabla;
	}
	
	//--------------------------------------------------------------------------
	
	function ver($cedula)
	{
		$this->loadModel('Dependencia');
		$this->SmuqUsuario->recursive = 2;
		$usuario_info = array();
		$usuario = $this->Usuario->find('first', array('conditions' => array('Usuario.Usu_cedula' => $cedula)));
		$smuq_usuario = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.cedula' => $cedula)));
		if ( empty($smuq_usuario) )
		{
			$usuario_info['Usuario']['email'] = 'No disponible';
			$usuario_info['Usuario']['cargo'] = 'No disponible';
		}
		else
		{
			if ( $smuq_usuario['SmuqUsuario']['email'] == '' )
			{
				$usuario_info['Usuario']['email'] = 'No disponible';
			}
			else
			{
				$usuario_info['Usuario']['email'] = $smuq_usuario['SmuqUsuario']['email'];
			}
			
			if ( $smuq_usuario['SmuqUsuario']['cargo'] == '' )
			{
				$usuario_info['Usuario']['cargo'] = 'No disponible';
			}
			else
			{
				$usuario_info['Usuario']['cargo'] = $smuq_usuario['SmuqUsuario']['cargo'];
			}
		}
		
		if ( !empty($usuario) )	// Si se encuentra en CENCOS.
		{
			$dep_info = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.Cencos_id'=>$usuario['Usuario']['Usu_Cencos_id'])));
			if ( empty($dep_info) )
			{
				$dep_info['Edificio']['name'] = 'No tiene un edificio asignado.';
			}
			
			// Trasladamos valores al arreglo $usuario_info
			$usuario_info['Usuario']['nombre'] = mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
			$usuario_info['Usuario']['cedula'] = $usuario['Usuario']['Usu_cedula'];
			$usuario_info['Usuario']['login'] = $usuario['Usuario']['Usu_login'];
			$usuario_info['Grupo']['name'] = 'Usuario de Dependencia';
			$usuario_info['Edificio']['name'] = $dep_info['Edificio']['name'];
			$usuario_info['Dependencia']['name'] = mb_convert_case($usuario['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
		}
		else if ( !empty($smuq_usuario) )	// Entonces es un administrador en SMUQ ?
		{
			$dep_info = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.Cencos_id'=>1)));
			if ( empty($dep_info) )
			{
				$dep_info['Edificio']['name'] = 'No tiene un edificio asignado.';
				$dep_info['Dependencia']['name'] = 'No tiene una dependencia asignada.';
			}
			else
			{
				$dep_info['Dependencia']['name'] = 'SMUQ-Lab (Mantenimiento)';
			}
			
			// Trasladamos valores al arreglo $usuario_info
			$usuario_info['Usuario']['nombre'] = $smuq_usuario['SmuqUsuario']['nombre'];
			$usuario_info['Usuario']['cedula'] = $smuq_usuario['SmuqUsuario']['cedula'];
			$usuario_info['Usuario']['login'] = $smuq_usuario['SmuqUsuario']['login'];
			$usuario_info['Grupo']['name'] = $smuq_usuario['Grupo']['name'];
			$usuario_info['Edificio']['name'] = $dep_info['Edificio']['name'];
			$usuario_info['Dependencia']['name'] = $dep_info['Dependencia']['name'];
		}
		
		if ( !empty($usuario) || !empty($smuq_usuario) )
		{
			$this->set('usuario', $usuario_info);
			
			$id_grupo = $this->Session->read('Usuario.id_grupo');
			if ( $id_grupo == '1' )
			{
				$opciones_menu = $this->requestAction(array('controller' => 'adm_principal',
																		  'action' => 'get_opciones_menu'));
			}
			else if ( $id_grupo == '2' )
			{
				$opciones_menu = $this->requestAction(array('controller' => 'adm_soluciones',
																		  'action' => 'get_opciones_menu'));
			}
			else if ( $id_grupo == '3' )
			{
				$opciones_menu = $this->requestAction(array('controller' => 'usr_dependencia',
																		  'action' => 'get_opciones_menu'));
			}
			$this->set('opciones_menu', $opciones_menu);
		}
		$this->set('opcion_seleccionada', 'consultar_usuarios');
	}

	//--------------------------------------------------------------------------
	
	function _buscar_smuq_usuarios($frase_busqueda, $criterio_campo, $criterio_tipo_usuario)
	{
		$condiciones = array();
		$pre_con_like = array();
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['login LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['cedula LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		$condiciones['SmuqUsuario.id_grupo'] = $criterio_tipo_usuario;
		return $this->SmuqUsuario->find('all', array
		(
			'conditions' => $condiciones,
			'order' => array('SmuqUsuario.id_grupo')
		));
	}
	
	//--------------------------------------------------------------------------
	
	function _buscar_usuarios($frase_busqueda, $criterio_campo, $criterio_dependencia)
	{
		$condiciones = array();
		$pre_con_like = array();
		if ( $criterio_dependencia != '0' )
		{
			$condiciones['Usuario.Usu_Cencos_id'] = $criterio_dependencia;
		}
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones['Usu_'.$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['Usu_nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['Usu_login LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['Usu_cedula LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		return $this->Usuario->find('all', array
		(
			'conditions' => $condiciones,
			'order' => array('Usuario.Usu_nombre')
		));
	}
	
	//--------------------------------------------------------------------------
	
	function buscar($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_tipo_usuario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con_like = array();
		$datos_json = array
		(
			'filas_tabla' => '',
			'count' => 0,
			'resultado' => false
		);
		
		if ( $criterio_tipo_usuario == '1' || $criterio_tipo_usuario == '2' )
		{
			$smuq_usuarios = $this->_buscar_smuq_usuarios($frase_busqueda, $criterio_campo, $criterio_tipo_usuario);
			$datos_json = $this->_crear_filas_adm($smuq_usuarios);
		}
		else if ( $criterio_tipo_usuario == '3' )
		{
			$usuarios = $this->_buscar_usuarios($frase_busqueda, $criterio_campo, $criterio_dependencia);
			$datos_json = $this->__crear_filas($usuarios);
		}
		else if ( $criterio_tipo_usuario == '0' )
		{
			$usuarios = $this->_buscar_usuarios($frase_busqueda, $criterio_campo, $criterio_dependencia);
			$smuq_usuarios = $this->_buscar_smuq_usuarios($frase_busqueda, $criterio_campo, array(1,2));
			
			$json_usrs = $this->__crear_filas($usuarios);
			$json_smuqusrs = $this->_crear_filas_adm($smuq_usuarios);
			
			if ( $json_usrs['resultado'] == true )
			{
				$datos_json['filas_tabla'] .= $json_usrs['filas_tabla'];
				$datos_json['count'] += $json_usrs['count'];
				$datos_json['resultado'] = true;
			}
			if ( $json_smuqusrs['resultado'] == true )
			{
				$datos_json['filas_tabla'] .= $json_smuqusrs['filas_tabla'];
				$datos_json['count'] += $json_smuqusrs['count'];
				$datos_json['resultado'] = true;
			}
		}
		return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
	
	function buscar_xls($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_tipo_usuario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con_like = array();
		$datos_json = array
		(
			'filas_tabla' => '',
			'count' => 0,
			'resultado' => false
		);
		
		if ( $criterio_tipo_usuario == '1' || $criterio_tipo_usuario == '2' )
		{
			$smuq_usuarios = $this->_buscar_smuq_usuarios($frase_busqueda, $criterio_campo, $criterio_tipo_usuario);
			$datos_json = $this->_crear_filas_adm_xls($smuq_usuarios);
		}
		else if ( $criterio_tipo_usuario == '3' )
		{
			$usuarios = $this->_buscar_usuarios($frase_busqueda, $criterio_campo, $criterio_dependencia);
			$datos_json = $this->_crear_filas_xls($usuarios);
		}
		else if ( $criterio_tipo_usuario == '0' )
		{
			$usuarios = $this->_buscar_usuarios($frase_busqueda, $criterio_campo, $criterio_dependencia);
			$smuq_usuarios = $this->_buscar_smuq_usuarios($frase_busqueda, $criterio_campo, array(1,2));
			
			$json_usrs = $this->_crear_filas_xls($usuarios);
			$json_smuqusrs = $this->_crear_filas_adm_xls($smuq_usuarios);
			
			if ( $json_usrs['resultado'] == true )
			{
				$datos_json['filas_tabla'] .= $json_usrs['filas_tabla'];
				$datos_json['count'] += $json_usrs['count'];
				$datos_json['resultado'] = true;
			}
			if ( $json_smuqusrs['resultado'] == true )
			{
				$datos_json['filas_tabla'] .= $json_smuqusrs['filas_tabla'];
				$datos_json['count'] += $json_smuqusrs['count'];
				$datos_json['resultado'] = true;
			}
		}
		return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
}
?>
