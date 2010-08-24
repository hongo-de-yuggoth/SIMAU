<?php
class CertificadosController extends AppController
{
   //--------------------------------------------------------------------------
	
	function beforeFilter()
	{
		$this->set('display_contexto', 'none');
		$this->set('contexto', '');
	}
   
	//--------------------------------------------------------------------------
   
   function asignar_certificado($id_equipo, $placa_inventario)
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
         if ( !empty($_FILES['certificado']['name']) )
         {
            $handle = new Upload($_FILES['certificado']);
         
            if ( $handle->uploaded )
            {
               $handle->file_overwrite = true;
               $handle->file_safe_name = false;
               $handle->file_auto_rename = false;
               $handle->file_new_name_body = 'certificado('.$this->Certificado->getNextAutoIncrement().')_'.$placa_inventario;
               $handle->Process('equipos/certificados');
         
               if ( $handle->processed )
               {
                  $this->data['Certificado']['nombre_archivo'] = $handle->file_dst_name;
                  $this->data['Certificado']['id_equipo'] = $id_equipo;
                  $this->data['Certificado']['placa_inventario'] = $placa_inventario;
            
                  if ( $this->Certificado->save($this->data) )
                  {
                     $datos_json['resultado'] = true;
                     $datos_json['id'] = $this->Certificado->id;
                     $datos_json['nombre_archivo'] = $this->data['Certificado']['nombre_archivo'];
                  }
               }
               $handle->Clean();
            }
         }
		}
      
      return json_encode($datos_json);
	}
	
	//--------------------------------------------------------------------------
   
   function eliminar_certificado($id)
	{
		$this->autoLayout = false;
		$this->autoRender = false;
		
		$certificado = $this->Certificado->findById($id);
		
		if ( !empty($certificado) )
		{
			if ( !empty($certificado['Certificado']['nombre_archivo']) )
			{
				if ( unlink(WWW_ROOT.'equipos/certificados/'.$certificado['Certificado']['nombre_archivo']) )
				{
               // Borramos el registro de la BD.
					if ( $this->Certificado->delete($certificado['Certificado']['id']) )
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
