<?php
session_start();

require "../../config/db.php";
require "../../app/Services/EstudoService.php";
require "../../app/Services/AtividadeService.php";

/* PROTEÇÃO DE ACESSO */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$estudoService    = new EstudoService($pdo);
$atividadeService = new AtividadeService($pdo);

/* FILTROS */
$atividade_id = $_GET['atividade_id'] ?? '';
$ano_letivo   = $_GET['ano_letivo'] ?? '';

/* ATIVIDADES PARA FILTRO */
$atividades = $atividadeService->listarAtividades();

/*
|--------------------------------------------------------------------------
| REGISTROS DE ESTUDO (filtrados)
|--------------------------------------------------------------------------
| Mantemos aqui apenas a consulta simples de listagem,
| pois edição/exclusão dependem do ID do registro.
*/
$where  = [];
$params = [];

if ($atividade_id) {
    $where[]  = "e.atividade_id = ?";
    $params[] = $atividade_id;
}

if ($ano_letivo) {
    $where[]  = "e.ano_letivo = ?";
    $params[] = $ano_letivo;
}

$sql = "
    SELECT
        e.id,
        e.data,
        e.hora_inicio,
        e.hora_fim,
        e.presenca,
        e.ano_letivo,
        a.nome AS atividade,
        a.tipo
    FROM estudos e
    JOIN atividades a ON a.id = e.atividade_id
";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY e.data DESC, e.hora_inicio DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* RESUMO GERAL */
$totalRegistros = count($registros);
$totalHoras     = 0;
$presencas      = 0;
$faltas         = 0;

foreach ($registros as $r) {
    if ($r['presenca'] === 'Presença') {
        $horas = (strtotime($r['hora_fim']) - strtotime($r['hora_inicio'])) / 3600;
        $totalHoras += $horas;
        $presencas++;
    } else {
        $faltas++;
    }
}

/* TOTAL DE HORAS POR ATIVIDADE */
$totaisAtividade = [];

foreach ($registros as $r) {
    if ($r['presenca'] !== 'Presença') continue;

    if (!isset($totaisAtividade[$r['atividade']])) {
        $totaisAtividade[$r['atividade']] = [
            'tipo'  => $r['tipo'],
            'horas' => 0
        ];
    }

    $totaisAtividade[$r['atividade']]['horas'] +=
        (strtotime($r['hora_fim']) - strtotime($r['hora_inicio'])) / 3600;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Registros de Estudo</title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<header class="topbar">
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    <span class="topbar-title">Registros de Estudo</span>
    <a href="../dashboard/dashboard.php" class="topbar-exit">Voltar</a>
</header>

<main class="conteudo">

<!-- FILTROS -->
<form method="get" class="box">
    <label>Atividade</label>
    <select name="atividade_id">
        <option value="">Todas</option>
        <?php foreach ($atividades as $a): ?>
            <option value="<?= $a['id'] ?>" <?= $atividade_id == $a['id'] ? 'selected' : '' ?>>
                <?= ucfirst($a['tipo']) ?> — <?= htmlspecialchars($a['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Ano letivo</label>
    <input type="number" name="ano_letivo" value="<?= htmlspecialchars($ano_letivo) ?>">

    <button type="submit">Filtrar</button>
</form>

<!-- RESUMO GERAL -->
<section class="card">
    <h3>Resumo Geral</h3>
    <p><strong>Total de registros:</strong> <?= $totalRegistros ?></p>
    <p><strong>Total de horas estudadas:</strong> <?= number_format($totalHoras, 2, ',', '.') ?> h</p>
    <p><strong>Presenças:</strong> <?= $presencas ?></p>
    <p><strong>Faltas:</strong> <?= $faltas ?></p>
</section>

<!-- REGISTROS -->
<section class="card">
<h3>Registros de Estudo</h3>

<table>
<thead>
<tr>
    <th>Atividade</th>
    <th>Tipo</th>
    <th>Data</th>
    <th>Início</th>
    <th>Término</th>
    <th>Total (h)</th>
    <th>Presença</th>
    <th>Ano</th>
    <th>Ações</th>
</tr>
</thead>

<tbody>
<?php if (!$registros): ?>
<tr>
    <td colspan="9">Nenhum registro encontrado.</td>
</tr>
<?php endif; ?>

<?php foreach ($registros as $r): ?>
<?php
$horas = (strtotime($r['hora_fim']) - strtotime($r['hora_inicio'])) / 3600;
?>
<tr>
    <td><?= htmlspecialchars($r['atividade']) ?></td>
    <td><?= ucfirst($r['tipo']) ?></td>
    <td><?= date('d/m/Y', strtotime($r['data'])) ?></td>
    <td><?= $r['hora_inicio'] ?></td>
    <td><?= $r['hora_fim'] ?></td>
    <td><?= number_format($horas, 2, ',', '.') ?></td>
    <td><?= $r['presenca'] ?></td>
    <td><?= $r['ano_letivo'] ?></td>
    <td>
        <a class="btn" href="editar.php?id=<?= $r['id'] ?>">Editar</a>
        <a class="btn" href="excluir.php?id=<?= $r['id'] ?>"
           onclick="return confirm('Deseja realmente excluir este registro?')">
           Excluir
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<!-- TOTAL POR ATIVIDADE -->
<section class="card">
<h3>Total de Horas por Atividade</h3>

<table>
<thead>
<tr>
    <th>Atividade</th>
    <th>Tipo</th>
    <th>Total de Horas</th>
</tr>
</thead>

<tbody>
<?php if ($totaisAtividade): ?>
    <?php foreach ($totaisAtividade as $nome => $t): ?>
        <tr>
            <td><?= htmlspecialchars($nome) ?></td>
            <td><?= ucfirst($t['tipo']) ?></td>
            <td><?= number_format($t['horas'], 2, ',', '.') ?> h</td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="3">Nenhum dado disponível.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</section>

</main>
</body>
</html>
