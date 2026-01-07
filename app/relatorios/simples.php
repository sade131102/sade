<?php
$titulo = 'Relatório Simples';
$pagina = 'page-list';

require __DIR__ . "/../templates/auth.php";
require __DIR__ . "/../../config/db.php";

/* ===============================
FILTRO DE PERÍODO
=============================== */
$dataInicio = $_GET['inicio'] ?? date('Y-m-01');
$dataFim    = $_GET['fim']    ?? date('Y-m-t');

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

ob_start();
?>

<h1 class="titulo-central">Relatório Simples</h1>
<p class="subtitulo-central">Visão geral do sistema por período</p>

<div class="card-crud">

  <!-- FILTRO POR PERÍODO -->
  <form method="get" class="acoes-topo" style="gap: 12px;">
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

    <a href="pdf_simples.php?inicio=<?= $dataInicio ?>&fim=<?= $dataFim ?>"
       class="btn btn-listar">
       Gerar PDF
    </a>
  </form>

  <!-- DADOS -->
  <table class="tabela">
    <tbody>
      <tr>
        <td><strong>Total de cursos cadastrados</strong></td>
        <td><?= $totalCursos ?></td>
      </tr>
      <tr>
        <td><strong>Total de disciplinas cadastradas</strong></td>
        <td><?= $totalDisciplinas ?></td>
      </tr>
      <tr>
        <td><strong>Total de projetos cadastrados</strong></td>
        <td><?= $totalProjetos ?></td>
      </tr>
      <tr>
        <td>
          <strong>Total de horas estudadas</strong><br>
          <small>Período: <?= date('d/m/Y', strtotime($dataInicio)) ?>
          a <?= date('d/m/Y', strtotime($dataFim)) ?></small>
        </td>
        <td><?= number_format($totalHoras, 1, ',', '.') ?> h</td>
      </tr>
    </tbody>
  </table>

</div>

<?php
$conteudo = ob_get_clean();
include __DIR__ . "/../templates/template.php";
