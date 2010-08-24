<?php
App::import('Vendor', 'phplot', array('file' => 'class.phplot.php'));

//-----------------------------------------------------------------------------

class ReportesEstadisticosController extends AppController
{
	var $name = 'ReportesEstadisticos';
	var $uses = array('Solicitud');
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
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
	
	//--------------------------------------------------------------------------
	
	function chequear($reporte, $param_1, $param_2=0, $param_3=0)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$condiciones = array('Solicitud.estado'=>'s');
		if ( $reporte == 'solicitudes_dependencia_meses' )
		{
			$condiciones['Solicitud.Cencos_id'] = $param_1;
			$condiciones['YEAR(Solicitud.solucionada)'] = $param_2;
			$meses = $this->Solicitud->find('first', array
			(
				'fields' => array('Solicitud.id'),
				'conditions' => $condiciones,
				'group' => array('MONTH(Solicitud.solucionada)')
			));
			if ( !empty($meses) )	// REVIZAR arriba <-
			{
				return 'true';
			}
		}
		else if ( $reporte == 'servicios_años' )
		{
			if ( $param_1 == 'rango' && $param_2 != $param_3 )
			{
				$condiciones['YEAR(Solicitud.solucionada) >='] = $param_2;
				$condiciones['YEAR(Solicitud.solucionada) <='] = $param_3;
			}
			$años = $this->Solicitud->find('first', array
			(
				'fields' => array('Solicitud.id'),
				'conditions' => $condiciones,
				'group' => array('YEAR(Solicitud.solucionada)')
			));
			if ( !empty($años) )
			{
				return 'true';
			}
		}
		else if ( $reporte == 'servicios_meses' )
		{
			$condiciones['YEAR(Solicitud.solucionada)'] = $param_1;
			$meses = $this->Solicitud->find('first', array
			(
				'fields' => array('Solicitud.id'),
				'conditions' => $condiciones
			));
			if ( !empty($meses) )
			{
				return 'true';
			}
		}
		else if ( $reporte == 'solicitudes_tecnico_años' )
		{
			$condiciones['Solicitud.cedula_adm_sol'] = $param_1;
			$condiciones['YEAR(Solicitud.solucionada)'] = $param_2;
			$meses = $this->Solicitud->find('first', array
			(
				'fields' => array('Solicitud.id'),
				'conditions' => $condiciones
			));
			if ( !empty($meses) )
			{
				return 'true';
			}
		}
		else if ( $reporte == 'costo_externo_interno_año' )
		{
			if ( $param_1 != 0 )
			{
				$condiciones['Solicitud.Cencos_id'] = $param_1;
			}
			$condiciones['YEAR(Solicitud.solucionada)'] = $param_2;
			$meses = $this->Solicitud->find('first', array
			(
				'fields' => array('Solicitud.id'),
				'conditions' => $condiciones,
				'group' => array('MONTH(Solicitud.solucionada)')
			));
			if ( !empty($meses) )
			{
				return 'true';
			}
		}
		
		return 'false';
	}
	
	//--------------------------------------------------------------------------
	
	function servicios_años($param, $año_inicial, $año_final)	// :)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$total_solicitudes = 0;
		
		// Obtenemos los años existentes, segun el parámetro de configuración.
		$param_año = '';
		if ( $param == 'rango' )
		{
			if ( $año_inicial == $año_final )
			{
				$años[] = array(array('year'=>$año_inicial));
			}
			else
			{
				$param_año = 'AND YEAR(solucionada)>= '.$año_inicial.' AND YEAR(solucionada)<= '.$año_final;
			}
		}
		if ( !isset($años) )
		{
			$años = $this->Solicitud->query("SELECT YEAR(solucionada) AS year FROM solicitudes WHERE estado='s' ".$param_año." GROUP BY YEAR(solucionada)");
		}
		if ( !empty($años) )
		{
			$total = array();
			foreach ( $años as $año )
			{
				//$cantidad_solicitudes = $this->Solicitud->query("SELECT tipo_servicio, COUNT(*) AS cuenta FROM solicitudes WHERE estado='s' AND YEAR(solucionada)=".$año[0]['year']);
				$cantidad_solicitudes = $this->Solicitud->query("SELECT tipo_servicio FROM solicitudes WHERE estado='s' AND YEAR(solucionada)=".$año[0]['year']);
				
				// Recreamos el arreglo de cant_solicitudes
				$cuentas = array
				(
					'1'=>0,
					'2'=>0,
					'3'=>0
				);
				foreach ( $cantidad_solicitudes as $cant_tipo_solicitud )
				{
					$ts = split(',', $cant_tipo_solicitud['solicitudes']['tipo_servicio']);
					for ( $i=0; $i < count($ts); $i++ )
					{
						switch ( $ts[$i] )
						{
							case '1':	++$cuentas[1]; break;
							case '2':	++$cuentas[2]; break;
							case '3':	++$cuentas[3]; break;
						}
					}
				}
				for ( $i=0; $i < 3; $i++ )
				{
					$cant_solicitudes[$i] = array
					(
						'solicitudes' =>  array('tipo_servicio'=>$i+1),
						0 => array('cuenta'=>$cuentas[$i+1])
					);
				}
				
				$total[$año[0]['year']] = $cant_solicitudes;
				for ( $j=0; $j < count($cant_solicitudes); $j++  )
				{
					$total_solicitudes += $cant_solicitudes[$j][0]['cuenta'];
				}
			}
			if ( !empty($total) )
			{
				$arreglo_plot = array();
				foreach ( $total as $año=>$arreglo_año )
				{
					// se construye el array para el PHPlot.
					$ts_1 = $ts_2 = $ts_3 = 0;
					for ( $i=0; $i < count($arreglo_año); $i++ )
					{
						switch ( $arreglo_año[$i]['solicitudes']['tipo_servicio'] )
						{
							case '1': $ts_1 = $arreglo_año[$i][0]['cuenta']; break;
							case '2': $ts_2 = $arreglo_año[$i][0]['cuenta']; break;
							case '3': $ts_3 = $arreglo_año[$i][0]['cuenta']; break;
						}
					}
					$arreglo_plot[] = array($año, $ts_1, $ts_2, $ts_3);
				}
				
				$plot = new PHPlot();
				$plot->SetDataValues($arreglo_plot);
				$plot->SetDataType('text-data');
				
				// Fuentes
				$plot->SetUseTTF(true);
				$plot->SetFontTTF('legend', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('y_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('x_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('y_title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('x_title', 'FreeSans.ttf', 12);
				
				// Titulos
				$plot->SetTitle("\nSolicitudes de\nservicio por años\nTOTAL: ".$total_solicitudes);
				$plot->SetXTitle('AÑOS');
				$plot->SetYTitle('# SOLICITUDES');
				
				// Etiquetas
				$plot->SetXTickLabelPos('none');
				$plot->SetXTickPos('none');
				$plot->SetYTickLabelPos('none');
				$plot->SetYTickPos('none');
				$plot->SetYDataLabelPos('plotin');
				$plot->SetDrawXGrid(true);
				
				// Leyenda
				$leyenda = array('Mantenimiento Preventivo', 'Mantenimiento Correctivo', 'Calibraciones/Certificados');
				$plot->SetLegend($leyenda);
				$plot->SetLegendPixels(414, 0);
				$plot->SetDataColors(array('beige', 'YellowGreen', 'SkyBlue'));
				$plot->SetPlotType('bars');
				$plot->SetShading(7);
				
				$plot->DrawGraph();
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function servicios_meses($año)	// :)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$total_solicitudes = 0;
		$meses = $this->Solicitud->query("SELECT MONTH(solucionada) AS mes FROM solicitudes WHERE estado='s' AND YEAR(solucionada)=".$año." GROUP BY MONTH(solucionada)");
		if ( !empty($meses) )
		{
			// Inicializamos el arreglo en ceros (para los meses ke no tienen solicitudes).
			$total = array();
			for ( $i=1; $i <= 12; $i++ )
			{
				$total[$i][0][0] = array('cuenta'=>0);
			}
			foreach ( $meses as $mes )
			{
				$cantidad_solicitudes = $this->Solicitud->query("SELECT tipo_servicio FROM solicitudes WHERE estado='s' AND YEAR(solucionada)=".$año." AND MONTH(solucionada)=".$mes[0]['mes']);
				
				// Recreamos el arreglo de cant_solicitudes
				$cuentas = array
				(
					'1'=>0,
					'2'=>0,
					'3'=>0
				);
				foreach ( $cantidad_solicitudes as $cant_tipo_solicitud )
				{
					$ts = split(',', $cant_tipo_solicitud['solicitudes']['tipo_servicio']);
					for ( $i=0; $i < count($ts); $i++ )
					{
						switch ( $ts[$i] )
						{
							case '1':	++$cuentas[1]; break;
							case '2':	++$cuentas[2]; break;
							case '3':	++$cuentas[3]; break;
						}
					}
				}
				for ( $i=0; $i < 3; $i++ )
				{
					$cant_solicitudes[$i] = array
					(
						'solicitudes' =>  array('tipo_servicio'=>$i+1),
						0 => array('cuenta'=>$cuentas[$i+1])
					);
				}
				
				$total[$mes[0]['mes']] = $cant_solicitudes;
				for ( $j=0; $j < count($cant_solicitudes); $j++  )
				{
					$total_solicitudes += $cant_solicitudes[$j][0]['cuenta'];
				}
			}
			if ( !empty($total) )
			{
				foreach ( $total as $mes=>$arreglo_mes )
				{
					// se construye el array para el PHPlot.
					$ts_1 = $ts_2 = $ts_3 = 0;
					for ( $i=0; $i < count($arreglo_mes); $i++ )
					{
						switch ( $arreglo_mes[$i]['solicitudes']['tipo_servicio'] )
						{
							case '1': $ts_1 = $arreglo_mes[$i][0]['cuenta']; break;
							case '2': $ts_2 = $arreglo_mes[$i][0]['cuenta']; break;
							case '3': $ts_3 = $arreglo_mes[$i][0]['cuenta']; break;
						}
					}
					$arreglo_plot[] = array($this->meses[$mes], $ts_1, $ts_2, $ts_3);
				}
				
				$plot = new PHPlot(890, 450);
				$plot->SetDataValues($arreglo_plot);
				$plot->SetDataType('text-data');
				
				// Fuentes
				$plot->SetUseTTF(true);
				$plot->SetFontTTF('legend', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('y_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('x_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('y_title', 'FreeSans.ttf', 14);
				
				// Titulos
				$plot->SetTitle("\nSolicitudes de\nservicio en el año ".$año."\nTOTAL: ".$total_solicitudes);
				//$plot->SetXTitle('AÑO '.$año);
				$plot->SetYTitle('# SOLICITUDES');
				
				// Etiquetas
				$plot->SetXTickLabelPos('none');
				$plot->SetXTickPos('none');
				$plot->SetYTickLabelPos('none');
				$plot->SetYTickPos('none');
				$plot->SetYDataLabelPos('plotin');
				$plot->SetDrawXGrid(true);
				
				// Leyenda
				$leyenda = array('Mantenimiento Preventivo', 'Mantenimiento Correctivo', 'Calibraciones/Certificados');
				$plot->SetLegend($leyenda);
				$plot->SetLegendPixels(704, 0);
				$plot->SetDataColors(array('beige', 'YellowGreen', 'SkyBlue'));
				$plot->SetPlotType('bars');
				$plot->SetShading(7);
				
				$plot->DrawGraph();
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function solicitudes_dependencia_meses($id_oficina, $año)	// :)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('CentroCosto');
		$total_solicitudes = 0;
		
		$meses = $this->Solicitud->query("SELECT MONTH(solucionada) AS mes FROM solicitudes WHERE estado='s' AND Cencos_id='".$id_oficina."' AND YEAR(solucionada)=".$año." GROUP BY MONTH(solucionada)");
		if ( !empty($meses) )
		{
			// Inicializamos el arreglo en ceros (para los meses ke no tienen solicitudes).
			$total = array();
			for ( $i=1; $i <= 12; $i++ )
			{
				$total[$i][0][0] = array('cuenta'=>0);
			}
			
			foreach ( $meses as $mes )
			{
				$cantidad_solicitudes = $this->Solicitud->query("SELECT tipo_servicio FROM solicitudes WHERE estado='s' AND Cencos_id='".$id_oficina."' AND YEAR(solucionada)=".$año." AND MONTH(solucionada)=".$mes[0]['mes']);
				
				//-->
				// Recreamos el arreglo de cant_solicitudes
				$cuentas = array
				(
					'1'=>0,
					'2'=>0,
					'3'=>0
				);
				foreach ( $cantidad_solicitudes as $cant_tipo_solicitud )
				{
					$ts = split(',', $cant_tipo_solicitud['solicitudes']['tipo_servicio']);
					for ( $i=0; $i < count($ts); $i++ )
					{
						switch ( $ts[$i] )
						{
							case '1':	++$cuentas[1]; break;
							case '2':	++$cuentas[2]; break;
							case '3':	++$cuentas[3]; break;
						}
					}
				}
				for ( $i=0; $i < 3; $i++ )
				{
					$cant_solicitudes[$i] = array
					(
						'solicitudes' =>  array('tipo_servicio'=>$i+1),
						0 => array('cuenta'=>$cuentas[$i+1])
					);
				}
				//--->
				
				$total[$mes[0]['mes']] = $cant_solicitudes;
				
				for ( $j=0; $j < count($cant_solicitudes); $j++  )
				{
					$total_solicitudes += $cant_solicitudes[$j][0]['cuenta'];
				}
			}
			if ( !empty($total) )
			{
				$cenco = $this->CentroCosto->find('first', array('conditions'=>array('CentroCosto.Cencos_id'=>$id_oficina)));
				foreach ( $total as $mes=>$arreglo_mes )
				{
					// se construye el array para el PHPlot.
					$ts_1 = $ts_2 = $ts_3 = 0;
					for ( $i=0; $i < count($arreglo_mes); $i++ )
					{
						switch ( $arreglo_mes[$i]['solicitudes']['tipo_servicio'] )
						{
							case '1': $ts_1 += $arreglo_mes[$i][0]['cuenta']; break;
							case '2': $ts_2 += $arreglo_mes[$i][0]['cuenta']; break;
							case '3': $ts_3 += $arreglo_mes[$i][0]['cuenta']; break;
						}
					}
					$arreglo_plot[] = array($this->meses[$mes], $ts_1, $ts_2, $ts_3);
					
				}
				
				$plot = new PHPlot(890, 450);
				$plot->SetDataValues($arreglo_plot);
				$plot->SetDataType('text-data');
				
				// Fuentes
				$plot->SetUseTTF(true);
				$plot->SetFontTTF('legend', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('y_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('x_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('y_title', 'FreeSans.ttf', 14);
				
				// Titulos
				$plot->SetTitle("\nSolicitudes de servicio\nrealizadas por\n".
				mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8").
				"  en el año ".$año."\nTOTAL: ".$total_solicitudes);
				$plot->SetYTitle('# SOLICITUDES');
				
				// Etiquetas
				$plot->SetXTickLabelPos('none');
				$plot->SetXTickPos('none');
				$plot->SetYTickLabelPos('none');
				$plot->SetYTickPos('none');
				$plot->SetYDataLabelPos('plotin');
				$plot->SetDrawXGrid(true);
				
				// Leyenda
				$leyenda = array('Mantenimiento Preventivo', 'Mantenimiento Correctivo', 'Calibraciones/Certificados');
				$plot->SetLegend($leyenda);
				$plot->SetLegendPixels(704, 0);
				$plot->SetDataColors(array('beige', 'YellowGreen', 'SkyBlue'));
				$plot->SetPlotType('bars');
				$plot->SetShading(7);
				
				$plot->DrawGraph();
			}
		}
	}
	
	//--------------------------------------------------------------------------

	function solicitudes_tecnico_años($cedula_tecnico, $año)	// :)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('SmuqUsuario');
		$total_solicitudes = 0;
		
		$meses = $this->Solicitud->query("SELECT MONTH(solucionada) AS mes FROM solicitudes WHERE estado='s' AND cedula_adm_sol='".$cedula_tecnico."' AND YEAR(solucionada)=".$año." GROUP BY MONTH(solucionada)");
		if ( !empty($meses) )
		{
			// Inicializamos el arreglo en ceros (para los meses ke no tienen solicitudes).
			$total = array();
			for ( $i=1; $i <= 12; $i++ )
			{
				$total[$i][0][0] = array('cuenta'=>0);
			}
			
			foreach ( $meses as $mes )
			{
				$cantidad_solicitudes = $this->Solicitud->query("SELECT tipo_servicio FROM solicitudes WHERE estado='s' AND cedula_adm_sol='".$cedula_tecnico."' AND YEAR(solucionada)=".$año." AND MONTH(solucionada)=".$mes[0]['mes']);
				
				//-->
				// Recreamos el arreglo de cant_solicitudes
				$cuentas = array
				(
					'1'=>0,
					'2'=>0,
					'3'=>0
				);
				foreach ( $cantidad_solicitudes as $cant_tipo_solicitud )
				{
					$ts = split(',', $cant_tipo_solicitud['solicitudes']['tipo_servicio']);
					for ( $i=0; $i < count($ts); $i++ )
					{
						switch ( $ts[$i] )
						{
							case '1':	++$cuentas[1]; break;
							case '2':	++$cuentas[2]; break;
							case '3':	++$cuentas[3]; break;
						}
					}
				}
				for ( $i=0; $i < 3; $i++ )
				{
					$cant_solicitudes[$i] = array
					(
						'solicitudes' =>  array('tipo_servicio'=>$i+1),
						0 => array('cuenta'=>$cuentas[$i+1])
					);
				}
				//--->
				
				$total[$mes[0]['mes']] = $cant_solicitudes;
				
				for ( $j=0; $j < count($cant_solicitudes); $j++  )
				{
					$total_solicitudes += $cant_solicitudes[$j][0]['cuenta'];
				}
			}
			if ( !empty($total) )
			{
				$tecnico = $this->SmuqUsuario->find('first', array('conditions'=>array('SmuqUsuario.cedula'=>$cedula_tecnico)));
				foreach ( $total as $mes=>$arreglo_mes )
				{
					// se construye el array para el PHPlot.
					$ts_1 = $ts_2 = $ts_3 = 0;
					for ( $i=0; $i < count($arreglo_mes); $i++ )
					{
						switch ( $arreglo_mes[$i]['solicitudes']['tipo_servicio'] )
						{
							case '1': $ts_1 += $arreglo_mes[$i][0]['cuenta']; break;
							case '2': $ts_2 += $arreglo_mes[$i][0]['cuenta']; break;
							case '3': $ts_3 += $arreglo_mes[$i][0]['cuenta']; break;
						}
					}
					$arreglo_plot[] = array($this->meses[$mes], $ts_1, $ts_2, $ts_3);
					
				}
				
				$plot = new PHPlot(890, 450);
				$plot->SetDataValues($arreglo_plot);
				$plot->SetDataType('text-data');
				
				// Fuentes
				$plot->SetUseTTF(true);
				$plot->SetFontTTF('legend', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('y_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('x_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('y_title', 'FreeSans.ttf', 14);
				
				// Titulos
				$plot->SetTitle("\nSolicitudes de servicio\natendidas por\n".
				mb_convert_case($tecnico['SmuqUsuario']['nombre'], MB_CASE_TITLE, "UTF-8").
				"  en el año ".$año."\nTOTAL: ".$total_solicitudes);
				$plot->SetYTitle('# SOLICITUDES');
				
				// Etiquetas
				$plot->SetXTickLabelPos('none');
				$plot->SetXTickPos('none');
				$plot->SetYTickLabelPos('none');
				$plot->SetYTickPos('none');
				$plot->SetYDataLabelPos('plotin');
				$plot->SetDrawXGrid(true);
				
				// Leyenda
				$leyenda = array('Mantenimiento Preventivo', 'Mantenimiento Correctivo', 'Calibraciones/Certificados');
				$plot->SetLegend($leyenda);
				$plot->SetLegendPixels(704, 0);
				$plot->SetDataColors(array('beige', 'YellowGreen', 'SkyBlue'));
				$plot->SetPlotType('bars');
				$plot->SetShading(7);
				
				$plot->DrawGraph();
			}
		}
	}
	
	//--------------------------------------------------------------------------
	
	function costo_externo_interno_año($id_oficina, $año)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		$this->loadModel('CentroCosto');
		
		$sql_oficina = '';
		if ( $id_oficina != 0 )
		{
			$sql_oficina = " AND Cencos_id='".$id_oficina."' ";
			$cenco = $this->CentroCosto->find('first', array
			(
				'fields' => array('CentroCosto.Cencos_nombre'),
				'conditions' => array('CentroCosto.Cencos_id' => $id_oficina)
			));
			$subtitulo_oficina = 'la dependencia '.mb_convert_case($cenco['CentroCosto']['Cencos_nombre'], MB_CASE_TITLE, "UTF-8");
		}
		else
		{
			$subtitulo_oficina = 'todas las dependencias';
		}
		
		$meses = $this->Solicitud->query("SELECT MONTH(solucionada) AS mes FROM solicitudes WHERE estado='s' ".$sql_oficina." AND YEAR(solucionada)=".$año." GROUP BY MONTH(solucionada)");
		if ( !empty($meses) )
		{
			// Inicializamos el arreglo en ceros (para los meses ke no tienen solicitudes).
			$totales = array();
			for ( $i=1; $i <= 12; $i++ )
			{
				$totales[$i][0][0] = array
				(
					'costo_i' => 0,
					'costo_e' => 0
				);
			}
			foreach ( $meses as $mes )
			{
				$costos_e_i = $this->Solicitud->query("SELECT SUM(costo_externo) AS costo_e, SUM(costo_interno) AS costo_i FROM solicitudes WHERE estado='s' AND YEAR(solucionada)=".$año." AND MONTH(solucionada)=".$mes[0]['mes']);
				$totales[$mes[0]['mes']] = $costos_e_i;
			}
			if ( !empty($totales) )
			{
				$total_costo_interno = $total_costo_externo = 0;
				$i = 0;
				$arreglo_plot = array();
				foreach ( $totales as $mes=>$arreglo_mes )
				{
					// se construye el array para el PHPlot.
					if ( count($arreglo_mes) > 0 )
					{
						$arreglo_plot[$i] = array($this->meses[$mes], $arreglo_mes[0][0]['costo_i'], $arreglo_mes[0][0]['costo_e']);
						$total_costo_interno += $arreglo_mes[0][0]['costo_i'];
						$total_costo_externo += $arreglo_mes[0][0]['costo_e'];
					}
					else
					{
						$arreglo_plot[$i] = array($this->meses[$mes], 0, 0);
					}
					$i++;
				}
				
				$plot = new PHPlot(1790, 500);
				$plot->SetDataValues($arreglo_plot);
				$plot->SetDataType('text-data');
					
				// Fuentes
				$plot->SetUseTTF(true);
				$plot->SetFontTTF('legend', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('y_label', 'FreeSans.ttf', 9);
				$plot->SetFontTTF('x_label', 'FreeSans.ttf', 10);
				$plot->SetFontTTF('y_title', 'FreeSans.ttf', 14);
				$plot->SetFontTTF('x_title', 'FreeSans.ttf', 12);
				
				// Titulos
				$plot->SetTitle("\nTotal de costos internos/externos\n".
					"de ".$subtitulo_oficina.
					" en el año ".$año.
					"\n TOTAL Costo Interno = $".$total_costo_interno."\n".
					"TOTAL Costo Externo = $".$total_costo_externo);
				$plot->SetYTitle('$ COSTO');
		
				// Etiquetas
				$plot->SetXTickLabelPos('none');
				$plot->SetXTickPos('none');
				$plot->SetYTickLabelPos('none');
				$plot->SetYTickPos('none');
				$plot->SetYDataLabelPos('plotin');
				$plot->SetDrawXGrid(true);
				
				// Leyenda
				$leyenda = array('Costo Interno', 'Costo Externo');
				$plot->SetLegend($leyenda);
				$plot->SetLegendPixels(27, 0);
				
				$plot->SetDataColors(array('beige', 'YellowGreen'));
				$plot->SetPlotType('bars');
				$plot->SetShading(5);
				
				$plot->DrawGraph();
			}
		}
	}
	
	//--------------------------------------------------------------------------
}
?>
