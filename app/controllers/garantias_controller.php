<?php
class GarantiasController extends AppController
{
   //--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
   
	//--------------------------------------------------------------------------
   
   function asignar_garantia($id_equipo, $placa_inventario)
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
         if ( !empty($_FILES['garantia']['name']) )
         {
            $handle = new Upload($_FILES['garantia']);
      
            if ( $handle->uploaded )
            {
               $handle->file_overwrite = true;
               $handle->file_safe_name = false;
               $handle->file_auto_rename = false;
               $handle->file_new_name_body = 'garantia('.$this->Garantia->getNextAutoIncrement().')_'.$placa_inventario;
               $handle->Process('equipos/garantias');
   
               if ( $handle->processed )
               {
                  $this->data['Garantia']['nombre_archivo'] = $handle->file_dst_name;
                  $this->data['Garantia']['id_equipo'] = $id_equipo;
                  $this->data['Garantia']['placa_inventario'] = $placa_inventario;
            
                  if ( $this->Garantia->save($this->data) )
                  {
                     $datos_json['resultado'] = true;
                     $datos_json['id'] = $this->Garantia->id;
                     $datos_json['nombre_archivo'] = $this->data['Garantia']['nombre_archivo'];
                  }
               }
               $handle->Clean();
            }
         }
		}
      
      return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
   
   function eliminar_garantia($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$garantia = $this->Garantia->findById($id);
		if ( !empty($garantia) )
		{
			if ( !empty($garantia['Garantia']['nombre_archivo']) )
			{
				if ( unlink(WWW_ROOT.'equipos/garantias/'.$garantia['Garantia']['nombre_archivo']) )
				{
               // Borramos el registro de la BD.
					if ( $this->Garantia->delete($garantia['Garantia']['id']) )
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
