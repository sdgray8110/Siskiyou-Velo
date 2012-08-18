<?php
include('includes/db_connect.php');
$getID = $_GET['id'];

$result = mysql_query("SELECT * FROM wp_users WHERE ID = '$getID'", $connection);

//Use returned data
while ($row = mysql_fetch_array($result)) {

$name = $row['firstname'].' '.$row['lastname'];
$expire = strtotime($row['DateExpire']);
$renewal = 'Renewal Date: '.date('m/d/Y',$expire);


require('includes/fpdf16/fpdf.php');

$pdf=new FPDF('P','in','A4');

$pdf->AddPage();
$pdf->SetFont('Times','BU',9.5);
$pdf->Image('images/member_card.png',.5,.5,3.22);
$pdf->SetXY(.5, 1.75); 
$pdf->MultiCell(1.75, .15, $name , 0 , L , false);
$pdf->SetFont('Times','B',9.5);
$pdf->SetXY(2, 1.75); 
$pdf->MultiCell(1.75, .15, $renewal , 0 , R , false);
$pdf->Output();

}

?>

