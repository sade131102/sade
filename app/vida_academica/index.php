<?php
$titulo = 'Vida Acadêmica';
$pagina = 'page-dashboard mod-academica';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/*
|--------------------------------------------------------------------------
| CONSULTAS RESUMIDAS (DASHBOARD)
|--------------------------------------------------------------------------
*/

$totalEstudos = $pdo->query("SELECT COUNT(*) FROM estudos")->fetchColumn();

$totalHoras = (float) $pdo->query("
  SELECT SUM(TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fim)) / 60
  FROM estudos
  WHERE presenca = 'Presença'
")->fetchColumn();

$totalCursos = $pdo->query("
  SELECT COUNT(*) FROM atividades WHERE tipo = 'curso'
")->fetchColumn();

$totalDisciplinas = $pdo->query("
  SELECT COUNT(*) FROM atividades WHERE tipo = 'disciplina'
")->fetchColumn();

ob_start();
?>

<div class="page-central">

  <h1 class="titulo-central">Vida Acadêmica</h1>
  <p class="subtitulo-central">
    Visão geral da sua trajetória acadêmica
  </p>

  <!-- RESUMO -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Resumo Acadêmico</h2>

    <!-- ⚠️ USAR SOMENTE .cards (grid já existente) -->
    <div class="cards">

      <div class="card">
         <span class="icon">📘</span>
		 <h3><?= $totalEstudos ?></h3>
        <p>Registros de Estudo</p>
      </div>

      <div class="card">
	    <span class="icon">⏱️</span>
        <h3><?= number_format($totalHoras, 1, ',', '.') ?> h</h3>
        <p>Horas Estudadas</p>
      </div>

      <div class="card">
	    <span class="icon">🎓</span>
        <h3><?= $totalCursos ?></h3>
        <p>Cursos</p>
      </div>

      <div class="card">
	    <span class="icon">📚</span>
        <h3><?= $totalDisciplinas ?></h3>
        <p>Disciplinas</p>
      </div>

    </div>
  </section>

  <!-- AÇÕES -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Ações</h2>

    <div class="cards-acoes">

      <div class="card-acao">
        <h3>Cursos</h3>
        <p>Formações e cursos livres</p>
        <a href="cursos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Disciplinas</h3>
        <p>Disciplinas cursadas</p>
        <a href="disciplinas/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Estudos</h3>
        <p>Registros de estudo</p>
        <a href="estudos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Projetos</h3>
        <p>Projetos acadêmicos</p>
        <a href="projetos/index.php" class="btn btn-listar">Acessar</a>
      </div>
	  
	  <div class="card-acao">
	  <h3>Relatórios</h3>
	  <p>Relatórios Acadêmicos</p>
	  <a href="relatorios/index.php" class="btn btn-listar">Acessar</a>
	  </div>
	  
      <div class="card-acao">
	  <h3>Atividades</h3>
	  <p>Atividades Complementares</p>
	  <a href="relatorios/index.php" class="btn btn-listar">Acessar</a>
	  </div>
	  
    </div>
  </section>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
