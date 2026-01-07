<?php

require __DIR__ . "/../../libs/dompdf/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* ===============================
FILTRO DE PERÍODO
=============================== */
$dataInicio = $_GET['inicio'] ?? null;
$dataFim    = $_GET['fim']    ?? null;

if (!$dataInicio || !$dataFim) {
    die('Período inválido.');
}

/* ===============================
DADOS DO RELATÓRIO
(apenas colunas existentes)
=============================== */
$stmt = $pdo->prepare("
    SELECT
        e.atividade_id,
        e.disciplina_id,
        e.projeto_id,
        e.data,
        e.horas
    FROM estudos e
    WHERE e.data BETWEEN :inicio AND :fim
    ORDER BY e.data DESC
");

$stmt->execute([
    'inicio' => $dataInicio,
    'fim'    => $dataFim
]);

$atividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
HTML DO PDF
=============================== */
$html = "
<!DOCTYPE html>
<html lang='pt-br'>
<head>
<meta charset='UTF-8'>
<style>
  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #111;
  }
  h1 {
    text-align: center;
    margin-bottom: 4px;
  }
  h2 {
    text-align: center;
    font-size: 12px;
    font-weight: normal;
    margin-bottom: 16px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 8px;
    border-bottom: 1px solid #ccc;
    text-align: center;
  }
  th {
    background: #f3f4f6;
  }
</style>
</head>
<body>

<h1>Relatório de Atividades</h1>
<h2>Período: " . date('d/m/Y', strtotime($dataInicio)) . " a " . date('d/m/Y', strtotime($dataFim)) . "</h2>

<table>
  <thead>
    <tr>
      <th>Atividade (ID)</th>
      <th>Disciplina (ID)</th>
      <th>Projeto (ID)</th>
      <th>Data</th>
      <th>Horas</th>
    </tr>
  </thead>
  <tbody>
";

/* ===============================
LINHAS DA TABELA
=============================== */
if (empty($atividades)) {
    $html .= "
    <tr>
      <td colspan='5' style='padding:20px;'>
        Nenhuma atividade encontrada no período.
      </td>
    </tr>";
} else {
    foreach ($atividades as $a) {
        $html .= "
        <tr>
          <td>{$a['atividade_id']}</td>
          <td>" . ($a['disciplina_id'] ?? '-') . "</td>
          <td>" . ($a['projeto_id'] ?? '-') . "</td>
          <td>" . date('d/m/Y', strtotime($a['data'])) . "</td>
          <td>" . number_format($a['horas'], 1, ',', '.') . " h</td>
        </tr>";
    }
}

$html .= "
  </tbody>
</table>

</body>
</html>
";

/* ===============================
GERAÇÃO DO PDF
=============================== */
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

/* Nome do arquivo */
$nomeArquivo =
    'relatorio_atividades_' .
    date('Ymd', strtotime($dataInicio)) .
    '_a_' .
    date('Ymd', strtotime($dataFim)) .
    '.pdf';

/* Exibir no navegador */
$dompdf->stream($nomeArquivo, [
    'Attachment' => false
]);

exit;
