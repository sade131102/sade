<?php

/* ======================================================
RELATÓRIO SIMPLES – PDF (POR PERÍODO)
====================================================== */

require __DIR__ . "/../../libs/dompdf/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';


use Dompdf\Dompdf;
use Dompdf\Options;

/* ===============================
FILTRO DE PERÍODO
=============================== */
$dataInicio = $_GET['inicio'] ?? null;
$dataFim    = $_GET['fim'] ?? null;

if (!$dataInicio || !$dataFim) {
    die('Período inválido.');
}

/* ===============================
TOTAIS CADASTRAIS (GERAIS)
=============================== */
$totalCursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
$totalDisciplinas = $pdo->query("SELECT COUNT(*) FROM disciplinas")->fetchColumn();
$totalProjetos = $pdo->query("SELECT COUNT(*) FROM projetos")->fetchColumn();

/* ===============================
HORAS ESTUDADAS NO PERÍODO
=============================== */
$stmtHoras = $pdo->prepare("
    SELECT COALESCE(SUM(horas), 0)
    FROM estudos
    WHERE data BETWEEN :inicio AND :fim
");
$stmtHoras->execute([
    'inicio' => $dataInicio,
    'fim'    => $dataFim
]);
$totalHoras = $stmtHoras->fetchColumn();

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
    font-size: 12px;
    color: #111;
  }
  h1 {
    text-align: center;
    margin-bottom: 4px;
  }
  h2 {
    text-align: center;
    font-size: 13px;
    font-weight: normal;
    margin-bottom: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }
  td:first-child {
    font-weight: bold;
    width: 70%;
  }
</style>
</head>
<body>

<h1>Relatório Simples</h1>
<h2>Período: " . date('d/m/Y', strtotime($dataInicio)) . " a " . date('d/m/Y', strtotime($dataFim)) . "</h2>

<table>
  <tr>
    <td>Total de cursos cadastrados</td>
    <td>{$totalCursos}</td>
  </tr>
  <tr>
    <td>Total de disciplinas cadastradas</td>
    <td>{$totalDisciplinas}</td>
  </tr>
  <tr>
    <td>Total de projetos cadastrados</td>
    <td>{$totalProjetos}</td>
  </tr>
  <tr>
    <td>Total de horas estudadas no período</td>
    <td>" . number_format($totalHoras, 1, ',', '.') . " h</td>
  </tr>
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
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* Nome do arquivo */
$nomeArquivo = "relatorio_simples_" .
               date('Ymd', strtotime($dataInicio)) .
               "_a_" .
               date('Ymd', strtotime($dataFim)) .
               ".pdf";

/* Exibir no navegador */
$dompdf->stream($nomeArquivo, [
    "Attachment" => false
]);

exit;
