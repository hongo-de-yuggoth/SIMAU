<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');
echo $html->css('calendario');

echo $javascript->link('calendario');
echo $javascript->link('adm_principal/modificar_equipo');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Administración de Equipos</div>

	<div id="cuadro_notificaciones" class="<?php echo $clase_notificacion; ?>" style="display: <?php echo $display_notificacion; ?>;">
		<?php echo $mensaje_notificacion; ?>
	</div>

	<div id='barra_menu_equipo' style='margin-top:15px;'>
		<ul>
			<?php echo $opciones_menu_2; ?>
		</ul>
	</div>
	<div id='subtitulo_pagina'>Modificar un equipo</div>
	<div id="contenido_vista">
		<form id="equipo" name="equipo" action="#" method="post" enctype="multipart/form-data" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos">
				<input id="encontro" type="hidden" value='' />
			</div>
			<input id='placa_inventario' name='data[Equipo][placa_inventario]' type='hidden' value=''>
			<input id='tmp_resultado' type='hidden' value='' />
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td class='subtitulo' width='100'>Placa de Inventario:</td>
					<td width="70" align="left"><input id="placa_inventario_buscar" maxlength="10" size='9' /></td>
					<td style="padding-left: 5px;" width="*"><input id='boton_buscar_equipo' type="button" value="Buscar equipo"></td>
				</tr>
				
				<tr align="left">
					<td width='100'></td>
					<td width="300" colspan="2" class="textoError"><div id="error_placa_buscar" style="display:none;"></td>
				</tr>
			</tbody></table>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align='left'>
					<td width="100%">
						<div id='info_equipo' style='display:none;'>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr><td height="10" colspan="4"/></tr>
								<tr><td height="1" class="linea" colspan="4"/></tr>
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='160'>Nombre:</td>
									<td colspan="3" width="210"><div id="nombre"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Modelo:</td>
									<td width="210"><div id="modelo"></div></td>
									<td class='subtitulo' width='45'>Marca:</td>
									<td><div id="marca"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Placa de Inventario:</td>
									<td colspan="3" width="210"><div id="placa_inventario_div"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								<tr><td height="1" class="linea" colspan="4"/></tr>
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Edificio:</td>
									<td colspan="3"><div id="edificio"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Dependencia:</td>
									<td colspan="3"><div id="dependencia"></div></td>
								</tr>
							
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Responsable:</td>
									<td colspan="3"><div id="responsable"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
					
								<tr align="left">
									<td class='subtitulo' width='90'>Cargo:</td>
									<td colspan="3"><div id='cargo'></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								<tr><td height="1" class="linea" colspan="4"/></tr>
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Valor de Compra:</td>
									<td colspan="3"><div id="valor_compra"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Fecha de Compra:</td>
									<td colspan="3"><div id="fecha_compra"></div></td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
					
								<tr align="left">
									<td class='subtitulo' width='120'>Fecha de Recibido a satisfacción:</td>
									<td colspan="3">
										<input id="fecha_recibido_satisfaccion" name='data[Equipo][fecha_recibido_satisfaccion]' type='text' readonly value='' maxlength="10" size='9' />
										<input id='boton_cal_2' type="button" value="Seleccionar fecha" />
									</td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="100" colspan="3" class="textoError"><div id="error_fecha_recibido_satisfaccion" style="display:none;" /></td>
								</tr>
	
								<tr><td height="10" colspan="4"/></tr>
								<tr><td height="1" class="linea" colspan="4"/></tr>
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td width='100%' colspan="4">
										<div id='div_foto' style='display:none;'>
											<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
												<tr align="left">
													<td width='200'><div id='foto_equipo'><img border="0" alt="" src="" /></div></td>
													<td valign='bottom'><input id="boton_borrar_foto" type='button' value='Borrar esta foto' /></td>
												</tr>
											</tbody></table>
										</div>
									</td>
								</tr>
								<tr align="left">
									<td width='100%' colspan="4">
										<div id='div_msj_foto' align='center' style='display:none; color:green; vertical-align:middle;'>Se ha eliminado la imagen.</div>
									</td>
								</tr>
								
								<tr><td height="10" colspan="4"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Nueva Foto:</td>
									<td colspan="3"><input id="archivo_foto" name='data[File][archivo_foto]' type='file' /></td>
								</tr>
								
								<tr><td height="15" colspan="4"/></tr>
								
								<tr align="left">
									<td width='100%' valign="top" align="center" colspan="4"><input type="submit" value="Guardar cambios"/></td>
								</tr>
							</tbody></table>
						</div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
