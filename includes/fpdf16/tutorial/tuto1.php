<?php

$getName = $_GET['name'];
$renewal = 'Renewal Date: 11/13/2009';

require('../fpdf.php');

$pdf=new FPDF('P','in','A4');

$pdf->AddPage();
$pdf->SetFont('Times','BU',9);
$pdf->Image('../../../images/member_card.png',.5,.5,3.22);
$pdf->SetXY(.5, 1.75); 
$pdf->MultiCell(1.75, .15, $getName , 0 , L , false);
$pdf->SetFont('Times','B',9);
$pdf->SetXY(2, 1.75); 
$pdf->MultiCell(1.75, .15, $renewal , 0 , R , false);
$pdf->Output();

?>

