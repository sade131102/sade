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
DADOS DO RELATÓRIO (MODELO REAL)
cursos -> disciplinas -> estudos
=============================== */
$stmt = $pdo->prepare("
    SELECT
        c.titulo,
        c.tipo,
        c.instituicao,
        c.status,
        COALESCE(SUM(e.horas), 0) AS total_horas
    FROM cursos c
    LEFT JOIN disciplinas d
           ON d.curso_id = c.id
    LEFT JOIN estudos e
           ON e.disciplina_id = d.id
          AND e.data BETWEEN :inicio AND :fim
    GROUP BY c.id
    ORDER BY c.titulo
");

$stmt->execute([
    'inicio' => $dataInicio,
    'fim'    => $dataFim
]);

$relatorio = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    text-align: left;
  }
  th {
    background: #f3f4f6;
  }
</style>
</head>
<body>

<h1>Relatório Completo</h1>
<h2>Período: " . date('d/m/Y', strtotime($dataInicio)) . " a " . date('d/m/Y', strtotime($dataFim)) . "</h2>

<table>
  <thead>
    <tr>
      <th>Curso</th>
      <th>Tipo</th>
      <th>Instituição</th>
      <th>Status</th>
      <th>Horas</th>
    </tr>
  </thead>
  <tbody>
";

/* ===============================
LINHAS DA TABELA
=============================== */
if (empty($relatorio)) {
    $html .= "
    <tr>
      <td colspan='5' style='text-align:center;padding:20px;'>
        Nenhum dado encontrado no período.
      </td>
    </tr>";
} else {
    foreach ($relatorio as $r) {
        $html .= "
        <tr>
          <td>{$r['titulo']}</td>
          <td>{$r['tipo']}</td>
          <td>{$r['instituicao']}</td>
          <td>{$r['status']}</td>
          <td>" . number_format($r['total_horas'], 1, ',', '.') . " h</td>
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

$nomeArquivo =
    'relatorio_completo_' .
    date('Ymd', strtotime($dataInicio)) .
    '_a_' .
    date('Ymd', strtotime($dataFim)) .
    '.pdf';

$dompdf->stream($nomeArquivo, [
    'Attachment' => false
]);

exit;
