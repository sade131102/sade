<?php
$titulo = 'Relatórios';
$pagina = 'page-entity';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/path.php';
require_once APP_ROOT . '/app/templates/auth.php';

ob_start();
?>

<h1 class="titulo-central">Relatórios</h1>
<p class="subtitulo-central">Análises e consolidações do sistema</p>

<div class="cards-acoes">
  <div class="card-acao">
    <h3>Relatório Simples</h3>
    <p>Dados básicos das atividades</p>
    <a href="simples.php" class="btn btn-listar">Gerar</a>
  </div>

  <div class="card-acao">
    <h3>Relatório Completo</h3>
    <p>Todos os dados cadastrados</p>
    <a href="completo.php" class="btn btn-editar">Gerar</a>
  </div>

  <div class="card-acao">
    <h3>Atividades</h3>
    <p>Disciplinas, cursos e projetos</p>
    <a href="atividades.php" class="btn btn-cadastrar">Visualizar</a>
  </div>
</div>

<?php
$conteudo = ob_get_clean();
include __DIR__ . "/../templates/template.php";

