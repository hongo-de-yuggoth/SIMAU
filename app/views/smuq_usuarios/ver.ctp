<?php
echo $html->css('menu_navegacion');
echo $html->css('vista');

echo $javascript->link('smuq_usuarios/ver');
?>

<div id="menu_navegacion">
	<div class="cuerpo_menu">
		<ul>
			<?php echo $opciones_menu; ?>
		</ul>
	</div>
</div>

<div id="col_derecha">
	<div id="titulo_pagina" align="center">Información de un usuario</div>
	<div id="cuadro_notificaciones"></div>
	
	<div id="contenido_vista">
		<form>
			<input id="opcion_seleccionada" type="hidden" value="<?php echo $opcion_seleccionada; ?>" />
		</form>
      <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
         <tr align='left'>
            <td colspan='4'>
               <div id='info_usuario'>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>
                     <tr><td height="10" colspan="2"/></tr>
                     <tr><td height="1" class="linea" colspan="2"/></tr>
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Nombre:</td>
                        <td width="210"><?php echo $usuario['Usuario']['nombre']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Cédula:</td>
                        <td width="210"><?php echo $usuario['Usuario']['cedula']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Login:</td>
                        <td width="210"><?php echo $usuario['Usuario']['login']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Email:</td>
                        <td width="210"><?php echo $usuario['Usuario']['email']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     <tr><td height="1" class="linea" colspan="2"/></tr>
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Tipo de Usuario:</td>
                        <td><?php echo $usuario['Grupo']['name']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Edificio:</td>
                        <td><?php echo $usuario['Edificio']['name']; ?></td>
                     </tr>
                     
                     <tr><td height="10" colspan="2"/></tr>
                     
                     <tr align="left">
                        <td class='subtitulo' width='50'>Dependencia:</td>
                        <td><?php echo $usuario['Dependencia']['name']; ?></td>
                     </tr>
                  
                     <tr><td height="10" colspan="2"/></tr>
            
                     <tr align="left">
                        <td class='subtitulo' width='50'>Cargo:</td>
                        <td><?php echo $usuario['Usuario']['cargo']; ?></td>
                     </tr>
                  </tbody></table>
               </div>
            </td>
         </tr>
      </tbody></table>
	</div>
</div>
