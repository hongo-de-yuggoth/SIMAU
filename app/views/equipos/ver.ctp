<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');

echo $javascript->link('equipos/ver');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Consulta de Equipos</div>
	<div id="subtitulo_pagina" align="center">Informacion del Equipo con placa de inventario #<?php echo $producto['Producto']['prousu_placa']; ?></div>
	<div id="contenido_vista">
      <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
         <tr align="left">
            <td colspan="4" width="100%" align="right"><div id='link_pdf' style='display:block;'><a href='/equipos/exportar_pdf/<?php echo $producto['Producto']['prousu_placa']; ?>'><?php echo $html->image('pdf.gif', array('border'=>0, 'alt'=>'Exportar a PDF', 'title'=>'Exportar a PDF')); ?></a></div></td>
         </tr>
         
         <tr><td height="20" colspan="4"><form><input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/></form></td></tr>
         <tr align='left'>
            <td colspan='4'>
               <div id='info_equipo'>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                     <tr align="left">
                        <td class='subtitulo' width='90'>Nombre:</td>
                        <td colspan="3" width="210"><?php echo $producto['Producto']['prousu_pro_nombre']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Modelo:</td>
                        <td width="150"><?php echo $producto['Producto']['prousu_modelo']; ?></td>
                        <td class='subtitulo' width='15'>Marca:</td>
                        <td width='70'><?php echo $producto['Producto']['prousu_marca']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Placa de Inventario:</td>
                        <td colspan="3" width="210"><?php echo $producto['Producto']['prousu_placa']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     <tr><td height="1" class="linea" colspan="4"/></tr>
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Edificio:</td>
                        <td colspan="3"><?php echo $edificio['Edificio']['name']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Dependencia:</td>
                        <td colspan="3"><?php echo $centro_costo['CentroCosto']['Cencos_nombre']; ?></td>
                     </tr>
                  
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Responsable:</td>
                        <td colspan="3"><?php echo $producto['Usuario']['Usu_nombre']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
            
                     <tr align="left">
                        <td class='subtitulo' width='90'>Cargo:</td>
                        <td colspan="3"><?php echo $smuq_usuario['SmuqUsuario']['cargo']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     <tr><td height="1" class="linea" colspan="4"/></tr>
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Valor de Compra:</td>
                        <td colspan="3">$<span id="valor_compra" /><?php echo $producto['Producto']['prousu_valor']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='90'>Fecha de Compra:</td>
                        <td colspan="3"><?php echo $producto['Producto']['prousu_fecha_compra']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
            
                     <tr align="left">
                        <td class='subtitulo' width='90'>Fecha de Recibido a satisfacción:</td>
                        <td colspan="3"><?php echo $equipo['Equipo']['fecha_recibido_satisfaccion']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="4"/></tr>
                     <tr><td height="1" class="linea" colspan="4"/></tr>
                     <tr><td height="10" colspan="4"/></tr>
							
							<tr align="left">
                        <td colspan="4">
									<fieldset>
										<legend class="subtitulo">Documentos</legend>
										<table width="100%">
											<tr align="left">
												<td class='subtitulo' width='50' valign="top">Certificados:</td>
												<td colspan="3" width="210"><div id='lista_certificados'><?php echo $lista_archivos['certificados']; ?></div></td>
											</tr>
											<tr align="left">
												<td class='subtitulo' width='50' valign="top">Garantías:</td>
												<td colspan="3" width="210"><div id='lista_garantias'><?php echo $lista_archivos['garantias']; ?></div></td>
											</tr>
											<tr align="left">
												<td class='subtitulo' width='50' valign="top">Manuales:</td>
												<td colspan="3" width="210"><div id='lista_manuales'><?php echo $lista_archivos['manuales']; ?></div></td>
											</tr>
											<tr align="left">
												<td class='subtitulo' width='50' valign="top">Facturas:</td>
												<td colspan="3" width="210"><div id='lista_facturas'><?php echo $lista_archivos['facturas']; ?></div></td>
											</tr>
											<tr align="left">
												<td class='subtitulo' width='50' valign="top">Cotizaciones:</td>
												<td colspan="3" width="210"><div id='lista_cotizaciones'><?php echo $lista_archivos['cotizaciones']; ?></div></td>
											</tr>
										</table>
									</fieldset>
								</td>
                     </tr>
							
							<tr><td height="10" colspan="4"/></tr>
                     <tr><td height="1" class="linea" colspan="4"/></tr>
                     <tr><td height="10" colspan="4"/></tr>
                     
                     <tr align="left">
                        <td  valign="top" class='subtitulo' width='90'>Foto:</td>
                        <td colspan="3"><?php echo $equipo['Equipo']['nombre_foto']; ?></td>
                     </tr>
                     
                     <tr><td height="15" colspan="4"/></tr>
                  </tbody></table>
               </div>
            </td>
         </tr>
      </tbody></table>
	</div>
</div>
