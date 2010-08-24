<?php
ini_set("memory_limit","16M");
App::import('Vendor','xtcpdf');
$tcpdf = new XTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$textfont = 'freesans';

$tcpdf->SetTitle('Información del equipo PI '.$placa_inventario);
$tcpdf->SetAuthor("SISMIUQ");
$tcpdf->SetAutoPageBreak(false);
$tcpdf->setHeaderFont(array($textfont,'',10));
$tcpdf->xheadercolor = array(255,255,255);

$tcpdf->AddPage();
$tcpdf->SetTextColor(0, 0, 0);
$tcpdf->SetFont($textfont,'N',10);

$tcpdf->writeHTML($filas_tabla, true, 0, true, 0);

echo $tcpdf->Output('Equipo_PI_'.$placa_inventario.'.pdf', 'D'); //el pdf se descarga
?>
