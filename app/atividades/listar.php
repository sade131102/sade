<?php
session_start();

require "../../config/db.php";
require "../../app/Services/AtividadeService.php";

/* PROTEÇÃO DE ACESSO */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$atividadeService = new AtividadeService($pdo);
$atividades = $atividadeService->listarAtividadesComResumo();

/* Dados para o gráfico */
$labels = [];
$previstas = [];
$realizadas = [];

foreach ($atividades as $a) {
    $labels[] = $a['nome'];
    $previstas[] = (float) $a['carga_prevista'];
    $realizadas[] = (float) $a['carga_realizada'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Atividades Cadastradas</title>
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<header class="topbar">
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    <span class="topbar-title">Atividades Cadastradas</span>
    <a href="../dashboard/dashboard.php" class="topbar-exit">Voltar</a>
</header>

<main class="conteudo">

<section class="card">
    <h3>Previsto × Realizado</h3>
    <div class="grafico-container">
        <canvas id="graficoAtividades"></canvas>
    </div>
</section>

<table>
<thead>
<tr>
    <th>Tipo</th>
    <th>Nome</th>
    <th>Instituição</th>
    <th>Finalidade</th>
    <th>Prevista (h)</th>
    <th>Realizadas (h)</th>
    <th>Faltantes (h)</th>
    <th>Cumprimento</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php if (!$atividades): ?>
<tr>
    <td colspan="9">Nenhuma atividade cadastrada.</td>
</tr>
<?php endif; ?>

<?php foreach ($atividades as $a): ?>
<?php
$percentual = $a['percentual'];

if ($percentual >= 75) {
    $classe = 'ok';
    $status = 'Em dia';
} elseif ($percentual >= 50) {
    $classe = 'atencao';
    $status = 'Atenção';
} else {
    $classe = 'atraso';
    $status = 'Atrasado';
}
?>
<tr>
    <td><?= ucfirst($a['tipo']) ?></td>
    <td><?= htmlspecialchars($a['nome']) ?></td>
    <td><?= htmlspecialchars($a['instituicao'] ?? '-') ?></td>
    <td><?= htmlspecialchars($a['finalidade'] ?? '-') ?></td>
    <td><?= number_format($a['carga_prevista'], 2, ',', '.') ?></td>
    <td><?= number_format($a['carga_realizada'], 2, ',', '.') ?></td>
    <td><?= number_format($a['carga_faltante'], 2, ',', '.') ?></td>
    <td><?= number_format($percentual, 1, ',', '.') ?>%</td>
    <td class="<?= $classe ?>">
        <?= number_format($percentual, 1, ',', '.') ?>% — <?= $status ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficoAtividades');
    if (!canvas) return;

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Horas Previstas',
                data: <?= json_encode($previstas) ?>,
                backgroundColor: [
                    '#4e79a7', '#f28e2b', '#e15759',
                    '#76b7b2', '#59a14f', '#edc949'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

</body>
</html>
