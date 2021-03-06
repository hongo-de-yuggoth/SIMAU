<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');
echo $html->css('tabla_blue/style');

echo $javascript->link('jquery.tablesorter.min');
echo $javascript->link('validaciones');
echo $javascript->link('adm_principal/consultar_usuarios');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Consulta de Usuarios</div>

	<div id='subtitulo_pagina'></div>
	<div id="contenido_vista">
		<form id="solicitudes" name="solicitudes" action="#" method="post" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos"></div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>

			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr align="left">
					<td colspan="2">
						<!-- CRITERIOS -->
						<div>
							<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
								<tr><td colspan="2">Puedes realizar búsquedas de una palabra o frase en todos los campos:</td></tr>
								<tr><td height="10" /></tr>
								<tr align="left">
									<td class='subtitulo' width='120'>Buscar:</td>
									<td><input id='busqueda' name='busqueda' type='text' size="50" maxlength="50" /></td>
								</tr>
								<tr align="left">
									<td></td>
									<td class="textoError" valign="top"><div id="error_cedula" style="display:none; height:25px;" /></td>
								</tr>
								<tr align="left">
									<td class='subtitulo'>En el campo:</td>
									<td>
										<select id="select_campo">
											<option value="todos">Todos los campos</option>
											<option value="cedula">cédula del funcionario</option>
											<option value="nombre">nombre</option>
											<option value="login">login</option>

										</select>
									</td>
								</tr>
								<tr align="left">
									<td class='subtitulo'>Tipo de usuario:</td>
									<td>
										<select id="select_tipo_usuario">
											<?php echo $opciones_tipo_usuario; ?>
										</select>
									</td>
								</tr>
								<tr align="left">
									<td class='subtitulo'>De la dependencia:</td>
									<td>
										<select id="select_dependencia" disabled='disabled'>
											<?php echo $opciones_dependencias; ?>
										</select>
									</td>
								</tr>
							</tbody></table>
						</div>
						<div id='div_boton_buscar' align='center' style='padding-top:25px'>
							<input id='boton_buscar_usuarios' type='button' value='Buscar usuarios' />
						</div>
					</td>
				</tr>

				<tr align="left">
					<td width="100%" class="textoError" colspan="2">
						<div id="error_consulta" style="display:none;"></div>
						<div id='reloj_arena' align='center' style='display:none;'></div>
					</td>
				</tr>

				<tr><td height="10" colspan="2" /></tr>
				<tr align="left" ><td class='linea' height='1' colspan="2" ></td></tr>
				<tr><td height="10" colspan="2" /></tr>

				<tr align="left">
					<td width="50%"><div id="total_registros" style="display:none;" /></td>
					<td width="50%" align="right"><div id="archivo_xls" style="display:none;"><a href='#'><?php echo $html->image('excel.gif', array('border'=>0, 'alt'=>'Exportar a Excel', 'title'=>'Exportar a Excel')); ?></a></div></td>
				</tr>

				<tr align="left">
					<td colspan="2">
						<div id='resultados' style='display:none; overflow:auto; width:580px; height: 400px;'>
							<table id='tabla_resultados' class='tablesorter' style='overflow:auto; width:780px;'>
							</table>
						</div>
						<div id='info_usuario' style='display:none;'></div>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
