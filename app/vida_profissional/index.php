<?php
$titulo = 'Vida Profissional';
$pagina = 'page-dashboard mod-profissional';


require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/*
|--------------------------------------------------------------------------
| CONSULTAS RESUMIDAS (DASHBOARD)
|--------------------------------------------------------------------------
| (por enquanto valores fixos = 0, prontos para futura integração)
*/

$totalContratosTemporarios   = 0;
$totalConcursosAprovados     = 0;
$totalInfracoesEticas        = 0;
$totalCurriculosCadastrados  = 0;

ob_start();
?>

<div class="page-central">

  <h1 class="titulo-central">Vida Profissional</h1>
  <p class="subtitulo-central">
    Visão geral da sua trajetória profissional
  </p>

  <!-- RESUMO -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Resumo Profissional</h2>

    <!-- ⚠️ MESMO GRID DA VIDA ACADÊMICA -->
    <div class="cards">

      <div class="card">
        <span class="icon">📄</span>
        <h3><?= $totalContratosTemporarios ?></h3>
        <p>Contratos Temporários</p>
      </div>

      <div class="card">
        <span class="icon">🏛️</span>
        <h3><?= $totalConcursosAprovados ?></h3>
        <p>Concursos Públicos Aprovados</p>
      </div>

      <div class="card">
        <span class="icon">⚖️</span>
        <h3><?= $totalInfracoesEticas ?></h3>
        <p>Infrações Éticas</p>
      </div>

      <div class="card">
        <span class="icon">🧾</span>
        <h3><?= $totalCurriculosCadastrados ?></h3>
        <p>Currículos Cadastrados</p>
      </div>

    </div>
  </section>

  <!-- AÇÕES -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Ações</h2>

    <div class="cards-acoes">

      <div class="card-acao">
        <h3>Infrações Éticas</h3>
        <p>Registros e ocorrências éticas</p>
        <a href="infracoes_eticas/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Conflitos Profissionais</h3>
        <p>Registros de conflitos no ambiente de trabalho</p>
        <a href="conflitos_profissionais/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Concursos Públicos</h3>
        <p>Provas e títulos</p>
        <a href="concursos_publicos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Contratos CLT e Avulsos</h3>
        <p>Histórico de contratos profissionais</p>
        <a href="contratos_clt_avulsos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Contratos Temporários</h3>
        <p>Contratações por tempo determinado</p>
        <a href="contratos_temporarios/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Currículos Acadêmicos</h3>
        <p>Gestão de currículos profissionais</p>
        <a href="curriculos/index.php" class="btn btn-listar">Acessar</a>
      </div>

    </div>
  </section>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
