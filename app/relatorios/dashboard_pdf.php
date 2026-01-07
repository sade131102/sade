<?php
session_start();

require "../../config/db.php";
require "../../app/Services/RelatorioService.php";
require "../../libs/dompdf/vendor/autoload.php";

use Dompdf\Dompdf;

/* PROTEÇÃO */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuarioId = (int) $_SESSION['usuario_id'];

/* SERVICE */
$relatorioService = new RelatorioService($pdo);
$dados = $relatorioService->relatorioDashboard($usuarioId);

/* HTML DO PDF */
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h1, h2 { text-align: center; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
th { background: #f0f0f0; }
.resumo { margin: 15px 0; }
</style>
</head>
<body>

<h1>Relatório do Dashboard</h1>

<div class="resumo">
<p><strong>Horas hoje:</strong> ' . number_format($dados['horas_hoje'], 2, ',', '.') . ' h</p>
<p><strong>Horas na semana:</strong> ' . number_format($dados['horas_semana'], 2, ',', '.') . ' h</p>
<p><strong>Horas no mês:</strong> ' . number_format($dados['horas_mes'], 2, ',', '.') . ' h</p>
<p><strong>Sequência de estudos:</strong> ' . $dados['sequencia'] . ' dias</p>
</div>

<h2>Metas</h2>
<ul>
<li>Meta diária: ' . $dados['metas']['meta_diaria']['status'] . '</li>
<li>Meta semanal: ' . $dados['metas']['meta_semanal']['status'] . '</li>
<li>Meta mensal: ' . $dados['metas']['meta_mensal']['status'] . '</li>
</ul>

<h2>Atividades</h2>
<table>
<tr>
<th>Tipo</th>
<th>Nome</th>
<th>Prevista (h)</th>
<th>Realizada (h)</th>
<th>Faltante (h)</th>
<th>Cumprimento</th>
</tr>';

foreach ($dados['atividades'] as $a) {
    $html .= '
    <tr>
        <td>' . ucfirst($a['tipo']) . '</td>
        <td>' . htmlspecialchars($a['nome']) . '</td>
        <td>' . number_format($a['carga_prevista'], 2, ',', '.') . '</td>
        <td>' . number_format($a['carga_realizada'], 2, ',', '.') . '</td>
        <td>' . number_format($a['carga_faltante'], 2, ',', '.') . '</td>
        <td>' . number_format($a['percentual'], 1, ',', '.') . '%</td>
    </tr>';
}

$html .= '
</table>

<p style="margin-top:20px; text-align:center;">
Relatório gerado em ' . date('d/m/Y H:i') . '
</p>

</body>
</html>
';

/* GERAR PDF */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* EXIBIR */
$dompdf->stream("relatorio_dashboard.pdf", ["Attachment" => false]);
