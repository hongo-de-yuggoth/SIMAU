<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');

echo $javascript->link('smuq_usuarios/login');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
      <ul>
         <li id="inicio"><a href="/">Inicio</a></li>
         <li id="login"><a href="/login">Entrar al sistema</a></li>
         <li id="acerca_de"><a href="/solicitudes/acerca_de">Acerca de SISMLAB</a></li>
      </ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">SISMLAB<br>Sistema de Información para el Soporte y Mantenimiento de Activos Fijos</div>
	<div id="cuadro_notificaciones" class="<?php echo $clase_notificacion; ?>" style="display: <?php echo $display_notificacion; ?>;">
		<?php echo $mensaje_notificacion; ?>
	</div>
	<div id="contenido_vista">
		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
			<tr align="left"><td class='subtitulo'></td></tr>
			<tr align="left">
				<td>
					<div id="login_box">
						<table width="100%">
							<tr>
								<td height="300" align="center">
									<form id="login_usuario" name="login_usuario" method="post" action="/smuq_usuarios/login">
										<input type="hidden" id="opcion_seleccionada" value="<?php echo $opcion_seleccionada; ?>" />
										<table>
											<tr>
												<td class="subtitulo">Login:</td>
												<td><input name="data[SmuqUsuario][login]" type="text" value="" id="login_usr" /></td>
											</tr>
											<tr align="left">
												<td></td>
												<td class="textoError"><div id="error_login" style="display:none;" /></td>
											</tr>
											<tr>
												<td class="subtitulo">Clave:</td>
												<td><input type="password" name="data[SmuqUsuario][clave]" value="" id="clave" /></td>
											</tr>
											<tr align="left">
												<td></td>
												<td class="textoError"><div id="error_clave" style="display:none;" /></td>
											</tr>
											<tr>
												<td colspan="2" align="center"><input type="button" id="boton_entrar" value="Entrar al Sistema" /></td>
											</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</tbody></table>
	</div>
</div>
