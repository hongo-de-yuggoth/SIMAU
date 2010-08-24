<?php
$utf8_cad = utf8_decode('<td><b>Número de Solicitud</b></td>
		<td><b>Dependencia</b></td>
		<td><b>Cédula solicitante</b></td>
		<td><b>Solicitante</b></td>
		<td><b>Servicio</b></td>
		<td><b>Placa de inventario</b></td>
		<td><b>Fecha solicitud</b></td>
		<td><b>Descripción</b></td>
		<td><b>Observaciones</b></td>
		<td><b>Fecha solución</b></td>
		<td><b>Repuestos / Mano de obra</b></td>
		<td><b>Observaciones de la solución</b></td>
		<td><b>Contratista</b></td>
		<td><b>Costo interno</b></td>
		<td><b>Costo externo</b></td>
		<td><b>Estado</b></td>');
?>
<table>
	<tr>
		<td colspan='6'><b>REPORTE DE SOLICITUDES<b></td>
	</tr>
	<tr>
		<td><b>Fecha:</b></td>
		<td colspan='5'><?php echo date("F j, Y, g:i a"); ?></td>
	</tr>
	<tr>
		<td style="text-align:left"><b>Total de Registros:</b></td>
		<td align="left" colspan='5'><?php echo $total_registros; ?></td>
	</tr>
	<tr>
		<td colspan='6'></td>
	</tr>
	<tr id="titles"><?php echo $utf8_cad; ?></tr>
	<?php echo $filas_tabla; ?>
</table>
