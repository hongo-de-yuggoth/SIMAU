<?php
ini_set("memory_limit","16M");
App::import('Vendor','xtcpdf');

//--------------------------------------------------------------------------
class MYPDF extends XTCPDF
{
	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('freesans', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

//--------------------------------------------------------------------------

$tcpdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$textfont = 'freesans';

$tcpdf->SetTitle('Solicitud de Servicio #'.$id_solicitud);
$tcpdf->SetAuthor("SISMUQ - Activos Fijos");
$tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$tcpdf->setHeaderFont(array($textfont,'',10));
$tcpdf->xheadercolor = array(255,255,255);
$tcpdf->setPrintHeader(false);
$tcpdf->setPrintFooter(true);

$tcpdf->AddPage();
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'N',10);
$tcpdf->writeHTML($filas_tabla, true, 0, true, 0);

echo $tcpdf->Output('Solicitud_Servicio_No_'.$id_solicitud.'.pdf', 'D'); //el pdf se descarga
?>
