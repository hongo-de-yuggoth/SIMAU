<table>
	<tr>
		<td colspan='5'><b>REPORTE DE USUARIOS<b></td>
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
		<td><b>Nombre</b></td>
		<td><b>C&eacute;dula</b></td>
		<td><b>Login</b></td>
		<td><b>Correo Electr&oacute;nico</b></td>
		<td><b>Tipo de Usuario</b></td>	
		<td><b>Dependencia</b></td>
		<td><b>Cargo</b></td>
	</tr>
	<?php
		echo $filas_tabla;
	?>
</table>
