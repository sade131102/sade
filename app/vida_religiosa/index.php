<?php
$titulo = 'Vida Religiosa';
$pagina = 'page-dashboard mod-religiosa';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

/*
|--------------------------------------------------------------------------
| CONSULTAS RESUMIDAS (DASHBOARD)
|--------------------------------------------------------------------------
| (valores iniciais = 0, prontos para integração futura)
*/

$totalPresencasInstitucionais = 0;
$totalPalestrasPublicas       = 0;
$totalProgramasRadio          = 0;
$totalEstudosComplementares   = 0;

ob_start();
?>

<div class="page-central">

  <h1 class="titulo-central">Vida Religiosa</h1>
  <p class="subtitulo-central">
    Acompanhamento da sua vivência espiritual, formativa e institucional
  </p>

  <!-- RESUMO -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Resumo Religioso</h2>

    <!-- GRID PADRÃO DE INDICADORES -->
    <div class="cards">

      <div class="card">
        <span class="icon">🏛️</span>
        <h3><?= $totalPresencasInstitucionais ?></h3>
        <p>Presenças nas Atividades Institucionais</p>
      </div>

      <div class="card">
        <span class="icon">🎤</span>
        <h3><?= $totalPalestrasPublicas ?></h3>
        <p>Palestras Públicas Ministradas</p>
      </div>

      <div class="card">
        <span class="icon">📻</span>
        <h3><?= $totalProgramasRadio ?></h3>
        <p>Programas de Rádio Realizados</p>
      </div>

      <div class="card">
        <span class="icon">📖</span>
        <h3><?= $totalEstudosComplementares ?></h3>
        <p>Estudos e Cursos Complementares</p>
      </div>

    </div>
  </section>

  <!-- AÇÕES -->
  <section class="dashboard-section">
    <h2 class="titulo-central dashboard-title">Ações</h2>

    <div class="cards-acoes">

      <div class="card-acao">
        <h3>Estudos Básicos</h3>
        <p>Fundamentos doutrinários e introdutórios</p>
        <a href="estudos_basicos/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Estudos e Cursos Complementares</h3>
        <p>Aprofundamento doutrinário e formativo</p>
        <a href="estudos_complementares/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Palestras Públicas</h3>
        <p>Registros de palestras e exposições</p>
        <a href="palestras_publicas/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Programas de Rádio</h3>
        <p>Produção e participação em programas</p>
        <a href="programas_radio/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Desenvolvimento Mediúnico</h3>
        <p>Acompanhamento da prática mediúnica</p>
        <a href="desenvolvimento_mediunico/index.php" class="btn btn-listar">Acessar</a>
      </div>

      <div class="card-acao">
        <h3>Participação na Casa Espírita</h3>
        <p>Atividades, serviços e engajamento institucional</p>
        <a href="atividades_casa_espirita/index.php" class="btn btn-listar">Acessar</a>
      </div>

    </div>
  </section>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
