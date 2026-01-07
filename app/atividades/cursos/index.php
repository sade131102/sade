<?php
$titulo = 'Cursos';
$pagina = 'page-entity';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/path.php';
require_once APP_ROOT . '/app/templates/auth.php';


ob_start();
?>

<h1 class="titulo-central">Cursos</h1>
<p class="subtitulo-central">Gerencie seus cursos e formações</p>

<div class="cards-acoes">
  <div class="card-acao">
    <h3>Cadastrar</h3>
    <p>Novo curso ou formação</p>
    <a href="cadastrar.php" class="btn btn-cadastrar">Cadastrar</a>
  </div>

  <div class="card-acao">
    <h3>Listar</h3>
    <p>Cursos cadastrados</p>
    <a href="listar.php" class="btn btn-listar">Listar</a>
  </div>

  <div class="card-acao">
    <h3>Editar</h3>
    <p>Alterar dados</p>
    <a href="listar.php" class="btn btn-editar">Editar</a>
  </div>

  <div class="card-acao">
    <h3>Excluir</h3>
    <p>Remover curso</p>
    <a href="listar.php" class="btn btn-excluir">Excluir</a>
  </div>
</div>

<?php
$conteudo = ob_get_clean();
include "../../templates/template.php";
