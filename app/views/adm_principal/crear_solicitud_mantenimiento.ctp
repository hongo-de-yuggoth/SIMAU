<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');
echo $html->css('crear_solicitud');

echo $javascript->link('adm_principal/select_dep_usr.js');
echo $javascript->link('adm_principal/crear_solicitud_mantenimiento');
?>


<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Crear Solicitud de Mantenimiento</div>
	
	<div id="cuadro_notificaciones" class="<?php echo $clase_notificacion; ?>" style="display: <?php echo $display_notificacion; ?>;">
		<?php echo $mensaje_notificacion; ?>
	</div>
	
	<div id="contenido_vista">
		<form id="solicitud_mantenimiento" name="solicitud_mantenimiento" action="/solicitudes/crear" method="post" >
			<div class="ajax_loading_image"></div>
			<div id="escondidos">
				<input type='hidden' id='equipo_confirmado' name="data[Solicitud][placa_inventario]" value='' />
			</div>
			<input type="hidden" value="p" name="data[Solicitud][estado]" id="h_estado"/>
			<input type="hidden" value="" name="data[Solicitud][tipo_servicio]" id="h_tipo_servicio"/>
			<input type="hidden" value="0" name="data[Solicitud][cedula_adm_sol]" id="h_id_adm_sol"/>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr><td height="20" colspan="4"/></tr>
				<tr align="left">
					<td colspan='4'>
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr align="left">
								<td><?php echo $html->image('logouq.gif'); ?></td>
								<td colspan='3' align='right' style='font-size:13px; font-weight:bold;'>UNIVERSIDAD DEL QUINDIO<BR>SISTEMA INTEGRADO DE GESTIÓN</td>
							</tr>
							<tr align="right">
								<td></td>
								<td class='subtitulo'>Código: A.AC-07.00.02.F.01</td>
								<td class='subtitulo'>Versión: 1</td>
								<td class='subtitulo'>Fecha: 2009/11/30</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				
				<tr><td height="20" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Fecha:</td>
					<td colspan="3"><?php echo $fecha_hoy; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Edificio:</td>
					<td colspan="3"><select id="id_edificio"><?php echo $edificios_info; ?></select></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Dependencia:</td>
					<td colspan="3">
						<select id="id_dependencia" name="data[Solicitud][Cencos_id]"></select>
					</td>
				</tr>
			
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Solicitante:</td>
					<td colspan="3"><select id="id_usuario" name='data[Solicitud][cedula_usuario]'></select></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
	
				<tr align="left">
					<td class='subtitulo' width='90'>Cargo:</td>
					<td colspan="3"><div id='cargo' /></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				<tr><td height="1" class="linea" colspan="4"/></tr>
				<tr><td height="10" colspan="4"/></tr>
				<tr><td colspan="4" align="left">Debes ingresar la placa de inventario para buscar la información del equipo que requiere mantenimiento:</td></tr>
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='120'>Placa de Inventario:</td>
					<td width="180"><input id="placa_inventario" maxlength="10" size='9' /></td>
					<td colspan="2" style="padding-left: 5px;"><input id='boton_buscar_equipo' type="button" value="Buscar equipo"></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="*" colspan="3" class="textoError"><div id="error_placa" style="display:none;"></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td colspan='4'><table width="100%">
							<tr align="left">
								<td class='subtitulo' width="60">Placa de Inventario:</td>
								<td colspan="3" width="210" valign="bottom"><div id='placa_equ' /></td>
							</tr>
							<tr><td height="10" colspan="4"/></tr>
							<tr align="left">
								<td class='subtitulo' width='50'>Nombre:</td>
								<td colspan="3" width="210"><div id='nombre_equ' /></td>
							</tr>
							<tr><td height="10" colspan="4"/></tr>
							<tr align="left">
								<td class='subtitulo' width='50'>Modelo:</td>
								<td width="210"><div id='modelo_equ' /></td>
								<td class='subtitulo' width='45'>Marca:</td>
								<td><div id='marca_equ' /></td>
							</tr>
					</table></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				<tr><td height="1" class="linea" colspan="4"/></tr>
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90' valign="top">Tipo de Servicio:</td>
					<td width="180" valign="top">
						<input id="mp" type="checkbox" name="ts_mp" value="1" checked>
						<label for="mp">Mantenimiento Preventivo</label><br>
						<input id="mc" type="checkbox" name="ts_mc" value="2">
						<label for="mc">Mantenimiento Correctivo</label><br>
						<input id="cc" type="checkbox" name="ts_cc" value="3">
						<label for="cc">Calibración / Certificación</label>	
					</td>
					<td class="linea" width='1'></td>
					<td width='250' style="padding-left:10px;" valign="top">
						<fieldset>
							<legend><b>Definición del servicio</b></legend>
							<div id="definiciones">
								<div class="slides">
									<h4>Mantenimiento Preventivo</h4>
									<div class="content" style="display: block;">
										Actividad orientada a mantener en condiciones óptimas el funcionamiento del equipo.
									</div>
									
									<h4>Mantenimiento Correctivo</h4>
									<div class="content" style="display: none;">
										Actividad orientada a reponer el estado inicial del equipo.
									</div>
									
									<h4>Calibración</h4>
									<div class="content" style="display: none;">
										Ajustar los parámetros de un equipo a unos estándares permitidos.
									</div>
									
									<h4>Certificación</h4>
									<div class="content" style="display: none;">
										Procedimiento por el cual una empresa certifica la incertidumbre de una medida.
									</div>
								</div>
							</div>
						</fieldset>
					</td>
				</tr>
				<tr align="left">
					<td width='120'></td>
					<td width="*" colspan="3" class="textoError"><div id="error_tipo_servicio" style="display:none;"></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='50' valign="top">Descripción:</td>
					<td colspan="3" width="210"><textarea id="descripcion" name="data[Solicitud][descripcion]" cols="50"></textarea></td>
				</tr>
				<tr align="left">
					<td width='120'></td>
					<td width="*" colspan="3" class="textoError"><div id="error_descripcion" style="display:none;"></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='50' valign="top">Observaciones:</td>
					<td colspan="3" width="210"><textarea id="observaciones" name="data[Solicitud][observaciones]" cols="50"></textarea></td>
				</tr>
				
				<tr><td height="15" colspan="4"/></tr>
				
				<tr align="left">
					<td width='100%' valign="top" align="center" colspan="4"><input type="submit" value="Enviar Solicitud"/></td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
