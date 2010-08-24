<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		UNIVERSIDAD DEL QUINDIO. Sistema de Informaci&oacute;n de Soporte y Mantenimiento Activos Fijos
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->css('estilos');
		echo $javascript->link('jquery-1.3.2.min');
		
		echo $scripts_for_layout;
	?>
	<script type="text/javascript">
		jQuery(document).ready(function()
		{
			jQuery('[name*=hovermenu]').hover
			(
				function ()
				{
					jQuery(this).removeClass('celdano').addClass('celdasi');
				},
				function ()
				{
					jQuery(this).removeClass('celdasi').addClass('celdano');
				}
			)
		})
	</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
	<div id="header"><?php echo $html->image('barra_superior4.gif', array('border' => '0')); ?></div>
	<div id="contenedor">
		<div id="contenido">
			<div id="contexto_usuario" align="left" style="display:<?php echo $display_contexto; ?>; height:30px;">
				<table width="100%">
					<tr>
						<td width="*" align="left" class="contexto"><?php echo $contexto; ?></td>
						<td width="100"><div><a href="/logout"><div align="center" class="contexto_link">Cerrar Sesión</div></a></div></td>
					</tr>
				</table>
			</div>
			<?php echo $content_for_layout; ?>
		</div>
	</div>
	<div id="footer">
		<table width="100%">
			<tr>
				<td class="pie">Universidad del Quind&iacute;o<br>Carrera 15 Calle 12 Norte<br>Armenia - Quind&iacute;o - Colombia</td>
				<td class="pie">Tel.: +57 (6) 7460100<br>Quejas y Reclamos: 018000 96 35 78</td>
				<td class="pie">Copyright (c) 2010 Universidad del Quind&iacute;o<br>Todos los derechos reservados.<br>Comentarios: wbmaster@uniquindio.edu.co</td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>
