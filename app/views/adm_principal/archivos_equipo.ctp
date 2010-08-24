<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');
echo $html->css('tabs');
echo $javascript->link('jquery.idTabs.min');
echo $javascript->link('jquery.ajaxfileupload');
echo $javascript->link('jquery.selectboxes.min');
echo $javascript->link('adm_principal/archivos_equipo');
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
	<div id='subtitulo_pagina'>Documentos de un equipo</div>
	<div id="contenido_vista">
		<form id="equipo" name="equipo" action="#" method="post" enctype="multipart/form-data" >
			<!-- HIDDEN INPUTS -->
			<div id="escondidos">
            <input id="id_equipo" type="hidden" value='' />
            <input id="placa_inventario_equipo" type="hidden" value='' />
			</div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
         
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td class='subtitulo' width='120'>Placa de Inventario:</td>
					<td width="70"><input id="placa_inventario_buscar" maxlength="10" size='9' /></td>
					<td style="padding-left: 5px;"><input id='boton_buscar_equipo' type="button" value="Buscar equipo"></td>
				</tr>
				
				<tr align="left">
					<td width='120'></td>
					<td width="300" colspan="2" class="textoError"><div id="error_placa_buscar" style="display:none;"></td>
				</tr>
			</tbody></table>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align='left'>
					<td width="100%">
						<div id='cuadro_archivos' style='display:none;'>
                     <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                        <tr><td height="10" /></tr>
                        <tr><td height="1" class="linea" /></tr>
                        <tr><td height="30" /></tr>
                        <tr><td style='font-size:14px; font-weight:bold; text-align:center;'>Documentos del equipo <a id='link_ver_equipo' href='' target="_blank" alt='Ver la información del equipo'></a></td></tr>
                        <tr><td height="34" /></tr>
                        <tr>
                           <td>
                              <div id="tabs" class="usual">
                                 <ul class="idTabs">
                                    <li><a href="#fragment-1" class="selected"><span>Certificados</span></a></li>
                                    <li><a href="#fragment-2"><span>Garantías</span></a></li>
                                    <li><a href="#fragment-3"><span>Manuales</span></a></li>
                                    <li><a href="#fragment-4"><span>Facturas</span></a></li>
                                    <li><a href="#fragment-5"><span>Cotizaciones</span></a></li>
                                 </ul>
                                 <div id="fragment-1">
                                    <!-- CERTIFICADOS -->
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                       <tr align="left">
                                          <td>
                                             <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Asignar un Certificado</td></tr>
                                                <tr><td height="10" /></tr>
                                                <tr align="left">
                                                   <td class='subtitulo' width='120'>Nombre del archivo:</td>
                                                   <td width="150"><input id="nombre_archivo_certificado" name="certificado" type='file' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width="100%" colspan="2" align='center' style='padding-top:10px;'><input id="boton_certificado" type='button' value='Asignar certificado' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width='100%' colspan="2">
                                                      <div id='div_msj_cert_nuevo' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                                      <div id='reloj_arena_1a' align='center' style='display:none;'></div>
                                                   </td>
                                                </tr>
                                             </tbody></table>
                                          </td>
                                       </tr>
                                       <tr><td height="25" /></tr>
                                       <tr align="left">
                                          <td>
                                             <div id='cuadro_eliminar_certificado' style='display:none;'><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Certificados existentes</td></tr>
                                                <tr><td height="10" colspan='2' /></tr>
                                                <tr align="left"><td style='font-size:11px;' colspan='2'>Para acceder a un documento debes hacer doble click sobre el nombre del certificado.</td></tr>
                                                <tr><td height="5" colspan='2' /></tr>
                                                <tr align="left">
                                                   <td width='120'><select id="select_certificados" multiple="multiple" size='4'></select></td>
                                                   <td style='padding-left:10px;' valign='top'><input id="boton_eliminar_certificado" type='button' value='Eliminar certificado(s)' /></td>
                                                </tr>
                                             </tbody></table></div>
                                             <div id='reloj_arena_1b' align='center' style='display:none;'></div>
                                             <div id='div_msj_cert_eliminado' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                             <div id='msj_select' style='display:none; font-weight:bold; color:white;'>No hay ningún certificado asignado a este equipo.</div>
                                          </td>
                                       </tr>
                                    </tbody></table>
                                 </div>
                                 <div id="fragment-2">
                                    <!-- GARANTÍAS -->
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                       <tr align="left">
                                          <td>
                                             <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Asignar una Garantía</td></tr>
                                                <tr><td height="10" /></tr>
                                                <tr align="left">
                                                   <td class='subtitulo' width='120'>Nombre del archivo:</td>
                                                   <td width="150"><input id="nombre_archivo_garantia" name="garantia" type='file' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width="100%" colspan="2" align='center' style='padding-top:10px;'><input id="boton_garantia" type='button' value='Asignar garantía' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width='100%' colspan="2">
                                                      <div id='div_msj_gara_nuevo' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                                      <div id='reloj_arena_2a' align='center' style='display:none;'></div>
                                                   </td>
                                                </tr>
                                             </tbody></table>
                                          </td>
                                       </tr>
                                       <tr><td height="25" /></tr>
                                       <tr align="left">
                                          <td>
                                             <div id='cuadro_eliminar_garantia' style='display:none;'><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Garantías existentes</td></tr>
                                                <tr><td height="10" colspan='2' /></tr>
                                                <tr align="left"><td style='font-size:11px;' colspan='2'>Para acceder a un documento debes hacer doble click sobre el nombre de la garantía.</td></tr>
                                                <tr><td height="5" colspan='2' /></tr>
                                                <tr align="left">
                                                   <td width='120'><select id="select_garantias" multiple="multiple" size='4'></select></td>
                                                   <td style='padding-left:10px;' valign='top'><input id="boton_eliminar_garantia" type='button' value='Eliminar garantía(s)' /></td>
                                                </tr>
                                             </tbody></table></div>
                                             <div id='reloj_arena_2b' align='center' style='display:none;'></div>
                                             <div id='div_msj_gara_eliminado' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                             <div id='msj_select_gara' style='display:none; font-weight:bold; color:white;'>No hay ningúna garantía asignada a este equipo.</div>
                                          </td>
                                       </tr>
                                    </tbody></table>
                                 </div>
                                 <div id="fragment-3">
                                    <!-- MANUALES -->
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                       <tr align="left">
                                          <td>
                                             <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Asignar un Manual</td></tr>
                                                <tr><td height="10" /></tr>
                                                <tr align="left">
                                                   <td class='subtitulo' width='120'>Nombre del archivo:</td>
                                                   <td width="150"><input id="nombre_archivo_manual" name="manual" type='file' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width="100%" colspan="2" align='center' style='padding-top:10px;'><input id="boton_manual" type='button' value='Asignar manual' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width='100%' colspan="2">
                                                      <div id='div_msj_manu_nuevo' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                                      <div id='reloj_arena_3a' align='center' style='display:none;'></div>
                                                   </td>
                                                </tr>
                                             </tbody></table>
                                          </td>
                                       </tr>
                                       <tr><td height="25" /></tr>
                                       <tr align="left">
                                          <td>
                                             <div id='cuadro_eliminar_manual' style='display:none;'><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Manuales existentes</td></tr>
                                                <tr><td height="10" colspan='2' /></tr>
                                                <tr align="left"><td style='font-size:11px;' colspan='2'>Para acceder a un documento debes hacer doble click sobre el nombre del manual.</td></tr>
                                                <tr><td height="5" colspan='2' /></tr>
                                                <tr align="left">
                                                   <td width='120'><select id="select_manuales" multiple="multiple" size='4'></select></td>
                                                   <td style='padding-left:10px;' valign='top'><input id="boton_eliminar_manual" type='button' value='Eliminar manual(es)' /></td>
                                                </tr>
                                             </tbody></table></div>
                                             <div id='reloj_arena_3b' align='center' style='display:none;'></div>
                                             <div id='div_msj_manu_eliminado' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                             <div id='msj_select_manu' style='display:none; font-weight:bold; color:white;'>No hay ningún manual asignado a este equipo.</div>
                                          </td>
                                       </tr>
                                    </tbody></table>
                                 </div>
                                 <div id="fragment-4">
                                    <!-- FACTURAS -->
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                       <tr align="left">
                                          <td>
                                             <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Asignar una Factura</td></tr>
                                                <tr><td height="10" /></tr>
                                                <tr align="left">
                                                   <td class='subtitulo' width='120'>Nombre del archivo:</td>
                                                   <td width="150"><input id="nombre_archivo_factura" name="factura" type='file' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width="100%" colspan="2" align='center' style='padding-top:10px;'><input id="boton_factura" type='button' value='Asignar factura' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width='100%' colspan="2">
                                                      <div id='div_msj_fact_nuevo' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                                      <div id='reloj_arena_4a' align='center' style='display:none;'></div>
                                                   </td>
                                                </tr>
                                             </tbody></table>
                                          </td>
                                       </tr>
                                       <tr><td height="25" /></tr>
                                       <tr align="left">
                                          <td>
                                             <div id='cuadro_eliminar_factura' style='display:none;'><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Facturas existentes</td></tr>
                                                <tr><td height="10" colspan='2' /></tr>
                                                <tr align="left"><td style='font-size:11px;' colspan='2'>Para acceder a un documento debes hacer doble click sobre el nombre de la factura.</td></tr>
                                                <tr><td height="5" colspan='2' /></tr>
                                                <tr align="left">
                                                   <td width='120'><select id="select_facturas" multiple="multiple" size='4'></select></td>
                                                   <td style='padding-left:10px;' valign='top'><input id="boton_eliminar_factura" type='button' value='Eliminar factura(s)' /></td>
                                                </tr>
                                             </tbody></table></div>
                                             <div id='reloj_arena_4b' align='center' style='display:none;'></div>
                                             <div id='div_msj_fact_eliminado' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                             <div id='msj_select_fact' style='display:none; font-weight:bold; color:white;'>No hay ningúna factura asignada a este equipo.</div>
                                          </td>
                                       </tr>
                                    </tbody></table>
                                 </div>
                                 <div id="fragment-5">
                                    <!-- COTIZACIONES -->
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                       <tr align="left">
                                          <td>
                                             <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Asignar una Cotización</td></tr>
                                                <tr><td height="10" /></tr>
                                                <tr align="left">
                                                   <td class='subtitulo' width='120'>Nombre del archivo:</td>
                                                   <td width="150"><input id="nombre_archivo_cotizacion" name="cotizacion" type='file' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width="100%" colspan="2" align='center' style='padding-top:10px;'><input id="boton_cotizacion" type='button' value='Asignar cotizacion' /></td>
                                                </tr>
                                                <tr align="left">
                                                   <td width='100%' colspan="2">
                                                      <div id='div_msj_coti_nuevo' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                                      <div id='reloj_arena_5a' align='center' style='display:none;'></div>
                                                   </td>
                                                </tr>
                                             </tbody></table>
                                          </td>
                                       </tr>
                                       <tr><td height="25" /></tr>
                                       <tr align="left">
                                          <td>
                                             <div id='cuadro_eliminar_cotizacion' style='display:none;'><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                                                <tr align="left"><td style='font-size:14px;' colspan='2'>Cotizaciones existentes</td></tr>
                                                <tr><td height="10" colspan='2' /></tr>
                                                <tr align="left"><td style='font-size:11px;' colspan='2'>Para acceder a un documento debes hacer doble click sobre el nombre de la cotización.</td></tr>
                                                <tr><td height="5" colspan='2' /></tr>
                                                <tr align="left">
                                                   <td width='120'><select id="select_cotizaciones" multiple="multiple" size='4'></select></td>
                                                   <td style='padding-left:10px;' valign='top'><input id="boton_eliminar_cotizacion" type='button' value='Eliminar cotización(es)' /></td>
                                                </tr>
                                             </tbody></table></div>
                                             <div id='reloj_arena_5b' align='center' style='display:none;'></div>
                                             <div id='div_msj_coti_eliminado' align='center' style='display:none; color:green; vertical-align:middle;'></div>
                                             <div id='msj_select_coti' style='display:none; font-weight:bold; color:white;' >No hay ningúna cotización asignada a este equipo.</div>
                                          </td>
                                       </tr>
                                    </tbody></table>
                                 </div>
                              </div>
                           </td>
                        </tr>
                        
                     </tbody></table>
                  </div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
