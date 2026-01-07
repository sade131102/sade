<?php
$titulo = 'Relatório de Atividades';
$pagina = 'page-list';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/* ===============================
FILTRO DE PERÍODO
=============================== */
$dataInicio = $_GET['inicio'] ?? date('Y-m-01');
$dataFim    = $_GET['fim']    ?? date('Y-m-t');

/* ===============================
DADOS DO RELATÓRIO
(apenas colunas EXISTENTES)
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

ob_start();
?>

<h1 class="titulo-central">Relatório de Atividades</h1>
<p class="subtitulo-central">
  Atividades registradas no período selecionado
</p>

<div class="card-crud">

  <!-- FILTRO -->
  <form method="get" class="acoes-topo">
    <div>
      <label>Data inicial</label>
      <input type="date" name="inicio" value="<?= $dataInicio ?>">
    </div>

    <div>
      <label>Data final</label>
      <input type="date" name="fim" value="<?= $dataFim ?>">
    </div>

    <button type="submit" class="btn btn-listar">
      Filtrar
    </button>
	<a href="pdf_atividades.php?inicio=<?= $dataInicio ?>&fim=<?= $dataFim ?>"
   class="btn btn-listar">
   Gerar PDF
</a>
  </form>

  <!-- TABELA -->
  <table class="tabela">
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
      <?php if (empty($atividades)): ?>
        <tr>
          <td colspan="5" style="text-align:center;padding:20px;">
            Nenhuma atividade encontrada no período.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($atividades as $a): ?>
          <tr>
            <td><?= (int) $a['atividade_id'] ?></td>
            <td><?= $a['disciplina_id'] ? (int) $a['disciplina_id'] : '-' ?></td>
            <td><?= $a['projeto_id'] ? (int) $a['projeto_id'] : '-' ?></td>
            <td><?= date('d/m/Y', strtotime($a['data'])) ?></td>
            <td><?= number_format($a['horas'], 1, ',', '.') ?> h</td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</div>

<?php
$conteudo = ob_get_clean();
include __DIR__ . "/../templates/template.php";
