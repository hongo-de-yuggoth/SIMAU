<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');

echo $javascript->link('validaciones');
echo $javascript->link('solicitudes/ver');
?>

<style type="text/css">
<!--
	.div_solucion{
		border: solid;
		border-width: 1px;
		padding: 5px;
	}
-->
</style>


<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Consulta de Solicitudes</div>
	<div id="subtitulo_pagina" align="center">Solicitud de Mantenimiento #<?php echo $solicitud['Solicitud']['id']; ?></div>
	<div id="cuadro_notificaciones" class="<?php echo $clase_notificacion; ?>" style="display: <?php echo $display_notificacion; ?>;">
		<?php echo $mensaje_notificacion; ?>
	</div>
	<div id="contenido_vista" style='margin-top:15px;'>
		<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
			<form id="solucion" name="solucion" action="#" method="post" enctype="multipart/form-data" >
				<input id="opcion_seleccionada" type="hidden" value="<?php echo $opcion_seleccionada; ?>" />
				<input id='nombre_contratista' type='hidden' value='<?php echo $solicitud['Solicitud']['contratista']; ?>' />
				<input id='repuestos' type='hidden' value='<?php echo $solicitud['Solicitud']['repuestos_mano_obra']; ?>' />
				<input id='cst_externo' type='hidden' value='<?php echo $solicitud['Solicitud']['costo_externo']; ?>' />
				<input id='cst_interno' type='hidden' value='<?php echo $solicitud['Solicitud']['costo_interno']; ?>' />
				<input id='observaciones' type='hidden' value='<?php echo $solicitud['Solicitud']['observaciones_solucion']; ?>' />
				<input id='id_solicitud' name='data[Solicitud][id]' type='hidden' value='<?php echo $solicitud['Solicitud']['id']; ?>' />
				<input id='cedula_usr_autenticado' type='hidden' value='<?php echo $usuario['cedula']; ?>' />
				<input id='id_grupo_usuario' type='hidden' value='<?php echo $usuario['id_grupo']; ?>' />
				<input id='nombre_adm_sol' type='hidden' value='<?php echo $adm_sol['SmuqUsuario']['nombre']; ?>' />
				
				<tr align="left">
					<td colspan="4" width="100%" align="right"><div id='link_pdf' style='display:block;'><a href='/solicitudes/exportar_pdf/<?php echo $solicitud['Solicitud']['id']; ?>'><?php echo $html->image('pdf.gif', array('border'=>0, 'alt'=>'Exportar a PDF', 'title'=>'Exportar a PDF')); ?></a></div></td>
				</tr>
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
					<td class='subtitulo' width='90'>Estado:</td>
					<td colspan="3"><div id='estado' style='display:none;'><?php echo $solicitud['Solicitud']['estado']; ?></div></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Fecha:</td>
					<td colspan="3"><?php echo $solicitud['Solicitud']['fecha']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Edificio:</td>
					<td colspan="3"><?php echo $solicitud['Edificio']['nombre']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Dependencia:</td>
					<td colspan="3"><?php echo $solicitud['CentroCosto']['Cencos_nombre']; ?></td>
				</tr>
			
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90'>Solicitante:</td>
					<td colspan="3"><?php echo $solicitud['Usuario']['Usu_nombre']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
	
				<tr align="left">
					<td class='subtitulo' width='90'>Cargo:</td>
					<td colspan="3"><?php echo $solicitante['SmuqUsuario']['cargo']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
	
				<tr align="left">
					<td class='subtitulo' width='90'>Email:</td>
					<td colspan="3"><?php echo $solicitante['SmuqUsuario']['email']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
	
				<tr align="left">
					<td class='subtitulo' width='90'>Teléfono:</td>
					<td colspan="3"><?php echo $solicitante['SmuqUsuario']['telefono']; ?></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				<tr><td height="1" class="linea" colspan="4"/></tr>
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td colspan='4'><table width="100%">
							<tr align="left">
								<td class='subtitulo' width="120">Placa de Inventario:</td>
								<td colspan="3" width="210" valign="bottom"><?php echo $solicitud['Solicitud']['placa_inventario']; ?></td>
							</tr>
							<tr><td height="10" colspan="4"/></tr>
							<tr align="left">
								<td class='subtitulo' width='50'>Nombre del equipo:</td>
								<td colspan="3" width="210"><?php echo $equipo['Producto']['prousu_pro_nombre']; ?></td>
							</tr>
							<tr><td height="10" colspan="4"/></tr>
							<tr align="left">
								<td class='subtitulo' width='50'>Modelo:</td>
								<td width="210"><?php echo $equipo['Producto']['prousu_modelo']; ?></td>
								<td class='subtitulo' width='45'>Marca:</td>
								<td><?php echo $equipo['Producto']['prousu_marca']; ?></td>
							</tr>
					</table></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				<tr><td height="1" class="linea" colspan="4"/></tr>
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='90' valign="top">Tipo de Servicio:</td>
					<td colspan='3' width="180" valign="top"><ul><?php echo $solicitud['Solicitud']['tipo_servicio']; ?></ul></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='50' valign="top">Descripción:</td>
					<td colspan="3" width="210"><div class='div_solucion'><?php echo $solicitud['Solicitud']['descripcion']; ?></div></td>
				</tr>
				
				<tr><td height="10" colspan="4"/></tr>
				
				<tr align="left">
					<td class='subtitulo' width='50' valign="top">Observaciones:</td>
					<td colspan="3" width="210"><div class='div_solucion'><?php echo $solicitud['Solicitud']['observaciones']; ?></div></td>
				</tr>
				
				<!-- SOLUCION A LA SOLICITUD -->
				<tr><td colspan="4"><div id='div_solucion' style='display:none;'>
					<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
						<tr><td height="10" colspan="4"/></tr>
						<tr><td height="1" class="linea" colspan="4"/></tr>
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td colspan='4'><div id="subtitulo_pagina" align="center">Solución a la Solicitud</div></td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr><td colspan="4"><div id='div_solucionado_por' style='display:none;'>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr align="left">
									<td class='subtitulo' width='90' valign="top">Atendida por:</td>
									<td colspan="3" width="210"><div id='div_nombre_adm_sol'></div></td>
								</tr>
							</tbody></table>
						</div></td></tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td class='subtitulo' width='50' valign="top">Contratista:</td>
							<td colspan="3" width="210">
								<div id='div_contratista'></div>
								<div id='div_contratista_input' style='display:none;'><input id="contratista" size="30" maxlength="60" /></div>
							</td>
						</tr>
						<tr align="left">
							<td width="50"/>
							<td width="210" class="textoError" colspan="3"><div style="display: none;" id="error_contratista" /></td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td class='subtitulo' width='50' valign="top">Repuestos / Mano de Obra:</td>
							<td colspan="3" width="210">
								<div id='div_repuestos_mano_obra'></div>
								<div id='div_repuestos_mano_obra_textarea' style='display:none;'><textarea id="repuestos_mano_obra" cols="50"></textarea></div>
							</td>
						</tr>
						<tr align="left">
							<td width="50"/>
							<td width="210" class="textoError" colspan="3"><div style="display: none;" id="error_repuestos" /></td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td class='subtitulo' width='50' valign="middle">Costo externo:</td>
							<td colspan="3" width="210">
								<div id='div_costo_externo'></div>
								<div id='div_costo_externo_input' style='display:none;'>$<input id="costo_externo" size="15" maxlength="10" /></div>
							</td>
						</tr>
						<tr align="left">
							<td width="50"/>
							<td width="210" class="textoError" colspan="3"><div style="display: none;" id="error_costo_externo" /></td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td class='subtitulo' width='50' valign="middle">Costo interno:</td>
							<td colspan="3" width="210">
								<div id='div_costo_interno'></div>
								<div id='div_costo_interno_input' style='display:none;'>$<input id="costo_interno" size="15" maxlength="10" /></div>
							</td>
						</tr>
						<tr align="left">
							<td width="50"/>
							<td width="210" class="textoError" colspan="3"><div style="display: none;" id="error_costo_interno" /></td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td class='subtitulo' width='50' valign="top">Observaciones:</td>
							<td colspan="3" width="210">
								<div id='div_observaciones_solucion'></div>
								<div id='div_observaciones_solucion_textarea' style='display:none;'><textarea id="observaciones_solucion" cols="50"></textarea></div>
							</td>
						</tr>
						
						<tr><td height="10" colspan="4"/></tr>
						
						<tr align="left">
							<td colspan="4"><div id='botones_solucion' align="center" style='display:none;'><input id='boton_guardar' type="button" value="Guardar cambios"/> &nbsp;&nbsp;&nbsp;&nbsp;<input id='boton_solucionar' type="button" value="Dar por solucionado"/></div></td>
						</tr>
					</tbody></table>
				</div></td></tr>
			</form>
		</tbody></table>
	</div>
</div>
