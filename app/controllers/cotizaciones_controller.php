<?php
class CotizacionesController extends AppController
{
   //--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
   
	//--------------------------------------------------------------------------
   
   function asignar_cotizacion($id_equipo, $placa_inventario)
	{
      App::import('Vendor', 'upload', array('file' => 'class.upload.php'));
		$this->autoLayout = false;
		$this->autoRender = false;
      $datos_json = array
      (
         'resultado' => false,
         'id' => '',
         'nombre_archivo' => ''
      );
		
      if ( !empty($_FILES) && !empty($id_equipo) && !empty($placa_inventario) )
		{
         if ( !empty($_FILES['cotizacion']['name']) )
         {
            $handle = new Upload($_FILES['cotizacion']);
      
            if ( $handle->uploaded )
            {
               $handle->file_overwrite = true;
               $handle->file_safe_name = false;
               $handle->file_auto_rename = false;
               $handle->file_new_name_body = 'cotizacion('.$this->Cotizacion->getNextAutoIncrement().')_'.$placa_inventario;
               $handle->Process('equipos/cotizaciones');
   
               if ( $handle->processed )
               {
                  $this->data['Cotizacion']['nombre_archivo'] = $handle->file_dst_name;
                  $this->data['Cotizacion']['id_equipo'] = $id_equipo;
                  $this->data['Cotizacion']['placa_inventario'] = $placa_inventario;
            
                  if ( $this->Cotizacion->save($this->data) )
                  {
                     $datos_json['resultado'] = true;
                     $datos_json['id'] = $this->Cotizacion->id;
                     $datos_json['nombre_archivo'] = $this->data['Cotizacion']['nombre_archivo'];
                  }
               }
               $handle->Clean();
            }
         }
		}
      
      return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
   
   function eliminar_cotizacion($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$cotizacion = $this->Cotizacion->findById($id);
		if ( !empty($cotizacion) )
		{
			if ( !empty($cotizacion['Cotizacion']['nombre_archivo']) )
			{
				if ( unlink(WWW_ROOT.'equipos/cotizacion/'.$garantia['Cotizacion']['nombre_archivo']) )
				{
               // Borramos el registro de la BD.
					if ( $this->Cotizacion->delete($cotizacion['Cotizacion']['id']) )
					{
						return 'true';
					}
				}
			}
		}
		return 'false';
	}
	
	//--------------------------------------------------------------------------
}
?>
