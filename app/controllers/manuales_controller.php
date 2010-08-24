<?php
class ManualesController extends AppController
{
   //--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
   
	//--------------------------------------------------------------------------
   
   function asignar_manual($id_equipo, $placa_inventario)
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
         if ( !empty($_FILES['manual']['name']) )
         {
            $handle = new Upload($_FILES['manual']);
   
            if ( $handle->uploaded )
            {
               $handle->file_overwrite = true;
               $handle->file_safe_name = false;
               $handle->file_auto_rename = false;
               $handle->file_new_name_body = 'manual('.$this->Manual->getNextAutoIncrement().')_'.$placa_inventario;
               $handle->Process('equipos/manuales');
   
               if ( $handle->processed )
               {
                  $this->data['Manual']['nombre_archivo'] = $handle->file_dst_name;
                  $this->data['Manual']['id_equipo'] = $id_equipo;
                  $this->data['Manual']['placa_inventario'] = $placa_inventario;
            
                  if ( $this->Manual->save($this->data) )
                  {
                     $datos_json['resultado'] = true;
                     $datos_json['id'] = $this->Manual->id;
                     $datos_json['nombre_archivo'] = $this->data['Manual']['nombre_archivo'];
                  }
               }
               $handle->Clean();
            }
         }
		}
      
      return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
   
   function eliminar_manual($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$manual = $this->Manual->findById($id);
		if ( !empty($manual) )
		{
			if ( !empty($manual['Manual']['nombre_archivo']) )
			{
				if ( unlink(WWW_ROOT.'equipos/manuales/'.$manual['Manual']['nombre_archivo']) )
				{
               // Borramos el registro de la BD.
					if ( $this->Manual->delete($manual['Manual']['id']) )
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
