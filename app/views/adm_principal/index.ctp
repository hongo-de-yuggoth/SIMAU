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
</div>

<div id="col_derecha">
	<div id="titulo_pagina">Página Administrador Principal</div>
	<div id="cuadro_notificaciones">Cuadro Notificaciones</div>
	<div id="contenido_vista">Contenido General de la vista</div>
</div>