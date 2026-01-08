<?php
$titulo = 'Vida Pessoal';
$pagina = 'page-dashboard mod-pessoal';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/*
|--------------------------------------------------------------------------
| CONSULTAS RESUMIDAS (DASHBOARD)
|--------------------------------------------------------------------------
| (valores iniciais = 0, prontos para integração futura)
*/

$totalMetasAtingidas        = 0;
$totalConflitosSociais     = 0;
$totalOrcamentos           = 0;
$totalTarefasConcluidas    = 0;

ob_start();
?>

<div class="page-central">

  <h1 class="titulo-central">Vida Pessoal</h1>
  <p class="subtitulo-central">
    Visão geral da sua vida pessoal, social e organizacional
  </p>

  <!-- RESUMO -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Resumo Pessoal</h2>

    <!-- GRID PADRÃO DE INDICADORES -->
    <div class="cards">

      <div class="card">
        <span class="icon">🎯</span>
        <h3><?= $totalMetasAtingidas ?></h3>
        <p>Metas Pessoais Atingidas</p>
      </div>

      <div class="card">
        <span class="icon">👥</span>
        <h3><?= $totalConflitosSociais ?></h3>
        <p>Conflitos Sociais e Familiares</p>
      </div>

      <div class="card">
        <span class="icon">💰</span>
        <h3><?= $totalOrcamentos ?></h3>
        <p>Orçamentos</p>
      </div>

      <div class="card">
        <span class="icon">✅</span>
        <h3><?= $totalTarefasConcluidas ?></h3>
        <p>Tarefas Concluídas</p>
      </div>

    </div>
  </section>

  <!-- AÇÕES -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Ações</h2>

    <div class="cards-acoes">

      <div class="card-acao">
        <h3>Exames e Doenças</h3>
        <p>Histórico de saúde e exames</p>
        <a href="exames_doencas/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Conflitos Sociais e Familiares</h3>
        <p>Registros de conflitos pessoais</p>
        <a href="conflitos_sociais/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Melhores Amigos</h3>
        <p>Gestão de vínculos sociais importantes</p>
        <a href="melhores_amigos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Galeria dos Familiares</h3>
        <p>Registro e memória familiar</p>
        <a href="familiares/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Viagens e Visitas Turísticas</h3>
        <p>Histórico de viagens e passeios</p>
        <a href="viagens/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Rotinas Semanais</h3>
        <p>Planejamento e acompanhamento semanal</p>
        <a href="rotinas_semanais/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Agendas Mensais</h3>
        <p>Organização mensal de compromissos</p>
        <a href="agendas_mensais/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Calendários Oficiais</h3>
        <p>Feriados e datas relevantes</p>
        <a href="calendarios/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Relatórios Financeiros</h3>
        <p>Análises e registros financeiros</p>
        <a href="relatorios_financeiros/index.php" class="btn btn-listar">Acessar</a>
      </div>

    </div>
  </section>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
