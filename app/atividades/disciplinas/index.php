<?php
$titulo = 'Disciplinas';
$pagina = 'page-entity';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/path.php';
require_once APP_ROOT . '/app/templates/auth.php';

ob_start();
?>

<h1 class="titulo-central">Disciplinas</h1>
<p class="subtitulo-central">Gerencie as disciplinas do sistema</p>

<div class="cards-acoes">
  <div class="card-acao">
    <h3>Cadastrar</h3>
    <p>Adicionar uma nova disciplina</p>
    <a href="cadastrar.php" class="btn btn-cadastrar">Cadastrar</a>
  </div>

  <div class="card-acao">
    <h3>Listar</h3>
    <p>Visualizar disciplinas cadastradas</p>
    <a href="listar.php" class="btn btn-listar">Listar</a>
  </div>

  <div class="card-acao">
    <h3>Editar</h3>
    <p>Alterar dados de uma disciplina</p>
    <a href="listar.php" class="btn btn-editar">Editar</a>
  </div>

  <div class="card-acao">
    <h3>Excluir</h3>
    <p>Remover uma disciplina</p>
    <a href="listar.php" class="btn btn-excluir">Excluir</a>
  </div>
</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
