<?php
require_once __DIR__ . "/../libs/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$pdf = new Dompdf();
$pdf->loadHtml("<h1>PDF OK</h1><p>Dompdf carregado com sucesso.</p>");
$pdf->render();
$pdf->stream("teste.pdf", ["Attachment" => false]);
