<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');

echo $javascript->link('adm_principal/consultar_reportes');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Consulta de Reportes Estadísticos</div>
	<div id='subtitulo_pagina'></div>
	<div id="contenido_vista">
		<form id="solicitudes" name="solicitudes" action="#" method="post" >
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			<input type="hidden" value="<?php echo $listado_años; ?>" id="listado_años"/>
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td>
						<div id='menu_consulta'>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr align="left" >
									<td height='25' valign='top' colspan='6'>Selecciona un reporte:</td>
								</tr>
								<tr align="left" >
									<td width='22'>
										<select id="select_reporte">
											<option value="servicios_años">Servicios por años</option>
											<option value="servicios_meses">Servicios por meses</option>
											<option value="solicitudes_dependencia_meses">Solicitudes de una dependencia por años</option>
											<option value="solicitudes_tecnico_años">Solicitudes de un técnico por años</option>
											<option value="costo_externo_interno_año">Costo externo e interno por años</option>
										</select>
									</td>
									<td>
										<div id='div_boton_cargar' align='center'>
											<input id='boton_cargar_reporte' type='button' value='Cargar Reporte' />
										</div>
									</td>
								</tr>
								
								<tr align="left" >
									<td colspan='6' style="padding-top:13px;">
										<fieldset>
											<legend>Configuración del reporte</legend>
											<div id="div_servicios_tecnico" style="display:none;">
												<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
													<tr><td height="10" colspan="4"/></tr>
													<tr align="left">
														<td width='33'> </td>
														<td width='90'>Del técnico:</td>
														<td colspan="2">
															<select id="operarios"><?php echo $select_tecnicos; ?></select>
														</td>
													</tr>
												</tbody></table>
											</div>
											
											<div id="div_servicios_oficina" style="display:none;">
												<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
													<tr><td height="10" colspan="4"/></tr>
													<tr align="left">
														<td width='33'> </td>
														<td width='120'>De la dependencia:</td>
														<td colspan="2">
															<div id="div_oficina" style="display:none;">
																<select id="oficina"><?php echo $opciones_dependencias; ?></select>
															</div>
															<div id="div_oficina_costos" style="display:none;">
																<select id="oficina_costos"><?php echo '<option value="0">Todas las dependencias</option>'.$opciones_dependencias; ?></select>
															</div>
														</td>
													</tr>
												</tbody></table>
											</div>
											
											<div id="div_servicios_años" style="display:block;">
												<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
													<tr><td height="10" colspan="4"/></tr>
													<tr align="left">
														<td width='33'> </td>
														<td width='133'><select id="param_años">
															<option value="todos">Todos los años</option>
															<option value="rango">Rango de años</option>
														</select></td>
														<td colspan="2"><div id="div_rango_años" style="display:none;">
															del <select id="año_inicial"><?php echo $select_año_inicial; ?></select> al
															<select id="año_final"></select>
														</div></td>
													</tr>
												</tbody></table>
											</div>
											
											<div id="div_servicios_meses" style="display:none;">
												<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
													<tr><td height="10" colspan="4"/></tr>
													<tr align="left">
														<td width='33'> </td>
														<td width='60'>Del año:</td>
														<td colspan="2">
															<div id="div_meses_del_año" style="display:none;">
																<select id="año_meses"><?php echo $select_año_inicial; ?></select>
															</div>
														</td>
													</tr>
												</tbody></table>
											</div>
										</fieldset>
									</td>
								</tr>
							</tbody></table>
						</div>
						
					</td>
				</tr>
				
				<tr align="left">
					<td>
						<div>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr align="left" ><td height='15'></td></tr>
								<tr align="left" >
									<td height='1' width='100'></td>
									<td class='linea' height='1' ></td>
									<td height='1' width='100'></td>
								</tr>
								<tr align="left" ><td height='15'></td></tr>
							</tbody></table>
						</div>
					</td>
				</tr>
				
				<tr align="left">
					<td>
						<div id='reporte' style='display:block; overflow:auto; width:580px;'>
							<img id='img_reporte' src="" />
						</div>
						<div id="error_consulta" class="textoError" style="display:none;">No se encontraron solicitudes de esta dependencia.</div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
