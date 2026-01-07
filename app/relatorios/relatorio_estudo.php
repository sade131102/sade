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

/* FILTROS (opcionais) */
$anoLetivo   = $_GET['ano_letivo']   ?? date('Y');
$atividadeId = $_GET['atividade_id'] ?? null;

/* SERVICE */
$relatorioService = new RelatorioService($pdo);

/*
|--------------------------------------------------------------------------
| DADOS DO RELATÓRIO DE ESTUDO
|--------------------------------------------------------------------------
| Vamos reutilizar métodos existentes e complementar
*/
$dados = $relatorioService->relatorioEstudosAno((int)$anoLetivo);

$horasPorAtividade = $dados['horas_por_atividade'];
$presencas         = $dados['presencas'];
$totalHoras        = $dados['total_horas'];

/* HTML DO PDF */
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h1, h2 { text-align: center; }
.resumo p { margin: 4px 0; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
th { background-color: #f0f0f0; }
.footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
</style>
</head>
<body>

<h1>Relatório de Estudos</h1>
<h2>Ano Letivo: ' . htmlspecialchars($anoLetivo) . '</h2>

<div class="resumo">
<p><strong>Total de horas estudadas:</strong> ' . number_format($totalHoras, 2, ',', '.') . ' h</p>
<p><strong>Presenças:</strong> ' . ($presencas['Presença'] ?? 0) . '</p>
<p><strong>Faltas:</strong> ' . ($presencas['Falta'] ?? 0) . '</p>
</div>

<h2>Horas por Atividade</h2>
<table>
<thead>
<tr>
    <th>Atividade</th>
    <th>Tipo</th>
    <th>Total de Horas</th>
</tr>
</thead>
<tbody>';

if ($horasPorAtividade) {
    foreach ($horasPorAtividade as $a) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($a['nome']) . '</td>
            <td>' . ucfirst($a['tipo']) . '</td>
            <td>' . number_format($a['horas'], 2, ',', '.') . ' h</td>
        </tr>';
    }
} else {
    $html .= '
    <tr>
        <td colspan="3">Nenhum registro encontrado.</td>
    </tr>';
}

$html .= '
</tbody>
</table>

<div class="footer">
Relatório gerado em ' . date('d/m/Y H:i') . '
</div>

</body>
</html>
';

/* GERAR PDF */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* EXIBIR PDF */
$dompdf->stream(
    "relatorio_estudos_" . $anoLetivo . ".pdf",
    ["Attachment" => false]
);
