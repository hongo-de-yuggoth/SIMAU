<?php ?>
<table border="0" width="100%" cellspacing="0" cellpadding="2"><tbody>
	<tr align="left">
		<td colspan="2" colspan="3">Estimado usuario, se le informa que la solicitud de servicio #<?php echo $solicitud['Solicitud']['id']; ?>
		ya ha sido realizada. A continuación se adjunta el formato de la solicitud del servicio.</td>
	</tr>
	
	<tr><td height="30" colspan="3"></td></tr>
	
	<tr align="left">
		<td colspan="2"><?php echo $encabezado_pdf; ?></td>
	</tr>
	
	<tr><td height="30" colspan="3"></td></tr>
	
	<tr align="left">
		<td colspan="2" width="*"><div>
			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left">
					<td width="85"><b>Estado:</b></td>
					<td width="*" colspan="3"><?php echo $solicitud['Solicitud']['estado']; ?></td>
				</tr>
				<tr align="left">
					<td width="85"><b>Fecha:</b></td>
					<td width="*" colspan="3"><?php echo $solicitud['Solicitud']['fecha']; ?></td>
				</tr>
				<tr align="left">
					<td width="85"><b>Edificio:</b></td>
					<td width="*" colspan="3"><?php echo $solicitud['Edificio']['nombre']; ?></td>
				</tr>
				<tr align="left">
					<td width="85"><b>Dependencia:</b></td>
					<td width="*" colspan="3"><?php echo $solicitud['CentroCosto']['Cencos_nombre']; ?></td>
				</tr>
				<tr align="left">
					<td width="85"><b>Solicitante:</b></td>
					<td width="*" colspan="3"><?php echo $solicitud['Usuario']['Usu_nombre']; ?></td>
				</tr>
				<tr align="left">
					<td width="85"><b>Cargo:</b></td>
					<td width="*" colspan="3"><?php echo $cargo_solicitante; ?></td>
				</tr>
			</tbody></table>
		</div></td>
	</tr>
			
	<tr><td height="30" colspan="4"></td></tr>
	
	<tr align="left">
		<td colspan="2" width="*"><div>
			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left">
					<td width="160"><b>Placa de Inventario:</b></td>
					<td colspan="3" width="*" valign="bottom"><?php echo $solicitud['Solicitud']['placa_inventario']; ?></td>
				</tr>
				<tr align="left">
					<td width="110"><b>Nombre del equipo:</b></td>
					<td colspan="3" width="*"><?php echo $equipo['Producto']['prousu_pro_nombre']; ?></td>
				</tr>
				<tr align="left">
					<td width="110"><b>Modelo:</b></td>
					<td width="190"><?php echo $equipo['Producto']['prousu_modelo']; ?></td>
					<td width="50"><b>Marca:</b></td>
					<td width="*"><?php echo $equipo['Producto']['prousu_marca']; ?></td>
				</tr>
				<tr align="left">
					<td valign="top" width="110"><b>Tipo de Servicio:</b></td>
					<td colspan="3" width="*" valign="top"><?php echo $solicitud['Solicitud']['tipo_servicio']; ?></td>
				</tr>
				<tr align="left">
					<td valign="top" width="110"><b>Descripción:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['descripcion']; ?></td>
				</tr>
				<tr align="left">
					<td valign="top" width="110"><b>Observaciones:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['observaciones']; ?></td>
				</tr>
			</tbody></table>
		</div></td>
	</tr>
	
	<tr><td height="30" colspan="4"></td></tr>
	
	<tr align="left">
		<td colspan="2" width="*"><div>
			<table width="100%" cellspacing="0" cellpadding="5" border="1"><tbody>
				<tr align="left"><td width="*" align="center" colspan="4"><b>Solución a la Solicitud</b></td></tr>
				<tr align="left">
					<td width="95" valign="top"><b>Atendida por:</b></td>
					<td colspan="3" width="*"><?php echo $adm_sol['SmuqUsuario']['nombre']; ?></td>
				</tr>
				<tr align="left">
					<td width="95" valign="top"><b>Contratista:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['contratista']; ?></td>
				</tr>
				<tr align="left">
					<td width="95" valign="top"><b>Repuestos / Mano de Obra:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['repuestos_mano_obra']; ?></td>
				</tr>
				<tr align="left">
					<td width="95" valign="middle"><b>Costo externo:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['costo_externo']; ?></td>
				</tr>
				<tr align="left">
					<td width="95" valign="middle"><b>Costo interno:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['costo_interno']; ?></td>
				</tr>
				<tr align="left">
					<td width="95" valign="top"><b>Observaciones:</b></td>
					<td colspan="3" width="*"><?php echo $solicitud['Solicitud']['observaciones_solucion']; ?></td>
				</tr>
			</tbody></table>
		</div></td>
	</tr>
</tbody></table>