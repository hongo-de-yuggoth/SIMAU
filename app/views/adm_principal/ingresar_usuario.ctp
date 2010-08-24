<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');

echo $javascript->link('validaciones');
echo $javascript->link('validaciones_ajax');
echo $javascript->link('adm_principal/ingresar_usuario');
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
	
	<div id='subtitulo_pagina'>Ingresar un nuevo usuario</div>
	<div id="contenido_vista">
		<form id="usuario" name="usuario" action="/smuq_usuarios/crear" method="post" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos">
				<input id='dependencia' name='data[SmuqUsuario][id_dependencia]' type='hidden' value='1' />
			</div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td class='subtitulo' width='120'>Nombre:</td>
					<td width="210"><input id="nombre" name='data[SmuqUsuario][nombre]' maxlength="60" size='50' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_nombre" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Cédula:</td>
					<td width="300"><input id="cedula" name='data[SmuqUsuario][cedula]' maxlength="9" size='10' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_cedula" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Login:</td>
					<td width="210"><input id="login" name='data[SmuqUsuario][login]' maxlength="15" size='15' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_login" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Clave:</td>
					<td width="210"><input id="clave" name='data[SmuqUsuario][clave]' type='password' maxlength="15" size='15' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_clave" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Clave (otra vez):</td>
					<td width="210"><input id="clave2" type='password' maxlength="15" size='15' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="330" class="textoError"><div id="error_clave2" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Email:</td>
					<td width="210"><input id="email" type='text' name='data[SmuqUsuario][email]' maxlength="100" size='45' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_email" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Teléfono:</td>
					<td width="210"><input id="telefono" type='text' name='data[SmuqUsuario][telefono]' maxlength="15" size='15' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_telefono" style="display:none;" /></td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				<tr><td height="1" class="linea" colspan="2"/></tr>
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Tipo de Usuario:</td>
					<td>
						<select id='tipo_usuario' name='data[SmuqUsuario][id_grupo]'>
							<option value='2'>Administrador de Soluciones</option>
							<option value='1'>Administrador Principal</option>
						</select>
					</td>
				</tr>
				
				<tr><td height="10" colspan="2"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Cargo:</td>
					<td><input id="cargo" name='data[SmuqUsuario][cargo]' maxlength="70" size='35' /></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" class="textoError"><div id="error_cargo" style="display:none;" /></td>
				</tr>
				
				<tr><td height="15" colspan="2"/></tr>
				
				<tr align="left">
					<td width='100%' valign="top" align="center" colspan="2"><input type="submit" value="Ingresar Usuario"/></td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
