<?php
class AdmPrincipalController extends AppController
{
	var $name = 'AdmPrincipal';
	var $uses = array();
	var $components = array('Tiempo');
	var $helpers = array('Select', 'Javascript');
	var $id_grupo = '1';
	var $opciones_menu = array
	(
		array('titulo' => 'Crear Solicitud de Mantenimiento',
				'link' => '/adm_principal/crear_solicitud_mantenimiento',
				'id' => 'crear_solicitud_mantenimiento'),
		array('titulo' => 'Administrar Equipos',
				'link' => '/adm_principal/modificar_equipo',
				'id' => 'crear_equipo'),
		array('titulo' => 'Administrar Usuarios',
				'link' => '/adm_principal/ingresar_usuario',
				'id' => 'ingresar_usuario'),
		array('titulo' => 'Administrar Edificios y Dependencias',
				'link' => '/adm_principal/adm_edificios',
				'id' => 'adm_edificios'),
		array('titulo' => 'Consultar Solicitudes',
				'link' => '/adm_principal/consultar_solicitudes',
				'id' => 'consultar_solicitudes'),
		array('titulo' => 'Consultar Equipos',
				'link' => '/adm_principal/consultar_equipos',
				'id' => 'consultar_equipos'),
		array('titulo' => 'Consultar Usuarios',
				'link' => '/adm_principal/consultar_usuarios',
				'id' => 'consultar_usuarios'),
		array('titulo' => 'Consultar Reportes Estadísticos',
				'link' => '/adm_principal/consultar_reportes',
				'id' => 'consultar_reportes'),
		array('titulo' => 'Actualizar Datos de Usuario',
				'link' => '/adm_principal/actualizar_datos_usuario',
				'id' => 'actualizar_datos_usuario'),
		array('titulo' => 'Ayuda / Manual',
				'link' => '/ayuda/',
				'id' => 'ayuda'),
		array('titulo' => 'Cerrar Sesión',
				'link' => '/logout',
				'id' => 'cerrar')
	);
	var $meses = array
	(
		1=>'Enero',
		2=>'Febrero',
		3=>'Marzo',
		4=>'Abril',
		5=>'Mayo',
		6=>'Junio',
		7=>'Julio',
		8=>'Agosto',
		9=>'Septiembre',
		10=>'Octubre',
		11=>'Noviembre',
		12=>'Diciembre'
	);
	
	//--------------------------------------------------------------------------
	
	function beforeRender()
	{
		$opciones_menu_2 = '<li><a href="/adm_principal/modificar_equipo">Modificar un Equipo</a></li>
		<li><a href="/adm_principal/archivos_equipo">Documentos</a></li>';
		$this->set('opciones_menu', $this->__crear_menu());
		$this->set('opciones_menu_2', $opciones_menu_2);
	}
	
	//--------------------------------------------------------------------------
	
	function __crear_menu()
	{
		$opciones_menu = '';
		foreach ( $this->opciones_menu as $opcion )
		{
			$opciones_menu = $opciones_menu.'<li id="'.$opcion['id'].'" class="celdano" name="hovermenu"><a href="'.$opcion['link'].'">'.$opcion['titulo'].'</a></li>';
		}
		
		return $opciones_menu;
	}
	
	//--------------------------------------------------------------------------
	
	function index()
	{
	}
	
	//--------------------------------------------------------------------------
	
	function get_opciones_menu()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		return $this->__crear_menu();
	}
	
	//--------------------------------------------------------------------------
	
	function crear_solicitud_mantenimiento()	// ;)
	{
		$this->set('edificios_info', $this->requestAction(array('controller'=>'edificios',
																				  'action'=>'cargar_select_edificios_con_dependencias_con_usuarios')).'<option value="0">Dependencias sin edificio asignado</option>');
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
	
	function modificar_equipo()	// ;)
	{
		$this->set('opcion_seleccionada', 'crear_equipo');
		
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'Se han guardado los cambios.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'No se pudieron guardar los cambios.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error_imagen' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'El equipo no pudo ser modificado. Hubo un error con el guardado de la imagen.');
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
	
	function archivos_equipo()	// ;)
	{
		$this->set('opcion_seleccionada', 'crear_equipo');
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'Se ha guardado el archivo.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'No se pudo guardar el archivo.');
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
	
	function ingresar_usuario()	// ;)
	{
		$this->set('opcion_seleccionada', 'ingresar_usuario');
		
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'El usuario fue creado con éxito.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'El usuario no pudo ser creado.');
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
		$this->Session->del('Controlador.resultado_guardar');
	}
	
	//--------------------------------------------------------------------------
	
	function modificar_usuario()	// ;)
	{
		$this->set('opcion_seleccionada', 'ingresar_usuario');
		
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'Los cambios han sido guardados con éxito.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'El usuario no pudo ser modificado.');
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
	
	function eliminar_usuario()	// ;)
	{
		$this->set('opcion_seleccionada', 'ingresar_usuario');
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				$this->set('mensaje_notificacion', 'Se ha eliminado el usuario.');
			}
			else if ( $this->Session->read('Controlador.resultado_guardar') == 'error' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-error');
				$this->set('mensaje_notificacion', 'No se pudo eliminar el usuario.');
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
	
	function adm_edificios()	// ;)
	{
		$this->set('opcion_seleccionada', 'adm_edificios');
	}
	
	//--------------------------------------------------------------------------
	
	function adm_dependencias()	// ;)
	{
		$this->set('opcion_seleccionada', 'adm_edificios');
	}
	
	//--------------------------------------------------------------------------
	
	function consultar_solicitudes()	// ;)
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
	
	function consultar_equipos()	// ;)
	{
		$this->loadModel('CentroCosto');
		$opciones_cencos = '<option value="0">Todas las dependencias</option>'.
								$this->requestAction('/dependencias/cargar_select_dependencias_usuarios');
		$this->set('opciones_dependencias', $opciones_cencos);
		$this->set('opcion_seleccionada', 'consultar_equipos');
	}
	
	//--------------------------------------------------------------------------
	
	function consultar_usuarios()	// ;)
	{
		$this->loadModel('Grupo');
		$this->loadModel('CentroCosto');
		$opciones_cencos = '<option value="0">Todas las dependencias</option>'.
								$this->requestAction('/dependencias/cargar_select_dependencias_usuarios');
		
		$opciones_tipo_usuario = '<option value="0">Todos los tipos de usuario</option>';
		$grupos = $this->Grupo->find('list');
		foreach ( $grupos as $id_grupo => $nombre_grupo )
		{
			$opciones_tipo_usuario .= '<option value="'.$id_grupo.'">'.$nombre_grupo.'</option>';
		}
		$this->set('opciones_tipo_usuario', $opciones_tipo_usuario);
		$this->set('opciones_dependencias', $opciones_cencos);
		$this->set('opcion_seleccionada', 'consultar_usuarios');
	}
	
	//--------------------------------------------------------------------------
	
	function consultar_reportes()	// ;)
	{
		$this->loadModel('SmuqUsuario');
		$this->loadModel('Solicitud');
		
		$opciones_cencos = $this->requestAction('/dependencias/cargar_select_dependencias_usuarios');
		//------------------------------------------------------------------------
		$tecnicos = $this->SmuqUsuario->find('all', array
		(
			'recursive' => 0,
			'fields' => array('SmuqUsuario.cedula', 'SmuqUsuario.nombre'),
			'conditions' => array('SmuqUsuario.id_grupo' => array(1,2)),
			'order' => array('SmuqUsuario.nombre')
		));
		if ( !empty($tecnicos) )
		{
			$html = '';
			foreach ( $tecnicos as $tecnico )
			{
				$html .= '<option value="'.$tecnico['SmuqUsuario']['cedula'].'">'.$tecnico['SmuqUsuario']['nombre'].'</option>';
			}
			$this->set('select_tecnicos', $html);
		}
		//------------------------------------------------------------------------
		// Obtenemos los años existentes de Solicitud.
		$listado_años = '';
		$html = '';
		$años = $this->Solicitud->query("SELECT YEAR(solucionada) AS year FROM solicitudes WHERE estado='s' GROUP BY YEAR(solucionada)");
		if ( !empty($años) )
		{
			foreach ( $años as $año )
			{
				$listado_años .= $año[0]['year'].',';
				$html .= '<option value="'.$año[0]['year'].'">'.$año[0]['year'].'</option>';
			}
			$listado_años = substr($listado_años, 0, -1);
		}
		else
		{
			$listado_años = 'No hay solicitudes solucionadas';
			$html = '<option value="">No hay solicitudes solucionadas</option>';
		}
		$this->set('listado_años', $listado_años);
		$this->set('select_año_inicial', $html);
		//------------------------------------------------------------------------
		
		$this->set('opcion_seleccionada', 'consultar_reportes');
		$this->set('opciones_dependencias', $opciones_cencos);
	}
	
	//--------------------------------------------------------------------------
	
	function actualizar_datos_usuario()	// ;)
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
		$this->loadModel('SmuqUsuario');
		$this->SmuqUsuario->recursive = 0;
		
		$smuq_usuario = $this->SmuqUsuario->find('first', array('conditions' => array('SmuqUsuario.cedula' => $this->Session->read('Usuario.cedula'))));
		if ( !empty($smuq_usuario) )
		{
			$tipos_usuario = Configure::read('TiposUsuario');
			$smuq_usuario['SmuqUsuario']['tipo_usuario'] = $tipos_usuario[$smuq_usuario['SmuqUsuario']['id_grupo']];
			if ( $smuq_usuario['SmuqUsuario']['email'] == '' )
			{
				$smuq_usuario['SmuqUsuario']['email'] = 'No disponible';
			}
			if ( $smuq_usuario['SmuqUsuario']['cargo'] == '' )
			{
				$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible';
			}
			$this->set('smuq_usuario', $smuq_usuario);
		}
	}
}
?>
