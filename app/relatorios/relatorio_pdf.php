<?php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../libs/dompdf/vendor/autoload.php";

use Dompdf\Dompdf;

$ano = $_GET['ano'] ?? date('Y');

$sql = $pdo->prepare("
SELECT 
    a.nome,
    e.data,
    e.hora_inicio,
    e.hora_fim,
    e.presenca,
    e.conteudo
FROM estudos e
JOIN atividades a ON a.id = e.atividade_id
WHERE e.ano_letivo = ?
ORDER BY e.data
");
$sql->execute([$ano]);

$html = "
<h2>Relatório de Estudos – Ano $ano</h2>
<table width='100%' border='1' cellspacing='0' cellpadding='5'>
<tr>
<th>Atividade</th>
<th>Data</th>
<th>Horas</th>
<th>Presença</th>
<th>Conteúdo</th>
</tr>";

while ($r = $sql->fetch()) {
    $horas = (strtotime($r['hora_fim']) - strtotime($r['hora_inicio'])) / 3600;

    $html .= "
    <tr>
        <td>{$r['nome']}</td>
        <td>{$r['data']}</td>
        <td>".number_format($horas,2)."h</td>
        <td>{$r['presenca']}</td>
        <td>{$r['conteudo']}</td>
    </tr>";
}

$html .= "</table>";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper("A4", "portrait");
$pdf->render();
$pdf->stream("relatorio_estudos_$ano.pdf", ["Attachment" => false]);
