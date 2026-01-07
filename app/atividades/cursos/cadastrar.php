<?php
$titulo = 'Cadastrar Curso';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

ob_start();
?>

<h1 class="titulo-central">Cadastrar Curso</h1>
<p class="subtitulo-central">Informe os dados do curso</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <div class="grupo">
      <label>Título do curso</label>
      <input type="text" name="titulo" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Tipo</label>
        <select name="tipo" required>
          <option>Educação Básica</option>
          <option>Graduação</option>
          <option>Pós-Graduação</option>
          <option>Formação Continuada</option>
        </select>
      </div>

      <div class="grupo">
        <label>Status</label>
        <select name="status">
          <option>Em andamento</option>
          <option>Concluído</option>
          <option>Trancado</option>
          <option>Interrompido</option>
        </select>
      </div>
    </div>

    <div class="grupo">
      <label>Instituição</label>
      <input type="text" name="instituicao" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Data de início</label>
        <input type="date" name="data_inicio" required>
      </div>

      <div class="grupo">
        <label>Previsão de término</label>
        <input type="date" name="data_previsao_termino">
      </div>
    </div>

    <div class="acoes-form">
      <button class="btn btn-cadastrar">Cadastrar</button>
      <a href="listar.php" class="btn btn-listar">Cancelar</a>
    </div>

  </form>
</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
