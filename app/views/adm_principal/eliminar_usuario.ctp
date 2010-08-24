<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');

echo $javascript->link('adm_principal/eliminar_usuario');
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
	<div id='subtitulo_pagina'>Eliminar un usuario</div>
	<div id="contenido_vista">
		<form id="usuario" name="usuario" action="#" method="post" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos"></div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td class='subtitulo' width='120'>Cédula:</td>
					<td width="150"><input id="cedula_buscar" maxlength="10" size='10' /></td>
					<td style="padding-left: 5px;" width="550"><input id='boton_buscar_usuario' type="button" value="Buscar usuario"></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="700" colspan="2" class="textoError"><div id="error_cedula_buscar" style="display:none;"></td>
				</tr>
			</tbody></table>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align='left'>
					<td width="100%">
						<div id='info_usuario' style="display:none;">
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr><td height="10" width="100%" colspan="2"/></tr>
								<tr><td height="1" width="100%" class="linea" colspan="2"/></tr>
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="130">Nombre:</td>
									<td width="600"><div id="nombre" /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="110">Cédula:</td>
									<td width="600"><div id="cedula" /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="110">Login:</td>
									<td width="600"><div id="login" /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="110">Email:</td>
									<td width="600"><div id="email" /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="110">Telefono:</td>
									<td width="600"><div id="telefono" /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								<tr><td height="1" width="100%" class="linea" colspan="2"/></tr>
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width='110'>Tipo de Usuario:</td>
									<td width="600"><div id='tipo_usuario' /></td>
								</tr>
								
								<tr><td height="10" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td class='subtitulo' width="110">Cargo:</td>
									<td width="600"><div id="cargo" /></td>
								</tr>
								
								<tr><td height="15" width="100%" colspan="2"/></tr>
								
								<tr align="left">
									<td width='100%' valign="top" align="center" colspan="2"><input type="submit" value="Eliminar este usuario"/></td>
								</tr>
							</tbody></table>
						</div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
