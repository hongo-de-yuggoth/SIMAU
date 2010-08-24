<?php
echo $html->css('menu_navegacion');
echo $html->css('menu_adm_equipo');
echo $html->css('vista');

echo $javascript->link('adm_principal/adm_dependencias');
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
	<div id='subtitulo_pagina'>Administración de Dependencias</div>
	<div id="contenido_vista">
		<form id="edificio" name="edificio" action="#" method="post" >
			<div class="ajax_loading_image"></div>
			<!-- HIDDEN INPUTS -->
			<div id="escondidos"></div>
			<input type="hidden" value="<?php echo $opcion_seleccionada; ?>" id="opcion_seleccionada"/>
			
			<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
				<tr><td height="10" /></tr>
				
				<tr align='left'>
					<td >
						<table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
							<tr align="left">
								<td style='font-size:14px;' colspan='3'>Modificar Dependencias<td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Edificio:</td>
								<td width="150" colspan='2'><select id="id_edificio_modificar"></select></td>
							</tr>
							<tr><td height="10" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Dependencia:</td>
								<td width="150" colspan='2'><select id="id_dependencia_modificar"></select></td>
							</tr>
							<tr><td height="20" /></tr>
							<tr align="left">
								<td class='subtitulo' width='120'>Nuevo edificio:</td>
								<td width="150" colspan='2'><select id="id_nuevo_edificio_modificar"></select></td>
							</tr>
							<tr><td height="15" /></tr>
							<tr align="left">
								<td colspan="3" style="padding-left: 5px;" align="center"><input id='boton_modificar_dependencia' type="button" value="Modificar dependencia"></td>
							</tr>
							<tr align="left">
								<td></td>
								<td colspan="2" class="textoError"><div id="error_dependencia_modificar" style="display:none;"></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
		<?php echo $form->end(); ?>
	</div>
</div>
