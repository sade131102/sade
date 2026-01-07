<?php
$titulo = 'Relatório Completo';
$pagina = 'page-list';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/* ===============================
FILTRO DE PERÍODO
=============================== */
$dataInicio = $_GET['inicio'] ?? date('Y-m-01');
$dataFim    = $_GET['fim']    ?? date('Y-m-t');

/* ===============================
RELATÓRIO COMPLETO POR CURSO
=============================== */
$stmt = $pdo->prepare("
    SELECT
        c.id,
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

ob_start();
?>

<h1 class="titulo-central">Relatório Completo</h1>
<p class="subtitulo-central">
  Cursos e carga horária no período selecionado
</p>

<div class="card-crud">

  <!-- FILTRO -->
  <form method="get" class="acoes-topo" style="gap:12px;">
    <div>
      <label>Data inicial</label><br>
      <input type="date" name="inicio" value="<?= $dataInicio ?>">
    </div>

    <div>
      <label>Data final</label><br>
      <input type="date" name="fim" value="<?= $dataFim ?>">
    </div>

    <button class="btn btn-listar" type="submit">
      Filtrar
    </button>

    <a href="pdf_completo.php?inicio=<?= $dataInicio ?>&fim=<?= $dataFim ?>"
       class="btn btn-listar">
       Gerar PDF
    </a>
  </form>

  <!-- TABELA -->
  <table class="tabela">
    <thead>
      <tr>
        <th>Curso</th>
        <th>Tipo</th>
        <th>Instituição</th>
        <th>Status</th>
        <th>Horas no período</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($relatorio)): ?>
        <tr>
          <td colspan="5" style="text-align:center;padding:20px;">
            Nenhum dado encontrado no período.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($relatorio as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['titulo']) ?></td>
            <td><?= htmlspecialchars($r['tipo']) ?></td>
            <td><?= htmlspecialchars($r['instituicao']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= number_format($r['total_horas'], 1, ',', '.') ?> h</td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</div>

<?php
$conteudo = ob_get_clean();
include __DIR__ . "/../templates/template.php";
