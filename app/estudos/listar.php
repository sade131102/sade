<?php
$titulo = 'Registros de Estudo';
$pagina = 'page-entity';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

require_once APP_ROOT . '/app/Services/EstudoService.php';
require_once APP_ROOT . '/app/Services/AtividadeService.php';

/* FILTROS */
$atividade_id = $_GET['atividade_id'] ?? '';
$ano_letivo   = $_GET['ano_letivo'] ?? '';

$atividadeService = new AtividadeService($pdo);
$atividades = $atividadeService->listarAtividades();

/* CONSULTA */
$where  = [];
$params = [];

if ($atividade_id) {
  $where[]  = 'e.atividade_id = ?';
  $params[] = $atividade_id;
}

if ($ano_letivo) {
  $where[]  = 'e.ano_letivo = ?';
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
  $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY e.data DESC, e.hora_inicio DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* RESUMO */
$totalRegistros = count($registros);
$totalHoras = 0;
$presencas  = 0;
$faltas     = 0;

foreach ($registros as $r) {
  if ($r['presenca'] === 'Presença') {
    $horas = (strtotime($r['hora_fim']) - strtotime($r['hora_inicio'])) / 3600;
    $totalHoras += $horas;
    $presencas++;
  } else {
    $faltas++;
  }
}

ob_start();
?>

<h1 class="titulo-central">Registros de Estudo</h1>
<p class="subtitulo-central">Edite, exclua e salve seus registros de estudo</p>

<div class="cards-acoes">

  <div class="card-acao">
    <h3>Registrar</h3>
    <p>Novo registro de estudo</p>
    <a href="salvar_estudo.php" class="btn btn-cadastrar">Registrar</a>
  </div>

  <div class="card-acao">
    <h3>Editar</h3>
    <p>Alterar dados existentes</p>
    <a href="#lista-estudos" class="btn btn-editar">Editar</a>
  </div>

  <div class="card-acao">
    <h3>Excluir</h3>
    <p>Remover registros</p>
    <a href="#lista-estudos" class="btn btn-excluir">Excluir</a>
  </div>

</div>

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

<section class="card">
  <h3>Resumo Geral</h3>
  <p><strong>Total de registros:</strong> <?= $totalRegistros ?></p>
  <p><strong>Total de horas estudadas:</strong> <?= number_format($totalHoras, 2, ',', '.') ?> h</p>
  <p><strong>Presenças:</strong> <?= $presencas ?></p>
  <p><strong>Faltas:</strong> <?= $faltas ?></p>
</section>

<section class="card" id="lista-estudos">
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
          <a href="editar.php?id=<?= $r['id'] ?>" class="btn btn-editar">Editar</a>
          <a href="excluir.php?id=<?= $r['id'] ?>" class="btn btn-excluir"
             onclick="return confirm('Deseja realmente excluir este registro?')">
             Excluir
          </a>
        </td>
      </tr>
    <?php endforeach; ?>

    </tbody>
  </table>
</section>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
