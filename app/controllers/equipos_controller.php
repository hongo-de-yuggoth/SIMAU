<?php
// Configuracion para superar el limite de subida de archivos.
ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');
ini_set('max_execution_time','3000');
ini_set('max_input_time','3000');
ini_set('memory_limit','200M');

//-----------------------------------------------------------------------------

class EquiposController extends AppController
{
	var $helpers = array('Html', 'Javascript');
	var $uses  = array('Equipo', 'Producto');
	var $components = array('Tiempo');
	var $id_grupo = '*';

	//--------------------------------------------------------------------------

	function exportar_xls($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_usuario)
	{
		$datos = json_decode($this->requestAction('/equipos/buscar_xls/'.$frase_busqueda.'/'.$criterio_campo.'/'.$criterio_dependencia.'/'.$criterio_usuario));

		$this->set('filas_tabla',utf8_decode($datos->filas_tabla));
		$this->set('total_registros',$datos->count);
		$this->render('exportar_xls','exportar_xls');
	}

	//--------------------------------------------------------------------------

	function usr_exportar_xls($frase_busqueda, $criterio_campo)
	{
		$datos = json_decode($this->requestAction('/equipos/usr_buscar_xls/'.$frase_busqueda.'/'.$criterio_campo));

		$this->set('filas_tabla',utf8_decode($datos->filas_tabla));
		$this->set('total_registros',$datos->count);
		$this->render('exportar_xls','exportar_xls');
	}

	//--------------------------------------------------------------------------

	function exportar_pdf($placa_inventario)
	{
		// Sobrescribimos para que no aparezcan los resultados de debuggin
		// ya que sino daria un error al generar el pdf.
		Configure::write('debug',0);

		// Se obtienen los datos del equipo.
		$filas_tabla = $this->requestAction('/equipos/info_equipo_pdf/'.$placa_inventario);
		$this->set('filas_tabla',$filas_tabla);
		$this->set('placa_inventario',$placa_inventario);
		$this->render('exportar_pdf','exportar_pdf');
	}

	//--------------------------------------------------------------------------

	function modificar()
	{
		App::import('Vendor', 'upload', array('file' => 'class.upload.php'));
		$this->autoLayout = false;
		$this->autoRender = false;

		if ( !empty($this->data) )
		{
			// Si se anexó una imagen.... la guardamos.
			if ( !empty($this->data['File']['archivo_foto']['name']) &&
					$this->data['File']['archivo_foto']['size'] < (1024*1024*2) )
			{
				// Reordenamos el arreglo FILES para que la clase upload la pueda recibir.
				$FILE = array();
				$FILE['name'] = $this->data['File']['archivo_foto']['name'];
				$FILE['type'] = $this->data['File']['archivo_foto']['type'];
				$FILE['tmp_name'] = $this->data['File']['archivo_foto']['tmp_name'];
				$FILE['error'] = $this->data['File']['archivo_foto']['error'];
				$FILE['size'] = $this->data['File']['archivo_foto']['size'];
				$handle = new Upload($FILE);
				if ( $handle->uploaded )
				{
					$handle->file_overwrite = true;
					$handle->file_safe_name = false;
					$handle->file_auto_rename = false;
					$handle->file_new_name_body = 'equipo_'.$this->data['Equipo']['placa_inventario'];

					if ( $handle->image_src_x > 600 && $handle->image_src_y > 600 )
					{
						if ( $handle->image_src_x > $handle->image_src_y )
						{
							$handle->image_resize = true;
							$handle->image_ratio_y = true;
							$handle->image_x = 600;
						}
						else if ( $handle->image_src_y > $handle->image_src_x )
						{
							$handle->image_resize = true;
							$handle->image_ratio_x = true;
							$handle->image_y = 600;
						}
					}
					else
					{
						$handle->image_resize = false;
					}

					$handle->allowed = array('image/*');
					$handle->image_convert = 'jpg';
					$handle->Process('equipos/fotos');
					if ( $handle->processed )
					{
						$this->data['Equipo']['nombre_foto'] = $handle->file_dst_name;

						// Procesamos el THUMB.
						$handle->file_overwrite = true;
						$handle->file_safe_name = false;
						$handle->file_auto_rename = false;
						$handle->file_new_name_body = 'thumb_equipo_'.$this->data['Equipo']['placa_inventario'];

						$handle->image_resize = true;
						$handle->image_ratio_y = true;
						$handle->image_x = 100;

						$handle->image_convert = 'jpg';
						$handle->Process('equipos/thumbs');
					}
					else
					{
						$handle->Clean();
						$this->Session->write('Controlador.resultado_guardar', 'error_imagen');
						$this->redirect($this->referer());
					}

					$handle->Clean();
				}
				else
				{
					$this->Session->write('Controlador.resultado_guardar', 'error_imagen');
					$this->redirect($this->referer());
				}
			}

			// Guardamos los datos de esta imagen y equipo en la tabla equipos.
			if ( $this->Equipo->save($this->data) )
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

	function buscar_equipo_ajax($placa_inventario, $cedula_usuario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;

		$producto = $this->Producto->find('first', array('conditions' => array('Producto.prousu_placa' => $placa_inventario,
																								'Producto.prousu_usu_cedula' => $cedula_usuario)));
		if ( !empty($producto) )
		{
			if ( !empty($producto['Producto']['prousu_modelo']) )
			{
				$producto['Producto']['prousu_modelo'] = mb_convert_case($producto['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_modelo'] = 'No disponible.';
			}
			if ( !empty($producto['Producto']['prousu_marca']) )
			{
				$producto['Producto']['prousu_marca'] = mb_convert_case($producto['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_marca'] = 'No disponible.';
			}
			$producto['Producto']['prousu_pro_nombre'] = mb_convert_case($producto['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");

			// creamos inputs hidden
			$input_name = '<input id="nombre_equipo" type="hidden" value="'.$producto['Producto']['prousu_pro_nombre'].'"/>';
			$input_marca = '<input id="marca_equipo" type="hidden" value="'.$producto['Producto']['prousu_marca'].'"/>';
			$input_modelo = '<input id="modelo_equipo" type="hidden" value="'.$producto['Producto']['prousu_modelo'].'"/>';
			$input_encontro = '<input id="encontro" type="hidden" value="true"/>';
			$input_confirmado ='<input id="equipo_confirmado" name="data[Solicitud][placa_inventario]" type="hidden" value="'.$placa_inventario.'"/>';

			return $input_encontro.
					$input_name.
					$input_modelo.
					$input_marca.
					$input_confirmado;
		}
		else
		{
			return '<input id="encontro" type="hidden" value="false" /> <input id="equipo_confirmado" name="data[Solicitud][placa_inventario]" type="hidden" value="" />';
		}
	}

	//--------------------------------------------------------------------------

	function buscar_equipo_modificar($placa_inventario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		$this->loadModel('CentroCosto');
		$this->loadModel('SmuqUsuario');
		$producto = $this->Producto->find('first', array('conditions' => array('Producto.prousu_placa' => $placa_inventario)));
		$equipo = $this->Equipo->find('first', array('conditions' => array('Equipo.placa_inventario' => $placa_inventario)));
		if ( !empty($producto) )
		{
			if ( $producto['Producto']['prousu_marca'] == '' )
			{
				$producto['Producto']['prousu_marca'] = 'No Disponible.';
			}
			else
			{
				$producto['Producto']['prousu_marca'] = mb_convert_case($producto['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
			}
			if ( $producto['Producto']['prousu_modelo'] == '' )
			{
				$producto['Producto']['prousu_modelo'] = 'No Disponible.';
			}
			else
			{
				$producto['Producto']['prousu_modelo'] = mb_convert_case($producto['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
			}

			$smuq_usuario = $this->SmuqUsuario->findByCedula($producto['Usuario']['Usu_cedula']);
			if ( empty($smuq_usuario) || $smuq_usuario['SmuqUsuario']['cargo'] == '' )
			{
				$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible';
			}

			// Determinamos si la Dependencia tiene un Edificio asociado.
			$dependencia = $this->Dependencia->findByCencosId($producto['Usuario']['Usu_Cencos_id']);
			if ( !empty($dependencia) )
			{
				$edificio = $this->Edificio->findById($dependencia['Dependencia']['id_edificio']);
				if ( empty($edificio) )
				{
					$edificio['Edificio']['name'] = 'No disponible';
					$edificio['Edificio']['id'] = 0;
				}
			}
			else
			{
				$edificio['Edificio']['name'] = 'No disponible';
				$edificio['Edificio']['id'] = 0;
			}
			$centro_costo = $this->CentroCosto->findByCencosId($producto['Usuario']['Usu_Cencos_id']);

			// + Se debe hallar un valor para el $estado

			// creamos inputs hidden
			$input_id = '<input id="id_equipo" name="data[Equipo][Prousu_Pro_id]" type="hidden" value="'.$producto['Producto']['Prousu_Pro_id'].'"/>';
			$input_name = '<input id="name_equipo" type="hidden" value="'.mb_convert_case( $producto['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8").'"/>';
			$input_marca = '<input id="marca_equipo" type="hidden" value="'.$producto['Producto']['prousu_marca'].'"/>';
			$input_modelo = '<input id="modelo_equipo" type="hidden" value="'.$producto['Producto']['prousu_modelo'].'"/>';
			//$input_estado = '<input id="estado_equipo" type="hidden" value="'.$estado.'"/>';

			$input_placa_inventario = '<input id="placa_inventario_equipo" name="placa_inventario" type="hidden" value="'.$placa_inventario.'"/>';
			$input_id_usuario = '<input id="cedula_usuario_equipo" type="hidden" value="'.$producto['Producto']['prousu_usu_cedula'].'"/>';
			$input_usuario = '<input id="usuario_equipo" type="hidden" value="'.mb_convert_case($producto['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'"/>';
			$input_cargo = '<input id="cargo_equipo" type="hidden" value="'.$smuq_usuario['SmuqUsuario']['cargo'].'"/>';
			$input_id_edificio = '<input id="id_edificio_equipo" type="hidden" value="'.$edificio['Edificio']['id'].'"/>';
			$input_edificio = '<input id="edificio_equipo" type="hidden" value="'.$edificio['Edificio']['name'].'"/>';
			$input_id_dependencia = '<input id="id_dependencia_equipo" type="hidden" value="'.$producto['Usuario']['Usu_Cencos_id'].'"/>';
			$input_dependencia = '<input id="dependencia_equipo" type="hidden" value="'.mb_convert_case($centro_costo['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'"/>';

			$input_valor_compra = '<input id="valor_compra_equipo" type="hidden" value="'.$producto['Producto']['prousu_valor'].'"/>';
			$input_fecha_compra = '<input id="fecha_compra_equipo" type="hidden" value="'.$producto['Producto']['prousu_fecha_compra'].'"/>';
			$input_fecha_recibido = '<input id="fecha_recibido_equipo" type="hidden" value="'.$equipo['Equipo']['fecha_recibido_satisfaccion'].'"/>';
			//$input_garantia = '<input id="garantia_equipo" type="hidden" value="'.$equipo['Equipo']['garantia'].'"/>';

			if ( $equipo['Equipo']['nombre_foto'] != '' )
			{
				$input_nombre_foto = '<input id="nombre_foto_equipo" type="hidden" value="/equipos/thumbs/thumb_'.$equipo['Equipo']['nombre_foto'].'"/>';
				$nombre_foto_eliminar = '<input id="nombre_foto_eliminar" type="hidden" name="nombre_foto" value="'.$equipo['Equipo']['nombre_foto'].'"/>';
			}
			else
			{
				$input_nombre_foto = '<input id="nombre_foto_equipo" type="hidden" value=""/>';
				$nombre_foto_eliminar = '<input id="nombre_foto_eliminar" type="hidden" name="nombre_foto" value=""/>';
			}

			$input_encontro ='<input id="encontro" type="hidden" value="true"/>';

			return	$input_id.
						$input_name.
						$input_modelo.
						$input_marca.
						$input_placa_inventario.
						$input_id_usuario.
						$input_usuario.
						$input_cargo.
						$input_id_edificio.
						$input_edificio.
						$input_id_dependencia.
						$input_dependencia.
						$input_valor_compra.
						$input_fecha_compra.
						$input_fecha_recibido.
						$input_nombre_foto.
						$nombre_foto_eliminar.
						$input_encontro;
						//$input_garantia.
						//$input_estado.
		}
		else
		{
			return '<input id="encontro" type="hidden" value="false" /> <input id="equipo_confirmado" name="data[Equipo][placa_inventario]" type="hidden" value="" />';
		}
	}

	//--------------------------------------------------------------------------

	function buscar_equipo_archivos($placa_inventario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Certificado');
		$this->loadModel('Garantia');
		$this->loadModel('Manual');
		$this->loadModel('Factura');
		$this->loadModel('Cotizacion');

		$datos_json = array();
		$certificados = array();
		$manuales = array();
		$garantias = array();
		$facturas = array();
		$cotizaciones = array();

		$equipo_info = $this->Producto->find('first', array('conditions' => array('Producto.prousu_placa' => $placa_inventario)));
		if ( !empty($equipo_info) )
		{
			$certificados_info = $this->Certificado->find('all', array('conditions' => array('Certificado.placa_inventario' => $placa_inventario)));
			if ( !empty($certificados_info) )
			{
				foreach ( $certificados_info as $certificado )
				{
					$certificados[] = array('id'=>$certificado['Certificado']['id'],
													'nombre_archivo'=>$certificado['Certificado']['nombre_archivo']);
				}
			}

			$garantias_info = $this->Garantia->find('all', array('conditions' => array('Garantia.placa_inventario' => $placa_inventario)));
			if ( !empty($garantias_info) )
			{
				foreach ( $garantias_info as $garantia )
				{
					$garantias[] = array('id'=>$garantia['Garantia']['id'],
													'nombre_archivo'=>$garantia['Garantia']['nombre_archivo']);
				}
			}

			$manuales_info = $this->Manual->find('all', array('conditions' => array('Manual.placa_inventario' => $placa_inventario)));
			if ( !empty($manuales_info) )
			{
				foreach ( $manuales_info as $manual )
				{
					$manuales[] = array('id'=>$manual['Manual']['id'],
													'nombre_archivo'=>$manual['Manual']['nombre_archivo']);
				}
			}

			$facturas_info = $this->Factura->find('all', array('conditions' => array('Factura.placa_inventario' => $placa_inventario)));
			if ( !empty($facturas_info) )
			{
				foreach ( $facturas_info as $factura )
				{
					$facturas[] = array('id'=>$factura['Factura']['id'],
													'nombre_archivo'=>$factura['Factura']['nombre_archivo']);
				}
			}

			$cotizaciones_info = $this->Cotizacion->find('all', array('conditions' => array('Cotizacion.placa_inventario' => $placa_inventario)));
			if ( !empty($cotizaciones_info) )
			{
				foreach ( $cotizaciones_info as $cotizacion )
				{
					$cotizaciones[] = array('id'=>$cotizacion['Cotizacion']['id'],
													'nombre_archivo'=>$cotizacion['Cotizacion']['nombre_archivo']);
				}
			}

			$datos_json = array
			(
				'certificados'=>$certificados,
				'garantias'=>$garantias,
				'manuales'=>$manuales,
				'facturas'=>$facturas,
				'cotizaciones'=>$cotizaciones,
				'equipo'=>array
				(
					'id'=>$equipo_info['Producto']['Prousu_Pro_id'],
					'placa_inventario'=>$equipo_info['Producto']['prousu_placa']
				),
				'encontro_equipo'=>true
			);
		}
		else
		{
			$datos_json['encontro_equipo'] = false;
		}
		return json_encode($datos_json);
	}

	//--------------------------------------------------------------------------

	function borrar_foto($placa_inventario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$equipo = $this->Equipo->find('first', array('conditions'=>array('Equipo.placa_inventario'=>$placa_inventario)));
		if ( !empty($equipo) && !empty($equipo['Equipo']['nombre_foto']) )
		{
			if ( unlink(WWW_ROOT.'equipos/fotos/'.$equipo['Equipo']['nombre_foto'])
				&& unlink(WWW_ROOT.'equipos/thumbs/thumb_'.$equipo['Equipo']['nombre_foto']) )
			{
				$this->Equipo->read(null, $equipo['Equipo']['Prousu_Pro_id']);
				if ( $this->Equipo->saveField('nombre_foto', '') )
				{
					return 'true';
				}
			}
		}
		return 'false';
	}

	//--------------------------------------------------------------------------

	function __crear_filas($equipos_info)
	{
		$this->loadModel('CentroCosto');
		$datos_json['resultado'] = false;
		if ( isset($equipos_info[0]['Producto']) )
		{
			$filas_tabla = '';
			foreach ( $equipos_info as $equipo )
			{
				$cenco = $this->CentroCosto->find('first', array
				(
					'fields' => array('CentroCosto.Cencos_nombre'),
					'conditions' => array('CentroCosto.Cencos_id' => $equipo['Usuario']['Usu_Cencos_id'])
				));
				if ( empty($cenco) )
				{
					$cenco['CentroCosto']['Cencos_nombre'] = 'No disponible';
				}
				if ( empty($equipo['Producto']['prousu_modelo']) )
				{
					$equipo['Producto']['prousu_modelo'] = 'No disponible';
				}
				$filas_tabla .= '<tr><td><a target="_blank" href="/equipos/ver/'.$equipo['Producto']['prousu_placa'].'" title="Ver información completa del equipo">'.$equipo['Producto']['prousu_placa'].'</a></td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($equipos_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}

	//--------------------------------------------------------------------------

	function __crear_filas_xls($equipos_info)
	{
		$this->loadModel('CentroCosto');
		$datos_json['resultado'] = false;
		if ( isset($equipos_info[0]['Producto']) )
		{
			$filas_tabla = '';
			foreach ( $equipos_info as $equipo )
			{
				$cenco = $this->CentroCosto->find('first', array
				(
					'fields' => array('CentroCosto.Cencos_nombre'),
					'conditions' => array('CentroCosto.Cencos_id' => $equipo['Usuario']['Usu_Cencos_id'])
				));
				if ( empty($cenco) )
				{
					$cenco['CentroCosto']['Cencos_nombre'] = 'No disponible';
				}
				if ( empty($equipo['Producto']['prousu_modelo']) )
				{
					$equipo['Producto']['prousu_modelo'] = 'No disponible';
				}
				if ( empty($equipo['Producto']['prousu_marca']) )
				{
					$equipo['Producto']['prousu_marca'] = 'No disponible';
				}
				$filas_tabla .= '<tr><td>'.$equipo['Producto']['prousu_placa'].'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($equipo['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").'</td>';
				$filas_tabla .= '<td>'.$equipo['Producto']['prousu_valor'].'</td>';
				$filas_tabla .= '<td>'.$equipo['Producto']['prousu_fecha_compra'].'</td></tr>';
			}
			$datos_json['filas_tabla'] = $filas_tabla;
			$datos_json['count'] = count($equipos_info);
			$datos_json['resultado'] = true;
		}
		return $datos_json;
	}

	//--------------------------------------------------------------------------

	function info_equipo_pdf($placa_inventario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		$this->loadModel('CentroCosto');
		$this->loadModel('SmuqUsuario');

		$producto = $this->Producto->find('first', array('conditions' => array('Producto.prousu_placa' => $placa_inventario)));
		$equipo = $this->Equipo->find('first',  array('conditions' => array('Equipo.placa_inventario' => $placa_inventario)));
		if ( !empty($producto) )
		{
			$producto['Usuario']['Usu_nombre'] = mb_convert_case($producto['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
			$smuq_usuario = $this->SmuqUsuario->findByCedula($producto['Usuario']['Usu_cedula']);
			if ( empty($smuq_usuario) || $smuq_usuario['SmuqUsuario']['cargo'] == '' )
			{
				$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible';
			}

			// Determinamos si la Dependencia tiene un Edificio asociado.
			$dependencia = $this->Dependencia->findByCencosId($producto['Usuario']['Usu_Cencos_id']);
			if ( !empty($dependencia) )
			{
				$edificio = $this->Edificio->findById($dependencia['Dependencia']['id_edificio']);
				if ( empty($edificio) )
				{
					$edificio['Edificio']['name'] = 'No disponible';
					$edificio['Edificio']['id'] = 0;
				}
			}
			else
			{
				$edificio['Edificio']['name'] = 'No disponible';
				$edificio['Edificio']['id'] = 0;
			}
			$nombre_foto = 'No disponible.';
			if ( $equipo['Equipo']['nombre_foto'] != '' )
			{
				$nombre_foto = '<img src="/app/webroot/equipos/fotos/'.$equipo['Equipo']['nombre_foto'].'" alt="" />';
			}
			if ( !isset($equipo['Equipo']['fecha_recibido_satisfaccion']) )
			{
				$equipo['Equipo']['fecha_recibido_satisfaccion'] = 'No disponible';
			}
			if ( !empty($producto['Producto']['prousu_modelo']) )
			{
				$producto['Producto']['prousu_modelo'] = mb_convert_case($producto['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_modelo'] = 'No disponible.';
			}
			if ( !empty($producto['Producto']['prousu_marca']) )
			{
				$producto['Producto']['prousu_marca'] = mb_convert_case($producto['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_marca'] = 'No disponible.';
			}
			$producto['Producto']['prousu_pro_nombre'] = mb_convert_case($producto['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");

			$centro_costo = $this->CentroCosto->findByCencosId($producto['Usuario']['Usu_Cencos_id']);
			$centro_costo['CentroCosto']['Cencos_nombre'] = mb_convert_case($centro_costo['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");

			$filas_tabla =
			'<table width="100%" cellspacing="0" cellpadding="3" border="0"><tbody>
				<tr align="left"><td width="85"><img src="/app/webroot/img/logouq.gif" alt="" /></td></tr>
				<tr><td height="20"></td></tr>
				<tr align="left"><td width="*" align="center"><b>INFORMACIÓN DEL EQUIPO: '.$producto['Producto']['prousu_placa'].'</b></td></tr>
				<tr><td height="20"></td></tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left">
					<td width="60"><b>Nombre:</b></td>
					<td width="*" colspan="3">'.$producto['Producto']['prousu_pro_nombre'].'</td>
				</tr>
				<tr align="left">
					<td width="60"><b>Modelo:</b></td>
					<td width="190">'.$producto['Producto']['prousu_modelo'].'</td>
					<td width="50"><b>Marca:</b></td>
					<td width="*">'.$producto['Producto']['prousu_marca'].'</td>
				</tr>
				<tr align="left">
					<td width="110"><b>Placa de Inventario:</b></td>
					<td width="*" colspan="3">'.$placa_inventario.'</td>
				</tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="3" border="0"><tbody>
				<tr><td height="10" colspan="4"></td></tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left">
					<td width="90"><b>Edificio:</b></td>
					<td width="*" colspan="3">'.$edificio['Edificio']['name'].'</td>
				</tr>
				<tr align="left">
					<td width="90"><b>Dependencia:</b></td>
					<td width="*" colspan="3">'.$centro_costo['CentroCosto']['Cencos_nombre'].'</td>
				</tr>
				<tr align="left">
					<td width="90"><b>Responsable:</b></td>
					<td width="*" colspan="3">'.$producto['Usuario']['Usu_nombre'].'</td>
				</tr>
				<tr align="left">
					<td width="90"><b>Cargo:</b></td>
					<td width="*" colspan="3">'.$smuq_usuario['SmuqUsuario']['cargo'].'</td>
				</tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="3" border="0"><tbody>
				<tr><td height="10" colspan="4"></td></tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left">
					<td width="100"><b>Valor de Compra:</b></td>
					<td width="*" colspan="3">$'.$producto['Producto']['prousu_valor'].'</td>
				</tr>
				<tr align="left">
					<td width="180"><b>Fecha de Compra:</b></td>
					<td width="*" colspan="3">'.$producto['Producto']['prousu_fecha_compra'].'</td>
				</tr>
				<tr align="left">
					<td width="180"><b>Fecha de Recibido a satisfacción:</b></td>
					<td width="*" colspan="3">'.$equipo['Equipo']['fecha_recibido_satisfaccion'].'</td>
				</tr>
			</tbody></table>

			<table width="100%" cellspacing="0" cellpadding="5" border="0"><tbody>
				<tr><td height="10" colspan="4"></td></tr>
				<tr align="left">
					<td width="60"><b>Foto:</b></td>
					<td width="*" colspan="3">'.$nombre_foto.'</td>
				</tr>
			</tbody></table>';
			return $filas_tabla;
		}
		else
		{
			return 'false';
		}
	}

	//--------------------------------------------------------------------------

	function info_equipo($placa)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$equipo_info = $this->Producto->findByProusuPlaca($placa);
		return json_encode($this->__crear_filas(array($equipo_info)));
	}

	//--------------------------------------------------------------------------

	function usr_info_equipo($placa)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$equipo_info = $this->Producto->find('first', array
		(
			'conditions' => array
			(
				'Producto.prousu_usu_cedula' => $this->Session->read('Usuario.cedula'),
				'Producto.prousu_placa' => $placa,
			)
		));
		return json_encode($this->__crear_filas(array($equipo_info)));
	}

	//--------------------------------------------------------------------------

	function ver($placa_inventario)
	{
		$this->loadModel('Edificio');
		$this->loadModel('Dependencia');
		$this->loadModel('CentroCosto');
		$this->loadModel('SmuqUsuario');

		$producto = $this->Producto->find('first', array('conditions' => array('Producto.prousu_placa' => $placa_inventario)));
		$equipo = $this->Equipo->find('first',  array('conditions' => array('Equipo.placa_inventario' => $placa_inventario)));
		if ( !empty($producto) )
		{
			$producto['Usuario']['Usu_nombre'] = mb_convert_case($producto['Usuario']['Usu_nombre'], MB_CASE_TITLE, "UTF-8");
			$smuq_usuario = $this->SmuqUsuario->findByCedula($producto['Usuario']['Usu_cedula']);
			if ( empty($smuq_usuario) || $smuq_usuario['SmuqUsuario']['cargo'] == '' )
			{
				$smuq_usuario['SmuqUsuario']['cargo'] = 'No disponible.';
			}

			// Determinamos si la Dependencia tiene un Edificio asociado.
			$dependencia = $this->Dependencia->findByCencosId($producto['Usuario']['Usu_Cencos_id']);
			if ( !empty($dependencia) )
			{
				$edificio = $this->Edificio->findById($dependencia['Dependencia']['id_edificio']);
				if ( empty($edificio) )
				{
					$edificio['Edificio']['name'] = 'No disponible.';
					$edificio['Edificio']['id'] = 0;
				}
			}
			else
			{
				$edificio['Edificio']['name'] = 'No disponible.';
				$edificio['Edificio']['id'] = 0;
			}

			if ( $equipo['Equipo']['nombre_foto'] != '' )
			{
				$nombre_foto = '<img src="/equipos/fotos/'.$equipo['Equipo']['nombre_foto'].'" alt="" />';
				$equipo['Equipo']['nombre_foto'] = $nombre_foto;
			}
			else
			{
				$equipo['Equipo']['nombre_foto'] = 'No disponible.';
			}
			if ( !empty($producto['Producto']['prousu_modelo']) )
			{
				$producto['Producto']['prousu_modelo'] = mb_convert_case($producto['Producto']['prousu_modelo'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_modelo'] = 'No disponible.';
			}
			if ( !empty($producto['Producto']['prousu_marca']) )
			{
				$producto['Producto']['prousu_marca'] = mb_convert_case($producto['Producto']['prousu_marca'], MB_CASE_TITLE, "UTF-8");
			}
			else
			{
				$producto['Producto']['prousu_marca'] = 'No disponible.';
			}
			$producto['Producto']['prousu_pro_nombre'] = mb_convert_case($producto['Producto']['prousu_pro_nombre'], MB_CASE_TITLE, "UTF-8");

			if ( empty($equipo['Equipo']['fecha_recibido_satisfaccion']) )
			{
				$equipo['Equipo']['fecha_recibido_satisfaccion'] = 'No disponible';
			}
			$centro_costo = $this->CentroCosto->findByCencosId($producto['Usuario']['Usu_Cencos_id']);
			$centro_costo['CentroCosto']['Cencos_nombre'] = mb_convert_case($centro_costo['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");

			// Buscamos archivos del equipo.
			$json_archivos = $this->requestAction('/equipos/buscar_equipo_archivos/'.$placa_inventario);
			$archivos_equipo = json_decode($json_archivos, true);
			if ( $archivos_equipo['encontro_equipo'] )
			{
				if ( count($archivos_equipo['certificados']) > 0 )
				{
					$lista_certificados = '<ul>';
					foreach ( $archivos_equipo['certificados'] as $certificado )
					{
						$lista_certificados .= '<li><a href="/equipos/certificados/'.$certificado['nombre_archivo'].'">'.$certificado['nombre_archivo'].'</li>';
					}
					$lista_certificados .= '</ul>';
					$lista_archivos['certificados'] = $lista_certificados;
				}
				else
				{
					$lista_archivos['certificados'] = 'No hay ningún certificado asignado al equipo.';
				}

				if ( count($archivos_equipo['garantias']) > 0 )
				{
					$lista_garantias = '<ul>';
					foreach ( $archivos_equipo['garantias'] as $garantia )
					{
						$lista_garantias .= '<li><a href="/equipos/garantias/'.$garantia['nombre_archivo'].'">'.$garantia['nombre_archivo'].'</li>';
					}
					$lista_garantias .= '</ul>';
					$lista_archivos['garantias'] = $lista_garantias;
				}
				else
				{
					$lista_archivos['garantias'] = 'No hay ninguna garantía asignada al equipo.';
				}

				if ( count($archivos_equipo['manuales']) > 0 )
				{
					$lista_manuales = '<ul>';
					foreach ( $archivos_equipo['manuales'] as $manual )
					{
						$lista_manuales .= '<li><a href="/equipos/manuales/'.$manual['nombre_archivo'].'">'.$manual['nombre_archivo'].'</li>';
					}
					$lista_manuales .= '</ul>';
					$lista_archivos['manuales'] = $lista_manuales;
				}
				else
				{
					$lista_archivos['manuales'] = 'No hay ningún manual asignado al equipo.';
				}

				if ( count($archivos_equipo['facturas']) > 0 )
				{
					$lista_facturas = '<ul>';
					foreach ( $archivos_equipo['facturas'] as $factura )
					{
						$lista_facturas .= '<li><a href="/equipos/facturas/'.$factura['nombre_archivo'].'">'.$factura['nombre_archivo'].'</li>';
					}
					$lista_facturas .= '</ul>';
					$lista_archivos['facturas'] = $lista_facturas;
				}
				else
				{
					$lista_archivos['facturas'] = 'No hay ninguna factura asignada al equipo.';
				}

				if ( count($archivos_equipo['cotizaciones']) > 0 )
				{
					$lista_cotizaciones = '<ul>';
					foreach ( $archivos_equipo['cotizaciones'] as $cotizacion )
					{
						$lista_cotizaciones .= '<li><a href="/equipos/cotizaciones/'.$cotizacion['nombre_archivo'].'">'.$cotizacion['nombre_archivo'].'</li>';
					}
					$lista_cotizaciones .= '</ul>';
					$lista_archivos['cotizaciones'] = $lista_cotizaciones;
				}
				else
				{
					$lista_archivos['cotizaciones'] = 'No hay ninguna cotización asignada al equipo.';
				}
			}

			$this->set('lista_archivos', $lista_archivos);
			$this->set('producto', $producto);
			$this->set('equipo', $equipo);
			$this->set('edificio', $edificio);
			$this->set('centro_costo', $centro_costo);
			$this->set('smuq_usuario', $smuq_usuario);
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
			$this->set('opcion_seleccionada', 'consultar_equipos');
		}
	}

	//--------------------------------------------------------------------------

	function buscar($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_usuario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');

		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();

		if ( $criterio_usuario != 0 )
		{
			$pre_con['prousu_usu_cedula'] = $criterio_usuario;
		}
		else if ( $criterio_dependencia != 0 )
		{
			// Obtenemos cédulas que pertenecen al centro de costo del criterio.
			$usuarios = $this->Usuario->find('all', array
			(
				'fields' => array('Usuario.Usu_cedula'),
				'conditions' => array('Usuario.Usu_Cencos_id' => $criterio_dependencia)
			));
			$cedulas = array();
			foreach ( $usuarios as $usuario )
			{
				$cedulas[] = $usuario['Usuario']['Usu_cedula'];
			}
			$pre_con['Producto.prousu_usu_cedula'] = $cedulas;
		}

		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo == 'prousu_usu_cedula' )
			{
				$condiciones['Producto.prousu_usu_cedula'] = $frase_busqueda;
			}
			else if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['prousu_pro_nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_placa LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_marca LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_modelo LIKE'] = '%'.$frase_busqueda.'%';
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

		$equipos = $this->Producto->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas($equipos));
	}

	//--------------------------------------------------------------------------

	function usr_buscar($frase_busqueda, $criterio_campo)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');

		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();

		$pre_con['Producto.prousu_usu_cedula'] = $this->Session->read('Usuario.cedula');

		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['prousu_pro_nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_marca LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_modelo LIKE'] = '%'.$frase_busqueda.'%';
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

		$equipos = $this->Producto->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas($equipos));
	}

	//--------------------------------------------------------------------------

	function buscar_xls($frase_busqueda, $criterio_campo, $criterio_dependencia, $criterio_usuario)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');

		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();

		if ( $criterio_usuario != 0 )
		{
			$pre_con['prousu_usu_cedula'] = $criterio_usuario;
		}
		else if ( $criterio_dependencia != 0 )
		{
			// Obtenemos cédulas que pertenecen al centro de costo del criterio.
			$usuarios = $this->Usuario->find('all', array
			(
				'fields' => array('Usuario.Usu_cedula'),
				'conditions' => array('Usuario.Usu_Cencos_id' => $criterio_dependencia)
			));
			$cedulas = array();
			foreach ( $usuarios as $usuario )
			{
				$cedulas[] = $usuario['Usuario']['Usu_cedula'];
			}
			$pre_con['Producto.prousu_usu_cedula'] = $cedulas;
		}

		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo == 'prousu_usu_cedula' )
			{
				$condiciones['Producto.prousu_usu_cedula'] = $frase_busqueda;
			}
			else if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['prousu_pro_nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_placa LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_marca LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_modelo LIKE'] = '%'.$frase_busqueda.'%';
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

		$equipos = $this->Producto->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas_xls($equipos));
	}

	//--------------------------------------------------------------------------

	function usr_buscar_xls($frase_busqueda, $criterio_campo)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('Usuario');

		$condiciones = array();
		$pre_con = array();
		$pre_con_like = array();

		$pre_con['Producto.prousu_usu_cedula'] = $this->Session->read('Usuario.cedula');

		if ( $frase_busqueda != 'null' )
		{
			if ( $criterio_campo != 'todos' )
			{
				$condiciones[$criterio_campo.' LIKE'] = '%'.$frase_busqueda.'%';
			}
			else
			{
				$pre_con_like['prousu_pro_nombre LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_marca LIKE'] = '%'.$frase_busqueda.'%';
				$pre_con_like['prousu_modelo LIKE'] = '%'.$frase_busqueda.'%';
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

		$equipos = $this->Producto->find('all', array
		(
			'conditions' => $condiciones
		));
		return json_encode($this->__crear_filas_xls($equipos));
	}

	//--------------------------------------------------------------------------
}
?>
