<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');

echo $javascript->link('validaciones');
echo $javascript->link('validaciones_ajax');
echo $javascript->link('adm_principal/usuario');
echo $javascript->link('adm_principal/modificar_usuario');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Administración de Usuarios</div>
	
	<div id="cuadro_notificaciones" class="<?php echo $clase_notificacion; ?>" style="display: <?php echo $display_notificacion; ?>;">
		<?php echo $mensaje_notificacion; ?>
	</div>
	
	<div id='barra_menu_equipo' style='margin-top:15px;'>
		<ul>
			<li><a href='/adm_principal/ingresar_usuario'>Ingresar un Usuario</a></li>
			<li><a href='/adm_principal/modificar_usuario'>Modificar un Usuario</a></li>
			<li><a href='/adm_principal/eliminar_usuario'>Eliminar un Usuario</a></li>
		</ul>
	</div>
	<div id='subtitulo_pagina'>Modificar un usuario</div>
	<div id="contenido_vista">
		<form id="usuario" name="usuario" action="#" method="post" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos"></div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td class='subtitulo' width='120'>Cédula:</td>
					<td align="left" width="150"><input id="cedula_buscar" maxlength="10" size='10' /></td>
					<td style="padding-left: 5px;" width="450"><input id='boton_buscar_usuario' type="button" value="Buscar usuario"></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" colspan="2" class="textoError"><div id="error_cedula_buscar" style="display:none;"></div></td>
				</tr>
			</tbody></table>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align='left'>
					<td width='100%'>
						<div id='info_usuario' style='display:none;'>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr><td height="10" width="100%" colspan="2"/></tr>
								<tr><td height="1" width="100%" class="linea" colspan="2"/></tr>
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Nombre:</td>
									<td width="210">
										<div id="div_nombre"></div>
										<div id="div_nombre_input" style="display:none;"><input id="nombre" maxlength="60" size='50' type="text" /></div>
									</td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="100" class="textoError"><div id="error_nombre" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Cédula:</td>
									<td width="210">
										<div id="div_cedula"></div>
										<div id="div_cedula_input" style="display:none;"><input id="cedula" maxlength="9" size='10' type="text" /></td></div>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="210" class="textoError"><div id="error_cedula" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Login:</td>
									<td width="210">
										<div id="div_login"></div>
										<div id="div_login_input" style="display:none;"><input id="login" maxlength="15" size='15' type="text" /></td></div>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="100" class="textoError"><div id="error_login" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Nueva clave:</td>
									<td width="210"><input id="clave" type='password' maxlength="15" size='15' /></td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="100" class="textoError"><div id="error_clave" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='130'>Nueva clave (otra vez):</td>
									<td width="210"><input id="clave2" type='password' maxlength="15" size='15' /></td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="100" class="textoError"><div id="error_clave2" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Email:</td>
									<td width="210"><input id="email" type='text' maxlength="100" size='45' /></td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="300" class="textoError"><div id="error_email" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Teléfono:</td>
									<td width="210"><input id="telefono" type='text' maxlength="15" size='15' /></td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="300" class="textoError"><div id="error_telefono" style="display:none;" /></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								<tr><td height="1" class="linea" colspan="2"/></tr>
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='120'>Tipo de Usuario:</td>
									<td >
										<div id="div_tipo_usuario" style="display:none;">Usuario de Dependencia</div>
										<div id="div_tipo_usuario_select" style="display:none;">
											<select id='tipo_usuario' >
												<option value='2'>Administrador de Soluciones</option>
												<option value='1'>Administrador Principal</option>
											</select>
										</div>
									</td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Edificio:</td>
									<td ><div id="div_edificio"></div></td>
								</tr>
								
								<tr><td height="10" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='90'>Dependencia:</td>
									<td ><div id="div_dependencia"></div></td>
								</tr>
							
								<tr><td height="10" colspan="2"/></tr>
					
								<tr align="left">
									<td class='subtitulo' width='90'>Cargo:</td>
									<td ><input id="cargo" maxlength="70" size='35' type="text" /></td>
								</tr>
								
								<tr align="left">
									<td width='120'></td>
									<td width="300"  class="textoError"><div id="error_cargo" style="display:none;" /></td>
								</tr>
								
								<tr><td height="15" colspan="2"/></tr>
								
								<tr align="left">
									<td width='100%' valign="top" align="center" colspan="2"><input type="submit" value="Guardar cambios"/></td>
								</tr>
							</tbody></table>
						</div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
