<?php
$titulo = 'Estudos';
$pagina = 'page-entity';

require __DIR__ . "/../templates/auth.php";

ob_start();
?>

<div class="page-central">

  <h1 class="titulo-central">Estudos</h1>
  <p class="subtitulo-central">Gerencie seu tempo e histórico de estudo</p>

  <div class="cards-acoes">

    <div class="card-acao">
      <h3>Registrar Horas</h3>
      <p>Inserir estudo manualmente</p>
      <a href="registrar.php" class="btn btn-cadastrar">Registrar</a>
    </div>

    <div class="card-acao">
      <h3>Cronômetro</h3>
      <p>Iniciar, pausar ou encerrar estudo</p>
      <a href="cronometro.php" class="btn btn-editar">Cronômetro</a>
    </div>

    <div class="card-acao">
      <h3>Registros</h3>
      <p>Visualizar histórico de estudos</p>
      <a href="registros.php" class="btn btn-listar">Ver Registros</a>
    </div>

  </div>
</div>

<?php
$conteudo = ob_get_clean();
include __DIR__ . "/../templates/template.php";
