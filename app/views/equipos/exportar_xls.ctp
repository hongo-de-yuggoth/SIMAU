<table>
	<tr>
		<td colspan='5'><b>REPORTE DE EQUIPOS<b></td>
	</tr>
	<tr>
		<td><b>Fecha:</b></td>
		<td colspan='4'><?php echo date("F j, Y, g:i a"); ?></td>
	</tr>
	<tr>
		<td style="text-align:left"><b>Total de Registros:</b></td>
		<td align="left" colspan='4'><?php echo $total_registros; ?></td>
	</tr>
	<tr>
		<td colspan='5'></td>
	</tr>
	<tr id="titles">
		<td><b>Placa de inventario</b></td>
		<td><b>Nombre</b></td>
		<td><b>Marca</b></td>
		<td><b>Modelo</b></td>
		<td><b>Usuario</b></td>
		<td><b>Dependencia</b></td>
		<td><b>Valor de Compra</b></td>
		<td><b>Fecha de Compra</b></td>
	</tr>
	<?php
		echo $filas_tabla;
	?>
</table>
