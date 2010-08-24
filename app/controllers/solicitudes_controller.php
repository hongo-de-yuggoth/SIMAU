<?php
class SolicitudesController extends AppController
{
	var $helpers = array('Html', 'Javascript');
	var $components = array('Tiempo', 'Email');
	var $tipo_servicio = array
	(
		'1' => 'Mantenimiento Preventivo',
		'2' => 'Mantenimiento Correctivo',
		'3' => 'Calibración / Certificación'
	);
	var $estados = array
	(
		'p' => 'Pendiente',
		's' => 'Solucionada'
	);
	
	var $encabezado_pdf =
	'<table width="100%%" cellspacing="0" cellpadding="3" border="1"><tbody>
		<tr align="left">
			<td width="85" align="center"><img src="/app/webroot/img/logouq.gif" alt="" /></td>
			<td width="*" colspan="3" align="right"><br/><br/><b>UNIVERSIDAD DEL QUINDIO<br/>SISTEMA INTEGRADO DE GESTIÓN</b></td>
		</tr>
		<tr align="right">
			<td width="85"></td>
			<td width="160"><b>Código:</b> A.AC-07.00.02.F.01</td>
			<td width="160"><b>Versión:</b> 2</td>
			<td width="*"><b>Fecha:</b> 2010/5/12</td>
		</tr>
		<tr align="left"><td width="*" align="center" colspan="4"><b>SOLICITUD DE MANTENIMIENTO, CALIBRACIÓN Y/O CERTIFICACIÓN DE EQUIPOS No.%s</b></td></tr>
	</tbody></table>';
	//--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function exportar_xls($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$datos = json_decode($this->requestAction('/solicitudes/buscar_xls/'.$frase_busqueda.'/'.$criterio_fecha.'/'.$fecha_1.'/'.$fecha_2.'/'.$mostrar_solicitudes.'/'.$criterio_campo.'/'.$tipo_servicio));
		$this->set('filas_tabla',utf8_decode($datos->filas_tabla));
		$this->set('total_registros',$datos->count);
		$this->render('exportar_xls','exportar_xls');
	}
	
	//--------------------------------------------------------------------------
	
	function usr_exportar_xls($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$datos = json_decode($this->requestAction('/solicitudes/usr_buscar_xls/'.$frase_busqueda.'/'.$criterio_fecha.'/'.$fecha_1.'/'.$fecha_2.'/'.$mostrar_solicitudes.'/'.$criterio_campo.'/'.$tipo_servicio));
		$this->set('filas_tabla',utf8_decode($datos->filas_tabla));
		$this->set('total_registros',$datos->count);
		$this->render('exportar_xls','exportar_xls');
	}
	
	//--------------------------------------------------------------------------
	
	function exportar_pdf($id_solicitud)
	{
		// Sobrescribimos para que no aparezcan los resultados de debuggin
		// ya que sino daria un error al generar el pdf.
		Configure::write('debug',0);
		
		// Se obtienen los datos de la solicitud.
		$filas_tabla = $this->requestAction('/solicitudes/info_solicitud_pdf/'.$id_solicitud);
		$this->set('filas_tabla', $filas_tabla);
		$this->set('id_solicitud',$id_solicitud);
		$this->render('exportar_pdf','exportar_pdf');
	}
	
	//--------------------------------------------------------------------------
	
	function crear()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		if ( !empty($this->data) )
		{
			if ( $this->Solicitud->save($this->data) )
			{
				$this->Session->write('Controlador.resultado_guardar', 'exito');
				$this->Session->write('Controlador.id_solicitud_recien_creada', $this->Solicitud->id);
			}
			else
			{
				$this->Session->write('Controlador.resultado_guardar', 'error');
				$this->Session->delete('Controlador.id_solicitud_recien_creada');
			}
			
			$this->redirect($this->referer());
		}
	}

	//--------------------------------------------------------------------------

	function __crear_filas($solicitudes_info)
	{
		
		$datos_json['resultado'] = false;
		if ( isset($solicitudes_info[0]['Solicitud']) )
		{
			$filas_tabla = '';
			foreach ( $solicitudes_info as $solicitud )
			{
				$ts = array();
				$ts = split(',', $solicitud['Solicitud']['tipo_servicio']);
				$listado = '';
				for ( $i=0; $i < count($ts); $i++ )
				{
					$listado .= $this->tipo_servicio[$ts[$i]].',<br>';
				}
				$solicitud['Solicitud']['tipo_servicio'] = substr($listado, 0, -5);
				$filas_tabla .= '<tr><td><a target="_self" alt="Ver información completa de la solicitud" title="Ver información completa de la solicitud" href="/solicitudes/ver/'.$solicitud['Solicitud']['id'].'">'.$solicitud['Solicitud']['id'].'</a></td>';
				$filas_tabla .= '<td>'.mb_convert_case($solicitud['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($solicitud['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['tipo_servicio'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['created'].'</td>';
				$filas_tabla .= '<td>'.$this->estados[$solicitud['Solicitud']['estado']].'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($solicitudes_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}

	//--------------------------------------------------------------------------
	
	function _crear_filas_xls($solicitudes_info)
	{
		$datos_json['resultado'] = false;
		if ( isset($solicitudes_info[0]['Solicitud']) )
		{
			foreach ( $solicitudes_info as $solicitud )
			{
				$ts = array();
				$ts = split(',', $solicitud['Solicitud']['tipo_servicio']);
				$listado = '';
				for ( $i=0; $i < count($ts); $i++ )
				{
					$listado .= $this->tipo_servicio[$ts[$i]].' , ';
				}
				$solicitud['Solicitud']['tipo_servicio'] = substr($listado, 0, -3);
				
				$filas_tabla .= '<tr><td>'.$solicitud['Solicitud']['id'].'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($solicitud['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$solicitud['Usuario']['Usu_cedula'].'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($solicitud['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['tipo_servicio'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['placa_inventario'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['created'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['descripcion'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['observaciones'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['solucionada'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['repuestos_mano_obra'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['observaciones_solucion'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['contratista'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['costo_interno'].'</td>';
				$filas_tabla .= '<td>'.$solicitud['Solicitud']['costo_externo'].'</td>';
				$filas_tabla .= '<td>'.$this->estados[$solicitud['Solicitud']['estado']].'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($solicitudes_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}
	
	//--------------------------------------------------------------------------
	
	function cargar_tipos_de_servicio()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$opciones_servicios = '';
		$tipos_servicio = $this->Solicitud->tipo_servicio;
		foreach ( $tipos_servicio as  $id_ts => $nombre_ts )
		{
			$opciones_servicios .= '<option value="'.$id_ts.'">'.$nombre_ts.'</option>';
		}
		$opciones_servicios = '<option value="0">Todos los servicios</option>'.$opciones_servicios;
		return $opciones_servicios;
	}
	
	//--------------------------------------------------------------------------

	function info_solicitud_pdf($id)
	{
		$this->loadModel('Producto');
		$this->loadModel('SmuqUsuario');
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		
		$this->Solicitud->recursive = 1;
		$solicitud = $this->Solicitud->read(null, $id);
		if ( !empty($solicitud) )
		{
			// Si la solicitud ya fué solucionada, incluimos la info pertinente.
			if ( $solicitud['Solicitud']['estado'] == 's' )
			{
				$adm_sol = $this->SmuqUsuario->find('first', array
				(
					'fields' => array('SmuqUsuario.nombre'),
					'conditions' => array('SmuqUsuario.cedula' => $solicitud['Solicitud']['cedula_adm_sol'])
				));
			}
			if ( empty($adm_sol) )
			{
				$adm_sol = array('SmuqUsuario'=>array('nombre'=>'No Disponible.'));
			}
			
			$solicitante = $this->SmuqUsuario->find('first', array
			(
				'fields' => array
				(
					'SmuqUsuario.cargo',
					'SmuqUsuario.email',
					'SmuqUsuario.telefono'
				),
				'conditions' => array
				(
					'SmuqUsuario.cedula' => $solicitud['Solicitud']['cedula_usuario'],
					'SmuqUsuario.cargo <>' => ''
				)
			));
			if ( !empty($solicitante) )
			{
				if ( empty($solicitante['SmuqUsuario']['cargo']) )
				{
					$solicitante['SmuqUsuario']['cargo'] = 'No Disponible.';
				}
				if ( empty($solicitante['SmuqUsuario']['email']) )
				{
					$solicitante['SmuqUsuario']['email'] = 'No Disponible.';
				}
				if ( empty($solicitante['SmuqUsuario']['telefono']) )
				{
					$solicitante['SmuqUsuario']['telefono'] = 'No Disponible.';
				}
			}
			else
			{
				$solicitante['SmuqUsuario']['cargo'] = 'No Disponible.';
				$solicitante['SmuqUsuario']['email'] = 'No Disponible.';
				$solicitante['SmuqUsuario']['telefono'] = 'No Disponible.';
			}
			
			$dependencia = $this->Dependencia->find('first', array
			(
				'fields' => array('Dependencia.id_edificio'),
				'conditions' => array('Dependencia.Cencos_id' => $solicitud['Solicitud']['Cencos_id'])
			));
			if ( !empty($dependencia) )	// Si la dependencia está asignada a un edificio.
			{
				$edificio = $this->Edificio->find('first', array
				(
					'conditions' => array('Edificio.id' => $dependencia['Dependencia']['id_edificio'])
				));
				if ( empty($edificio) )
				{
					$edificio['Edificio']['name'] = 'No disponible';
				}
			}
			else
			{
				$edificio['Edificio']['name'] = 'No disponible';
			}
			
			$equipo = $this->Producto->find('first', array
			(
				'fields' => array('Producto.prousu_pro_nombre', 'Producto.prousu_modelo', 'Producto.prousu_marca'),
				'conditions' => array('Producto.prousu_placa' => $solicitud['Solicitud']['placa_inventario'])
			));
			
			$contratista = $this->SmuqUsuario->find('first', array
			(
				'fields' => array('SmuqUsuario.nombre'),
				'conditions' => array('SmuqUsuario.cedula' => $this->Session->read('Usuario.cedula'))
			));
			
			if ( !empty($equipo) )
			{
				$tmp = split(' ', $solicitud['Solicitud']['created']);
				$fecha = $tmp[0];
				list($anio, $mes, $dia) = split('-', $fecha);
				$solicitud['Solicitud']['fecha'] = $this->Tiempo->fecha_espaniol(date('Y-n-j-N', mktime(0,0,0,$mes, $dia, $anio)));
				$solicitud['CentroCosto']['Cencos_nombre'] = mb_convert_case($solicitud['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
				$solicitante['SmuqUsuario']['nombre'] = mb_convert_case($solicitud['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
				$ts = split(',', $solicitud['Solicitud']['tipo_servicio']);
				$listado = '';
				for ( $i=0; $i < count($ts); $i++ )
				{
					$listado .= '<li>'.$this->tipo_servicio[$ts[$i]].'</li>';
				}
				$solicitud['Solicitud']['tipo_servicio'] = $listado;
				$equipo['Producto']['prousu_pro_nombre'] = mb_convert_case($equipo['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");
				if ( empty($equipo['Producto']['prousu_marca']) )
				{
					$equipo['Producto']['prousu_marca'] = 'No Disponible.';
				}
				else
				{
					$equipo['Producto']['prousu_marca'] = mb_convert_case($equipo['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
				}
				if ( empty($equipo['Producto']['prousu_modelo']) )
				{
					$equipo['Producto']['prousu_modelo'] = 'No Disponible.';
				}
				else
				{
					$equipo['Producto']['prousu_modelo'] = mb_convert_case($equipo['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
				}
			}
			
			$filas_tabla['parte_2'] = '';
			if ( $solicitud['Solicitud']['estado'] == 's' )
			{
				$filas_tabla['parte_2'] =
				'<tr align="left">
					<td colspan="3" width="*"><div>
						<table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody>
							<tr align="left"><td width="*" colspan="4" align="center"><b>SOLUCIÓN A LA SOLICITUD</b></td></tr>
							<tr align="left">
								<td width="95" valign="top"><b>Atendida por</b></td>
								<td width="170">'.$adm_sol['SmuqUsuario']['nombre'].'</td>
								<td width="75" valign="top"><b>Contratista</b></td>
								<td width="*">'.$solicitud['Solicitud']['contratista'].'</td>
							</tr>
							<tr align="left">
								<td width="95" valign="top"><b>Repuestos / Mano de Obra</b></td>
								<td colspan="3" width="*">'.$solicitud['Solicitud']['repuestos_mano_obra'].'</td>
							</tr>
							<tr align="left">
								<td width="95" valign="middle"><b>Costo externo</b></td>
								<td width="125"><b>$</b>'.$solicitud['Solicitud']['costo_externo'].'</td>
								<td width="95" valign="middle"><b>Costo interno</b></td>
								<td width="*"><b>$</b>'.$solicitud['Solicitud']['costo_interno'].'</td>
							</tr>
							<tr align="left">
								<td width="95" valign="top"><b>Observaciones</b></td>
								<td colspan="3" width="*">'.$solicitud['Solicitud']['observaciones_solucion'].'</td>
							</tr>
						</tbody></table>
						
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr><td height="30" colspan="3"></td></tr>
							<tr align="left">
								<td width="237" align="left" valign="top"><b>Responsable Mantenimiento</b></td>
								<td width="50"> </td>
								<td width="*" align="left"><b>Operario/Contratista</b></td>
							</tr>
							<tr><td height="30" colspan="3"></td></tr>
							<tr align="left">
								<td width="237" align="left" valign="top"><b>Recepción a Usuario</b></td>
								<td width="50"> </td>
								<td width="*" align="left"><b>Recibido a Satisfacción</b></td>
							</tr>
							<tr align="left">
								<td width="237"> </td>
								<td width="50"> </td>
								<td width="*" align="left"><b>C.C.</b></td>
							</tr>
							<tr><td height="30" colspan="3"></td></tr>
							<tr align="left">
								<td width="237" align="left" valign="top"><b>Vo.Bo. Vicerrectora Administrativa</b></td>
								<td width="*" colspan="2"> </td>
							</tr>
						</tbody></table>
					</div></td>
				</tr>';
			}
			
			$encabezado = sprintf($this->encabezado_pdf, $id);
			$filas_tabla['parte_1'] =
			'<table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody>
				<tr align="left">
					<td colspan="2">'.$encabezado.'</td>
				</tr>
				
				<tr><td height="5" colspan="3"></td></tr>
				
				<tr align="left">
					<td colspan="3" width="*"><div>
						<table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody>
							<tr align="left">
								<td width="*" colspan="4" align="center"><b>INFORMACIÓN DEL USUARIO</b></td>
							</tr>
							<tr align="left">
								<td width="113"><b>Estado de solicitud</b></td>
								<td width="120">'.$this->estados[$solicitud['Solicitud']['estado']].'</td>
								<td width="50"><b>Fecha</b></td>
								<td width="*">'.$solicitud['Solicitud']['fecha'].'</td>
							</tr>
							<tr align="left">
								<td width="113"><b>Edificio</b></td>
								<td width="*" colspan="3">'.$edificio['Edificio']['name'].'</td>
							</tr>
							<tr align="left">
								<td width="113"><b>Dependencia</b></td>
								<td width="*" colspan="3">'.$solicitud['CentroCosto']['Cencos_nombre'].'</td>
							</tr>
							<tr align="left">
								<td width="113"><b>Solicitante</b></td>
								<td width="200">'.$solicitante['SmuqUsuario']['nombre'].'</td>
								<td width="50"><b>Cargo</b></td>
								<td width="*">'.$solicitante['SmuqUsuario']['cargo'].'</td>
							</tr>
							<tr align="left">
								<td width="113"><b>Email</b></td>
								<td width="270">'.$solicitante['SmuqUsuario']['email'].'</td>
								<td width="60"><b>Teléfono</b></td>
								<td width="*">'.$solicitante['SmuqUsuario']['telefono'].'</td>
							</tr>
						</tbody></table>
					</div></td>
				</tr>
						
				<tr align="left">
					<td colspan="3" width="*"><div>
						<table width="100%" cellspacing="0" cellpadding="3" border="1"><tbody>
							<tr align="left">
								<td width="*" colspan="4" align="center"><b>INFORMACIÓN DEL EQUIPO Y SERVICIO REQUERIDO</b></td>
							</tr>
							<tr align="left">
								<td width="110"><b>Nombre del equipo</b></td>
								<td width="250">'.$equipo['Producto']['prousu_pro_nombre'].'</td>
								<td width="110"><b>Placa de Inventario</b></td>
								<td width="*" valign="bottom">'.$solicitud['Solicitud']['placa_inventario'].'</td>
							</tr>
							<tr align="left">
								<td width="110"><b>Modelo</b></td>
								<td width="190">'.$equipo['Producto']['prousu_modelo'].'</td>
								<td width="50"><b>Marca</b></td>
								<td width="*">'.$equipo['Producto']['prousu_marca'].'</td>
							</tr>
							<tr align="left">
								<td valign="top" width="110"><b>Tipo de Servicio</b></td>
								<td colspan="3" width="*" valign="top"><ul>'.$solicitud['Solicitud']['tipo_servicio'].'</ul></td>
							</tr>
							<tr align="left">
								<td valign="top" width="110"><b>Descripción</b></td>
								<td colspan="3" width="*">'.$solicitud['Solicitud']['descripcion'].'</td>
							</tr>
							<tr align="left">
								<td valign="top" width="110"><b>Observaciones</b></td>
								<td colspan="3" width="*">'.$solicitud['Solicitud']['observaciones'].'</td>
							</tr>
						</tbody></table>
					</div></td>
				</tr>';
			
			return $filas_tabla['parte_1'].$filas_tabla ['parte_2'].'</tbody></table>';
		}
		else
		{
			return 'false';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function info_solicitud($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$solicitudes_info = $this->Solicitud->findById($id);
		return json_encode($this->__crear_filas(array($solicitudes_info)));
	}
	
	//--------------------------------------------------------------------------
	
	function usr_info_solicitud($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$solicitudes_info = $this->Solicitud->find('first', array
		(
			'conditions' => array
			(
				'Solicitud.cedula_usuario' => $this->Session->read('Usuario.cedula'),
				'Solicitud.id' => $id
			)
		));
		return json_encode($this->__crear_filas(array($solicitudes_info)));
	}
	
	//--------------------------------------------------------------------------
	
	function ver($id)
	{
		$this->loadModel('Producto');
		$this->loadModel('SmuqUsuario');
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		
		$this->set('opcion_seleccionada', 'consultar_solicitudes');
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
		
		// Revisamos variables de Session.
		if ( $this->Session->check('Controlador.resultado_guardar') )
		{
			if ( $this->Session->read('Controlador.resultado_guardar') == 'exito' )
			{
				$this->set('display_notificacion', 'block');
				$this->set('clase_notificacion', 'clean-ok');
				//$this->set('mensaje_notificacion', 'Se han guardado los cambios.');
				if ( $this->Session->read('Controlador.resultado_email') == 'exito' )
				{
					$this->set('mensaje_notificacion', 'La solicitud de servicio #'.$this->Session->read('Controlador.resultado_id').' ha sido solucionada y archivada, y se ha enviado un email con la información de la solicitud ');
				}
				else 
				{
					$this->set('mensaje_notificacion', 'La solicitud de servicio #'.$this->Session->read('Controlador.resultado_id').' ha sido solucionada y archivada.');
				}
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
		
		$this->Solicitud->recursive = 1;
		$solicitud_info = $this->Solicitud->read(null, $id);
		if ( !empty($solicitud_info) )
		{
			// Si la solicitud ya fué solucionada, incluimos la info pertinente.
			if ( $solicitud_info['Solicitud']['estado'] == 's' )
			{
				$adm_sol_info = $this->SmuqUsuario->find('first', array
				(
					'fields' => array('SmuqUsuario.nombre'),
					'conditions' => array('SmuqUsuario.cedula' => $solicitud_info['Solicitud']['cedula_adm_sol'])
				));
			}
			if ( !empty($adm_sol_info) )
			{
				$this->set('adm_sol', $adm_sol_info);
			}
			else
			{
				$this->set('adm_sol', array('SmuqUsuario'=>array('nombre'=>'No Disponible.')));
			}
			
			$cargo_solicitante = '';
			$solicitante = $this->SmuqUsuario->find('first', array
			(
				'fields' => array
				(
					'SmuqUsuario.cargo',
					'SmuqUsuario.email',
					'SmuqUsuario.telefono'
				),
				'conditions' => array
				(
					'SmuqUsuario.cedula' => $solicitud_info['Solicitud']['cedula_usuario'],
					'SmuqUsuario.cargo <>' => ''
				)
			));
			if ( !empty($solicitante) )
			{
				if ( empty($solicitante['SmuqUsuario']['cargo']) )
				{
					$solicitante['SmuqUsuario']['cargo'] = 'No Disponible.';
				}
				if ( empty($solicitante['SmuqUsuario']['email']) )
				{
					$solicitante['SmuqUsuario']['email'] = 'No Disponible.';
				}
				if ( empty($solicitante['SmuqUsuario']['telefono']) )
				{
					$solicitante['SmuqUsuario']['telefono'] = 'No Disponible.';
				}
			}
			else
			{
				$solicitante['SmuqUsuario']['cargo'] = 'No Disponible.';
				$solicitante['SmuqUsuario']['email'] = 'No Disponible.';
				$solicitante['SmuqUsuario']['telefono'] = 'No Disponible.';
			}
			$this->set('solicitante', $solicitante);
			
			$dependencia = $this->Dependencia->find('first', array
			(
				'fields' => array('Dependencia.id_edificio'),
				'conditions' => array('Dependencia.Cencos_id' => $solicitud_info['Solicitud']['Cencos_id'])
			));
			if ( !empty($dependencia) )	// Si la dependencia está asignada a un edificio.
			{
				$edificio = $this->Edificio->find('first', array
				(
					'conditions' => array('Edificio.id' => $dependencia['Dependencia']['id_edificio'])
				));
				if ( !empty($edificio) )
				{
					$solicitud_info['Edificio']['nombre'] = $edificio['Edificio']['name'];
				}
				else
				{
					$solicitud_info['Edificio']['nombre'] = 'No disponible';
				}
			}
			else
			{
				$solicitud_info['Edificio']['nombre'] = 'No disponible';
			}
			
			$equipo_info = $this->Producto->find('first', array
			(
				'fields' => array('Producto.prousu_pro_nombre', 'Producto.prousu_modelo', 'Producto.prousu_marca'),
				'conditions' => array('Producto.prousu_placa' => $solicitud_info['Solicitud']['placa_inventario'])
			));
			
			$contratista_info = $this->SmuqUsuario->find('first', array
			(
				'fields' => array('SmuqUsuario.nombre'),
				'conditions' => array('SmuqUsuario.cedula' => $this->Session->read('Usuario.cedula'))
			));
			
			if ( !empty($equipo_info) )
			{
				$tmp = split(' ', $solicitud_info['Solicitud']['created']);
				$fecha = $tmp[0];
				list($anio, $mes, $dia) = split('-', $fecha);
				$solicitud_info['Solicitud']['fecha'] = $this->Tiempo->fecha_espaniol(date('Y-n-j-N', mktime(0,0,0,$mes, $dia, $anio)));
				$solicitud_info['CentroCosto']['Cencos_nombre'] = mb_convert_case($solicitud_info['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
				$solicitud_info['Usuario']['Usu_nombre'] = mb_convert_case($solicitud_info['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
				
				$ts = split(',', $solicitud_info['Solicitud']['tipo_servicio']);
				$listado = '';
				for ( $i=0; $i < count($ts); $i++ )
				{
					$listado .= '<li>'.$this->tipo_servicio[$ts[$i]].'</li>';
				}
				$solicitud_info['Solicitud']['tipo_servicio'] = $listado;
				
				$equipo_info['Producto']['prousu_pro_nombre'] = mb_convert_case($equipo_info['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");
				if ( empty($equipo_info['Producto']['prousu_marca']) )
				{
					$equipo_info['Producto']['prousu_marca'] = 'No Disponible.';
				}
				else
				{
					$equipo_info['Producto']['prousu_marca'] = mb_convert_case($equipo_info['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
				}
				if ( empty($equipo_info['Producto']['prousu_modelo']) )
				{
					$equipo_info['Producto']['prousu_modelo'] = 'No Disponible.';
				}
				else
				{
					$equipo_info['Producto']['prousu_modelo'] = mb_convert_case($equipo_info['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
				}
				if ( empty($solicitud_info['Solicitud']['contratista']) )
				{
					$solicitud_info['Solicitud']['contratista'] = $contratista_info['SmuqUsuario']['nombre'];
				}
				
				$this->set('solicitud', $solicitud_info);
				$this->set('equipo', $equipo_info);
				$this->set('usuario', array
				(
					'cedula' => $this->Session->read('Usuario.cedula'),
					'id_grupo' => $this->Session->read('Usuario.id_grupo')
				));

				if ( $solicitud_info['Solicitud']['estado'] == 's' )
				{
					$this->set('display_link_pdf', 'block');
				}
				else
				{
					$this->set('display_link_pdf', 'none');
				}
				
			}
		}
	}

	//--------------------------------------------------------------------------
	
	function guardar_solucion()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		if ( !empty($this->data) )
		{
			$this->Solicitud->read(null, $this->data['Solicitud']['id']);
			
			if ( $this->Solicitud->save($this->data) )
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
	
	function solucionar()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Notificacion');
		$this->loadModel('SmuqUsuario');
		
		if ( !empty($this->data) )
		{
			$this->data['Solicitud']['estado'] = 's';
			$this->data['Solicitud']['solucionada'] = date('Y-m-d H:i:s');
			$solicitud_info = $this->Solicitud->read(null, $this->data['Solicitud']['id']);
			
			if ( $this->Solicitud->save($this->data) )
			{
				$this->Session->write('Controlador.resultado_guardar', 'exito');
				
				// Creamos la notificación correspondiente.
				$this->Notificacion->save
				(
					array('Notificacion'=>array
					(
						'cedula_usuario'=>$solicitud_info['Solicitud']['cedula_usuario'],
						'id_solicitud'=>$this->data['Solicitud']['id'],
						'leido'=>'no'
					))
				);
				
				// Ahora debemos informar al usuario sobre la solución de su
				// solicitud de servicio (EMAIL)
				$smuq_usuario = $this->SmuqUsuario->find('first', array
				(
					'fields' => array('SmuqUsuario.email'),
					'conditions' => array
					(
						'SmuqUsuario.cedula' => $solicitud_info['Solicitud']['cedula_usuario'],
						'SmuqUsuario.email <>' => ''
					)
				));
				if ( !empty($smuq_usuario) )	// Si el usuario tiene correo, se le envia la notificación
				{
					$this->Session->write('Email.id_solicitud', $this->Solicitud->id);
					$this->Session->write('Email.email_solicitante', $smuq_usuario['SmuqUsuario']['email']);
					if ( $this->requestAction('solicitudes/email', array('return'=>'')) == 'true' )
					{
						$this->Session->write('Controlador.resultado_email', 'exito');
					}
					else
					{
						$this->Session->write('Controlador.resultado_email', 'error');
					}
				}
				$this->Session->write('Controlador.resultado_guardar', 'exito');
				$this->Session->write('Controlador.resultado_id', $this->Solicitud->id);
			}
			else
			{
				$this->Session->write('Controlador.resultado_guardar', 'error');
				$this->Session->write('Controlador.resultado_email', 'error');
			}
			
			$this->redirect($this->referer());
		}
	}
	
	//--------------------------------------------------------------------------
	
	function email()
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Producto');
		$this->loadModel('SmuqUsuario');
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		$this->Email->template = 'email/default';
		
		$id_solicitud = $this->Session->read('Email.id_solicitud');
		$email_solicitante = $this->Session->read('Email.email_solicitante');
		
		//$id_solicitud = 2;
		//$email_solicitante = 'dementriomacias@hotmail.com';
		
		$this->Solicitud->recursive = 1;
		$solicitud_info = $this->Solicitud->read(null, $id_solicitud);
		if ( !empty($solicitud_info) )
		{
			$solicitud_info['Solicitud']['estado'] = $this->estados[$solicitud_info['Solicitud']['estado']];
			
			$adm_sol_info = $this->SmuqUsuario->find('first', array
			(
				'fields' => array('SmuqUsuario.nombre'),
				'conditions' => array('SmuqUsuario.cedula' => $solicitud_info['Solicitud']['cedula_adm_sol'])
			));
			$this->set('adm_sol', $adm_sol_info);
			
			$cargo_solicitante = '';
			$solicitante = $this->SmuqUsuario->find('first', array
			(
				'fields' => array('SmuqUsuario.cargo'),
				'conditions' => array
				(
					'SmuqUsuario.cedula' => $solicitud_info['Solicitud']['cedula_usuario'],
					'SmuqUsuario.cargo <>' => ''
				)
			));
			if ( !empty($solicitante) )
			{
				$cargo_solicitante = $solicitante['SmuqUsuario']['cargo'];
			}
			else
			{
				$cargo_solicitante = 'No Disponible.';
			}
			$this->set('cargo_solicitante', $cargo_solicitante);
			
			$dependencia = $this->Dependencia->find('first', array
			(
				'fields' => array('Dependencia.id_edificio'),
				'conditions' => array('Dependencia.Cencos_id' => $solicitud_info['Solicitud']['Cencos_id'])
			));
			if ( !empty($dependencia) )	// Si la dependencia está asignada a un edificio.
			{
				$edificio = $this->Edificio->find('first', array
				(
					'conditions' => array('Edificio.id' => $dependencia['Dependencia']['id_edificio'])
				));
				if ( !empty($edificio) )
				{
					$solicitud_info['Edificio']['nombre'] = $edificio['Edificio']['name'];
				}
				else
				{
					$solicitud_info['Edificio']['nombre'] = 'No disponible';
				}
			}
			else
			{
				$solicitud_info['Edificio']['nombre'] = 'No disponible';
			}
			
			$equipo_info = $this->Producto->find('first', array
			(
				'fields' => array('Producto.prousu_pro_nombre', 'Producto.prousu_modelo', 'Producto.prousu_marca'),
				'conditions' => array('Producto.prousu_placa' => $solicitud_info['Solicitud']['placa_inventario'])
			));
			
			$contratista_info = $this->SmuqUsuario->find('first', array
			(
				'fields' => array('SmuqUsuario.nombre'),
				'conditions' => array('SmuqUsuario.cedula' => $this->Session->read('Usuario.cedula'))
			));
			
			if ( !empty($equipo_info) )
			{
				$tmp = split(' ', $solicitud_info['Solicitud']['created']);
				$fecha = $tmp[0];
				list($anio, $mes, $dia) = split('-', $fecha);
				$solicitud_info['Solicitud']['fecha'] = $this->Tiempo->fecha_espaniol(date('Y-n-j-N', mktime(0,0,0,$mes, $dia, $anio)));
				$solicitud_info['Solicitud']['tipo_servicio'] = $this->tipo_servicio[$solicitud_info['Solicitud']['tipo_servicio']];
				$solicitud_info['CentroCosto']['Cencos_nombre'] = mb_convert_case($solicitud_info['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
				$solicitud_info['Usuario']['Usu_nombre'] = mb_convert_case($solicitud_info['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
				$equipo_info['Producto']['prousu_pro_nombre'] = mb_convert_case($equipo_info['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");
				if ( empty($equipo_info['Producto']['prousu_marca']) )
				{
					$equipo_info['Producto']['prousu_marca'] = 'No Disponible.';
				}
				else
				{
					$equipo_info['Producto']['prousu_marca'] = mb_convert_case($equipo_info['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
				}
				if ( empty($equipo_info['Producto']['prousu_modelo']) )
				{
					$equipo_info['Producto']['prousu_modelo'] = 'No Disponible.';
				}
				else
				{
					$equipo_info['Producto']['prousu_modelo'] = mb_convert_case($equipo_info['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
				}
				if ( empty($solicitud_info['Solicitud']['contratista']) )
				{
					$solicitud_info['Solicitud']['contratista'] = $contratista_info['SmuqUsuario']['nombre'];
				}
				
				$this->set('encabezado_pdf', $this->encabezado_pdf);
				$this->set('solicitud', $solicitud_info);
				$this->set('equipo', $equipo_info);
				$this->set('usuario', array('cedula'=>$this->Session->read('Usuario.cedula'),
													 'id_grupo'=>$this->Session->read('Usuario.id_grupo')));
			}
			
			$this->Email->to = $email_solicitante;
			$this->Email->subject = 'Información: Solicitud de Mantenimiento #'.$id_solicitud;
			
			//$this->Email->attach($fully_qualified_filename, optionally $new_name_when_attached);
			// You can attach as many files as you like.
			
			$result = $this->Email->send();
			if ( !$result )
			{
				return 'false';
			}
			else
			{
				return 'true';
			}
		}
		else
		{
			return 'false';
		}
	}
	
	//--------------------------------------------------------------------------
	
	function buscar($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();
		if ( $criterio_fecha == 'anio_mes' )
		{
			if ( $fecha_1 != 0 )
			{
				$pre_con['YEAR(created)'] = $fecha_1;
				if ( $fecha_2 != 0 )
				{
					$pre_con['MONTH(created)'] = $fecha_2;
				}
			}
		}
		else if ( $criterio_fecha == 'rango_fecha' )
		{
			$pre_con['DATE(created) >='] = $fecha_1;
			$pre_con['DATE(created) <='] = $fecha_2;
		}
		if ( $mostrar_solicitudes != 'todas' )
		{
			$pre_con['estado'] = $mostrar_solicitudes;
		}
		if ( $tipo_servicio != 0 )
		{
			$pre_con['tipo_servicio'] = $tipo_servicio;
		}
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['placa_inventario LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['descripcion LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['observaciones LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['repuestos_mano_obra LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['contratista LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		if ( count($pre_con) > 0 )
		{
			foreach ( $pre_con as $criterio => $crit_valor )
			{
				$condiciones[$criterio] = $crit_valor;
			}
		}
		
		$solicitudes = $this->Solicitud->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas($solicitudes));
	}
	
	//--------------------------------------------------------------------------
	
	function usr_buscar($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con = array('Solicitud.cedula_usuario' => $this->Session->read('Usuario.cedula'));
		$pre_con_like = array();
		if ( $criterio_fecha == 'anio_mes' )
		{
			if ( $fecha_1 != 0 )
			{
				$pre_con['YEAR(created)'] = $fecha_1;
				if ( $fecha_2 != 0 )
				{
					$pre_con['MONTH(created)'] = $fecha_2;
				}
			}
		}
		else if ( $criterio_fecha == 'rango_fecha' )
		{
			$pre_con['DATE(created) >='] = $fecha_1;
			$pre_con['DATE(created) <='] = $fecha_2;
		}
		if ( $mostrar_solicitudes != 'todas' )
		{
			$pre_con['estado'] = $mostrar_solicitudes;
		}
		if ( $tipo_servicio != 0 )
		{
			$pre_con['tipo_servicio LIKE'] = '%'.$tipo_servicio.'%';
		}
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['placa_inventario LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['descripcion LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['observaciones LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		if ( count($pre_con) > 0 )
		{
			foreach ( $pre_con as $criterio => $crit_valor )
			{
				$condiciones[$criterio] = $crit_valor;
			}
		}
		
		$solicitudes = $this->Solicitud->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas($solicitudes));
	}
	
	//--------------------------------------------------------------------------
	
	function buscar_xls($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();
		if ( $criterio_fecha == 'anio_mes' )
		{
			if ( $fecha_1 != 0 )
			{
				$pre_con['YEAR(created)'] = $fecha_1;
				if ( $fecha_2 != 0 )
				{
					$pre_con['MONTH(created)='] = $fecha_2;
				}
			}
		}
		else if ( $criterio_fecha == 'rango_fecha' )
		{
			$pre_con['DATE(created) >='] = $fecha_1;
			$pre_con['DATE(created) <='] = $fecha_2;
		}
		if ( $mostrar_solicitudes != 'todas' )
		{
			$pre_con['estado'] = $mostrar_solicitudes;
		}
		if ( $tipo_servicio != 0 )
		{
			$pre_con['tipo_servicio'] = $tipo_servicio;
		}
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['placa_inventario LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['descripcion LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['observaciones LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['repuestos_mano_obra LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['contratista LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		if ( count($pre_con) > 0 )
		{
			foreach ( $pre_con as $criterio => $crit_valor )
			{
				$condiciones[$criterio] = $crit_valor;
			}
		}
		
		$solicitudes = $this->Solicitud->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->_crear_filas_xls($solicitudes));
	}
	
	//--------------------------------------------------------------------------
	
	function usr_buscar_xls($frase_busqueda, $criterio_fecha, $fecha_1, $fecha_2, $mostrar_solicitudes, $criterio_campo, $tipo_servicio)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$condiciones = array();
		$pre_con = array('Solicitud.cedula_usuario' => $this->Session->read('Usuario.cedula'));
		$pre_con_like = array();
		if ( $criterio_fecha == 'anio_mes' )
		{
			if ( $fecha_1 != 0 )
			{
				$pre_con['YEAR(created)'] = $fecha_1;
				if ( $fecha_2 != 0 )
				{
					$pre_con['MONTH(created)='] = $fecha_2;
				}
			}
		}
		else if ( $criterio_fecha == 'rango_fecha' )
		{
			$pre_con['DATE(created) >='] = $fecha_1;
			$pre_con['DATE(created) <='] = $fecha_2;
		}
		if ( $mostrar_solicitudes != 'todas' )
		{
			$pre_con['estado'] = $mostrar_solicitudes;
		}
		if ( $tipo_servicio != 0 )
		{
			$pre_con['tipo_servicio LIKE'] = '%'.$tipo_servicio.'%';
		}
		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['placa_inventario LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['descripcion LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['observaciones LIKE'] = '%'.$frase_busqueda.'%';
				$condiciones['OR'] = $pre_con_like;
			}
		}
		if ( count($pre_con) > 0 )
		{
			foreach ( $pre_con as $criterio => $crit_valor )
			{
				$condiciones[$criterio] = $crit_valor;
			}
		}
		
		$solicitudes = $this->Solicitud->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->_crear_filas_xls($solicitudes));
	}
	
	//--------------------------------------------------------------------------
}
?>
