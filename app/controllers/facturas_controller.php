<?php
class FacturasController extends AppController
{
   //--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
   
	//--------------------------------------------------------------------------
   
   function asignar_factura($id_equipo, $placa_inventario)
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
         if ( !empty($_FILES['factura']['name']) )
         {
            $handle = new Upload($_FILES['factura']);
      
            if ( $handle->uploaded )
            {
               $handle->file_overwrite = true;
               $handle->file_safe_name = false;
               $handle->file_auto_rename = false;
               $handle->file_new_name_body = 'factura('.$this->Factura->getNextAutoIncrement().')_'.$placa_inventario;
               $handle->Process('equipos/facturas');
   
               if ( $handle->processed )
               {
                  $this->data['Factura']['nombre_archivo'] = $handle->file_dst_name;
                  $this->data['Factura']['id_equipo'] = $id_equipo;
                  $this->data['Factura']['placa_inventario'] = $placa_inventario;
            
                  if ( $this->Factura->save($this->data) )
                  {
                     $datos_json['resultado'] = true;
                     $datos_json['id'] = $this->Factura->id;
                     $datos_json['nombre_archivo'] = $this->data['Factura']['nombre_archivo'];
                  }
               }
               $handle->Clean();
            }
         }
		}
      
      return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
   
   function eliminar_factura($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$factura = $this->Factura->findById($id);
		if ( !empty($factura) )
		{
			if ( !empty($factura['Factura']['nombre_archivo']) )
			{
				if ( unlink(WWW_ROOT.'equipos/facturas/'.$factura['Factura']['nombre_archivo']) )
				{
               // Borramos el registro de la BD.
					if ( $this->Factura->delete($factura['Factura']['id']) )
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
