<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');

echo $javascript->link('adm_principal/adm_edificios');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Administración de Edificios y Dependencias</div>
	
	<div id='barra_menu_equipo'>
		<ul>
			<li><a href='/adm_principal/adm_edificios'>Administrar Edificios</a></li>
			<li><a href='/adm_principal/adm_dependencias'>Administrar Dependencias</a></li>
		</ul>
	</div>
	<div id='subtitulo_pagina'>Administración de Edificios</div>
	<div id="contenido_vista">
		<form id="edificio" name="edificio" action="#" method="post" >
			<!-- HIDDEN INPUTS -->
			<div id="escondidos"></div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr><td height="1" class="linea" /></tr>
				<tr><td height="10" /></tr>
				
				<tr align="left">
					<td>
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr align="left">
								<td style='font-size:14px;' colspan='3'>Crear Edificios<td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Nombre:</td>
								<td width="150"><input id="nombre_edificio_crear" maxlength="70" size='30' /></td>
								<td style="padding-left: 5px;"><input id='boton_crear_edificio' type="button" value="Crear edificio"></td>
							</tr>
							<tr align="left">
								<td></td>
								<td colspan="2" class="textoError"><div id="error_edificio_crear" style="display:none;"></td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr><td height="10" /></tr>
				<tr><td height="1" class="linea" /></tr>
				<tr><td height="10" /></tr>
				
				<tr align='left'>
					<td >
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr align="left">
								<td style='font-size:14px;' colspan='3'>Modificar Edificios<td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Edificio:</td>
								<td width="150" colspan='2'><select id="id_edificio_modificar"></select></td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo'>Nuevo nombre:</td>
								<td width='150'><input id="nombre_edificio_modificar" maxlength="70" size='30' /></td>
								<td style="padding-left: 5px;"><input id='boton_modificar_edificio' type="button" value="Modificar edificio"></td>
							</tr>
							<tr align="left">
								<td></td>
								<td colspan="2" class="textoError"><div id="error_edificio_modificar" style="display:none;"></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				
				<tr><td height="10" /></tr>
				<tr><td height="1" class="linea" /></tr>
				<tr><td height="10" /></tr>
				
				<tr align='left'>
					<td >
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr align="left">
								<td style='font-size:14px;' colspan='3'>Eliminar Edificios<td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Edificio:</td>
								<td width="150"><select id="id_edificio_eliminar"></select></td>
								<td style="padding-left: 5px;"><input id='boton_eliminar_edificio' type="button" value="Eliminar edificio"></td>
							</tr>
							
							<tr align="left">
								<td></td>
								<td colspan="2" class="textoError"><div id="error_edificio_eliminar" style="display:none;"></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
