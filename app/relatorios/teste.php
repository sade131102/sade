<?php
require "../../libs/tcpdf/tcpdf.php";

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, 'TCPDF funcionando no XAMPP!');
$pdf->Output();
