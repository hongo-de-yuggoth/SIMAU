<?php
class AdmSolucionesController extends AppController
{
	var $name = 'AdmSoluciones';
	var $uses = array();
	var $components = array('Tiempo');
	var $helpers = array('Select', 'Javascript');
	var $id_grupo = '2';
	var $opciones_menu = array
	(
		array('titulo' => 'Crear una Solicitud de Mantenimiento',
				'link' => '/adm_soluciones/crear_solicitud_mantenimiento',
				'id' => 'crear_solicitud_mantenimiento'),
		array('titulo' => 'Consultar Solicitudes',
				'link' => '/adm_soluciones/consultar_solicitudes',
				'id' => 'consultar_solicitudes'),
		array('titulo' => 'Consultar Equipos',
				'link' => '/adm_soluciones/consultar_equipos',
				'id' => 'consultar_equipos'),
		array('titulo' => 'Actualizar mis Datos',
				'link' => '/adm_soluciones/actualizar_datos_usuario',
				'id' => 'actualizar_datos_usuario'),
		array('titulo' => 'Ayuda / Manual',
				'link' => '/ayuda/',
				'id' => 'ayuda'),
		array('titulo' => 'Cerrar Sesión',
				'link' => '/logout/',
				'id' => 'cerrar')
	);

	//--------------------------------------------------------------------------

	function beforeRender()
	{
		$this->disableCache();
		$this->set('opciones_menu', $this->__crear_menu());
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

	function crear_solicitud_mantenimiento()	//;)
	{
		$options = $this->requestAction(array
		(
			'controller'=>'edificios',
			'action'=>'cargar_select_edificios_con_dependencias_con_usuarios'
		));
		$this->set('edificios_info', $options.'<option value="0">Dependencias sin edificio asignado</option>');
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

		$this->render('/adm_principal/crear_solicitud_mantenimiento');
	}

	//--------------------------------------------------------------------------

	function consultar_solicitudes()	//;)
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
		$this->render('/adm_principal/consultar_solicitudes');
	}

	//--------------------------------------------------------------------------

	function consultar_equipos()	//;)
	{
		$this->loadModel('CentroCosto');
		$opciones_cencos = '<option value="0">Todas las dependencias</option>'.
								$this->requestAction('/dependencias/cargar_select_dependencias_usuarios');
		$this->set('opciones_dependencias', $opciones_cencos);
		$this->set('opcion_seleccionada', 'consultar_equipos');
		$this->render('/adm_principal/consultar_equipos');
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

		$this->render('/adm_principal/actualizar_datos_usuario');
	}
}
?>
