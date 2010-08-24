<?php
class UsrDependenciaController extends AppController
{
	var $uses = array('Notificacion');
	var $components = array('Tiempo', 'RequestHandler');
	var $helpers = array('Select', 'Javascript');
	var $id_grupo = '3';
	var $opciones_menu = array
	(
		array('titulo' => 'Solicitar un Mantenimiento',
				'link' => '/usr_dependencia/crear_solicitud_mantenimiento',
				'id' => 'crear_solicitud_mantenimiento'),
		array('titulo' => 'Consultar Solicitudes',
				'link' => '/usr_dependencia/consultar_solicitudes',
				'id' => 'consultar_solicitudes'),
		array('titulo' => 'Consultar Equipos',
				'link' => '/usr_dependencia/consultar_equipos',
				'id' => 'consultar_equipos'),
		array('titulo' => 'Actualizar Datos de Usuario',
				'link' => '/usr_dependencia/actualizar_datos_usuario',
				'id' => 'actualizar_datos_usuario'),
		array('titulo' => 'Ayuda / Manual',
				'link' => '/ayuda',
				'id' => 'ayuda'),
		array('titulo' => 'Cerrar Sesión',
				'link' => '/logout/',
				'id' => 'ayuda')
	);
	
	//--------------------------------------------------------------------------
	
	function beforeRender()
	{
		$this->disableCache();
		$this->set('opciones_menu', $this->__crear_menu());
		
		// Se averigua si tiene notificaciones sin leer
		$notificaciones_count = $this->Notificacion->find('count', array('conditions'=>array('Notificacion.id_usuario'=>$this->Session->read('Usuario.id'),
																														'Notificacion.leido'=>'no')));
		if ( $notificaciones_count == 0 )
		{
			$this->set('msj_notificaciones', '');
			$this->set('display_notificaciones', 'none');
		}
		else
		{
			$this->set('msj_notificaciones', 'Tienes '.$notificaciones_count.' notificacion(es) sin leer.');
			$this->set('display_notificaciones', 'block');
		}
	}
	
	//--------------------------------------------------------------------------
	
	function __crear_menu()
	{
		$opciones_menu = '';
		foreach ( $this->opciones_menu as $opcion )
		{
			$opciones_menu = $opciones_menu.'<li id="'.$opcion['id'].'"><a href="'.$opcion['link'].'">'.$opcion['titulo'].'</a></li>';
		}
		
		return $opciones_menu;
	}
	
	//--------------------------------------------------------------------------
	
	function get_opciones_menu()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		return $this->__crear_menu();
	}
	
	//--------------------------------------------------------------------------
	
	function index()
	{
	}
	
	//--------------------------------------------------------------------------
	
	function ver_notificaciones()
	{
		$this->loadModel('Solicitud');
		$notificaciones = $this->Notificacion->find('all', array('conditions'=>array('Notificacion.id_usuario'=>$this->Session->read('Usuario.id'),
																											'Notificacion.leido'=>'no'),
																					'order'=>array('Notificacion.created DESC')));
		$filas_js = '';
		if ( !empty($notificaciones) )
		{
			$i = 1;
			$filas_tabla = '';
			foreach ( $notificaciones as $notificacion )
			{
				$filas_tabla .= '<tr><td height="10" /></tr><tr><td height="1" class="linea" /></tr><tr><td height="10" /></tr><tr align="left"><td><div id="notificacion_'.$i.'"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
									<tr align="left"><td class="subtitulo" colspan="3"><a href="/solicitudes/ver/'.$notificacion['Notificacion']['id_solicitud'].'" title="Ver información de la solicitud" >Solicitud #'.$notificacion['Notificacion']['id_solicitud'].'</a></td></tr>
									<tr align="left"><td class="subtitulo" width="120">Servicio:</td><td width="310">'.$this->Solicitud->tipo_servicio[$notificacion['Solicitud']['tipo_servicio']].'</td>
									<td rowspan="4" align="center" valign="center">
									
										<div id="div_marcar_'.$i.'" class="link_marcar" style="display:block;">
											<input type="button" id="boton_'.$i.'" value="Marcar como leida" />
										</div>
										<div id="div_marcado_'.$i.'" style="display:none;">Se ha marcado como leida.</div></td></tr>
									<tr align="left"><td class="subtitulo">Placa de inventario:</td><td>'.$notificacion['Solicitud']['placa_inventario'].'</td></tr>
									<tr align="left"><td class="subtitulo">Fecha de solicitud:</td><td>'.$notificacion['Solicitud']['created'].'</td></tr>
									<tr align="left"><td class="subtitulo">Fecha de solución:</td><td>'.$notificacion['Solicitud']['solucionada'].'</td></tr>
									</tbody></table></div></td></tr>';
									
				$filas_js .= 'jQuery("#boton_'.$i.'").click(function()
				{
					jQuery.ajax(
					{
						type: "POST",
						url: "/notificaciones/marcar_como_leida/'.$notificacion['Notificacion']['id'].'",
						dataType: "text",
						cache: false,
						async: false,
						success: function(resultado)
						{
							if ( resultado == "true" )
							{
								jQuery("#div_marcar_'.$i.'").hide();
								jQuery("#div_marcado_'.$i.'").show();
							}
						}
					});
				});';
									
				$i++;
			}
			$filas_js = 'jQuery(document).ready(function(){'.$filas_js.'});';
		}
		else
		{
			$filas_tabla = '<tr><td height="10" /></tr><tr><td height="1" class="linea" /></tr><tr><td height="10" /></tr><tr><td align="center">No se han encontrado notificaciones sin revizar.</td></tr>';
		}
		$this->set('filas', $filas_tabla);
		$this->set('filas_js', $filas_js);
	}
	
	//--------------------------------------------------------------------------
	
	function crear_solicitud_mantenimiento()
	{
		$this->loadModel('Usuario');
		$this->loadModel('SmuqUsuario');
		$this->loadModel('Dependencia');
		$this->loadModel('CentroCosto');
		
		$this->SmuqUsuario->recursive = 0;
		$cedula = $this->Session->read('Usuario.cedula');
		
		$usuario = $this->Usuario->find('first', array
		(
			'fields' => array('Usuario.Usu_nombre', 'Usuario.Usu_Cencos_id'),
			'conditions' => array('Usuario.Usu_cedula' => $cedula)
		));
		$smuq_usuario = $this->SmuqUsuario->find('first', array
		(
			'fields' => array
			(
				'SmuqUsuario.email',
				'SmuqUsuario.telefono',
				'SmuqUsuario.cargo'
			),
			'conditions' => array('SmuqUsuario.cedula' => $cedula)
		));
		
		if ( !empty($usuario) )	// Si se encuentra en CENCOS.
		{
			$info_usuario = array();
			if ( empty($smuq_usuario) )
			{
				$info_usuario['email'] = 'No disponible';
				$info_usuario['cargo'] = 'No disponible';
				$info_usuario['telefono'] = 'No disponible';
			}
			else
			{
				if ( $smuq_usuario['SmuqUsuario']['email'] == '' )
				{
					$info_usuario['email'] = 'No disponible';
				}
				else
				{
					$info_usuario['email'] = $smuq_usuario['SmuqUsuario']['email'];
				}
				
				if ( $smuq_usuario['SmuqUsuario']['cargo'] == '' )
				{
					$info_usuario['cargo'] = 'No disponible';
				}
				else
				{
					$info_usuario['cargo'] = $smuq_usuario['SmuqUsuario']['cargo'];
				}
				
				if ( $smuq_usuario['SmuqUsuario']['telefono'] == '' )
				{
					$info_usuario['telefono'] = 'No disponible';
				}
				else
				{
					$info_usuario['telefono'] = $smuq_usuario['SmuqUsuario']['telefono'];
				}
			}
			$info_usuario['nombre'] = mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
			$dep_info = $this->Dependencia->find('first', array('conditions'=>array('Dependencia.Cencos_id'=>$usuario['Usuario']['Usu_Cencos_id'])));
			if ( empty($dep_info) )
			{
				$info_usuario['edificio'] =  'No tiene un edificio asignado.';
			}
			else
			{
				$info_usuario['edificio'] = $dep_info['Edificio']['name'];
			}
			$cenco = $this->CentroCosto->find('first', array
			(
				'fields' => array('CentroCosto.Cencos_nombre'),
				'conditions' => array('CentroCosto.Cencos_id'=>$usuario['Usuario']['Usu_Cencos_id'])
			));
			$info_usuario['dependencia'] = mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
			$info_usuario['cedula'] = $cedula;
			$info_usuario['Cencos_id'] = $usuario['Usuario']['Usu_Cencos_id'];
			$this->set('usuario', $info_usuario);
		}
	
		$this->set('fecha_hoy', $this->Tiempo->fecha_espaniol(date('Y-n-j-N')));
		$this->set('opcion_seleccionada', 'crear_solicitud_mantenimiento');
		
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'La solicitud de mantenimiento fue creada con éxito.');
				
				if ( $this->Session->check('Controlador.id_solicitud_recien_creada') )
				{
					$this->set('id_solicitud_nueva', $this->Session->read('Controlador.id_solicitud_recien_creada'));
				}
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'La solicitud de mantenimiento no pudo ser creada.');
			}
			else
			{
				$this->set('display_notificacion', 'none');
				$this->set('clase_notificacion', '');
				$this->set('mensaje_notificacion', '');
			}
		}
		else
		{
			$this->set('display_notificacion', 'none');
			$this->set('clase_notificacion', '');
			$this->set('mensaje_notificacion', '');
		}
		
		$this->Session->write('Controlador.resultado_guardar', '');
	}
	
	//--------------------------------------------------------------------------
	
	function consultar_solicitudes()
	{
		$this->loadModel('Solicitud');
		$opciones_años = '';
		$opciones_meses = '';
		$opciones_servicios = '';
		
		// primero obtenemos los años existentes de las solucionadas.
		$años = $this->Solicitud->query("SELECT YEAR(created) AS year FROM solicitudes GROUP BY YEAR(created)");
		if ( !empty($años) )
		{
			foreach ( $años as $año )
			{
				$opciones_años .= '<option value="'.$año[0]['year'].'">'.$año[0]['year'].'</option>';
			}
			$opciones_años = '<option value="0">Todos los años</option>'.$opciones_años;
		}
		
		foreach ( $this->meses as $num_mes => $mes )
		{
			$opciones_meses .= '<option value="'.$num_mes.'">'.$mes.'</option>';
		}
		$opciones_meses = '<option value="0">Todos los meses</option>'.$opciones_meses;
		
		$this->set('opciones_años', $opciones_años);
		$this->set('opciones_meses', $opciones_meses);
		$this->set('opciones_servicios', $this->requestAction('/solicitudes/cargar_tipos_de_servicio'));
		$this->set('opcion_seleccionada', 'consultar_solicitudes');
	}
	
	//--------------------------------------------------------------------------
	
	function consultar_equipos()
	{
		$this->loadModel('CentroCosto');
		$opciones_cencos = '<option value="0">Todas las dependencias</option>'.
								$this->requestAction('/dependencias/cargar_select_dependencias_usuarios');
		$this->set('opciones_dependencias', $opciones_cencos);
		$this->set('opcion_seleccionada', 'consultar_equipos');
	}
	
	//--------------------------------------------------------------------------
	
	function actualizar_datos_usuario()
	{
		$this->set('opcion_seleccionada', 'actualizar_datos_usuario');
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'Los cambios fueron guardados.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'No se pudo guardar los cambios.');
			}
			else
			{
				$this->set('display_notificacion', 'none');
				$this->set('clase_notificacion', '');
				$this->set('mensaje_notificacion', '');
			}
		}
		else
		{
			$this->set('display_notificacion', 'none');
			$this->set('clase_notificacion', '');
			$this->set('mensaje_notificacion', '');
		}
		
		$this->Session->write('Controlador.resultado_guardar', '');
		
		// Se obtiene la info del usuario...
		$this->loadModel('Usuario');
		$this->loadModel('SmuqUsuario');
		$this->SmuqUsuario->recursive = 2;
		$cedula = $this->Session->read('Usuario.cedula');
		$usuario_info = array();
		$usuario = $this->Usuario->find('first', array
		(
			'fields' => array
			(
				'Usuario.Usu_nombre',
				'Usuario.Usu_login'
			),
			'conditions' => array('Usuario.Usu_cedula' => $cedula)
		));
		$smuq_usuario = $this->SmuqUsuario->find('first', array
		(
			'fields' => array
			(
				'SmuqUsuario.email',
				'SmuqUsuario.cargo',
				'SmuqUsuario.telefono'
			),
			'conditions' => array('SmuqUsuario.cedula' => $cedula)
		));
	
		$usuario_info['email'] = '';
		$usuario_info['cargo'] = '';
		$usuario_info['telefono'] = '';
		if ( $smuq_usuario['SmuqUsuario']['email'] != '' )
		{
			$usuario_info['email'] = $smuq_usuario['SmuqUsuario']['email'];
		}
		if ( $smuq_usuario['SmuqUsuario']['cargo'] != '' )
		{
			$usuario_info['cargo'] = $smuq_usuario['SmuqUsuario']['cargo'];
		}
		if ( $smuq_usuario['SmuqUsuario']['telefono'] != '' )
		{
			$usuario_info['telefono'] = $smuq_usuario['SmuqUsuario']['telefono'];
		}
	
		// Trasladamos valores al arreglo $usuario_info
		$usuario_info['nombre'] = mb_convert_case($usuario['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
		$usuario_info['cedula'] = $cedula;
		$usuario_info['login'] = $usuario['Usuario']['Usu_login'];
		if ( !empty($usuario) || !empty($smuq_usuario) )
		{
			$this->set('usuario', $usuario_info);
		}
	}
}
?>
