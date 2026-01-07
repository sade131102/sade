<?php
$titulo = 'Vida Acadêmica';
$pagina = 'page-entity';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

ob_start();
?>

<h1 class="titulo-central">Vida Acadêmica</h1>
<p class="subtitulo-central">
  Organização da formação, estudos e trajetória acadêmica
</p>

<div class="cards-acoes">

  <div class="card-acao">
    <h3>Cursos</h3>
    <p>Formações, cursos livres e extensões</p>
    <a href="cursos/index.php" class="btn btn-listar">Acessar</a>
  </div>

  <div class="card-acao">
    <h3>Disciplinas</h3>
    <p>Disciplinas cursadas e em andamento</p>
    <a href="disciplinas/index.php" class="btn btn-listar">Acessar</a>
  </div>

  <div class="card-acao">
    <h3>Estudos</h3>
    <p>Registros de estudo e dedicação</p>
    <a href="estudos/index.php" class="btn btn-listar">Acessar</a>
  </div>

  <div class="card-acao">
    <h3>Projetos</h3>
    <p>Projetos acadêmicos e pesquisas</p>
    <a href="projetos/index.php" class="btn btn-listar">Acessar</a>
  </div>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
