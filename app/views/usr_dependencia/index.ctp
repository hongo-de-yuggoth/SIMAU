<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
	<div id='cuadro_notificaciones_mantenimientos' class='tooltips-green' style="display:<?php echo $display_notificaciones; ?>;">
		<span style='font-weight:bold; font-size:14px;'>Notificaciones</span>
		<div id='msj_notificaciones'><a id='link_cuadro_notificaciones' href='/usr_dependencia/ver_notificaciones'><?php echo $msj_notificaciones; ?></a></div>
		<div id='punta_globo'></div>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina">Página Administrador Principal</div>
	<div id="cuadro_notificaciones">Cuadro Notificaciones</div>
	<div id="contenido_vista">Contenido General de la vista</div>
</div>
