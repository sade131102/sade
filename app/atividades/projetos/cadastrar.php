<?php
$titulo = 'Cadastrar Projeto';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

ob_start();
?>

<h1 class="titulo-central">Cadastrar Projeto</h1>
<p class="subtitulo-central">Defina o tipo e os objetivos do projeto</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <div class="grupo">
      <label>Título do projeto</label>
      <input type="text" name="titulo" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Tipo</label>
        <select name="tipo" required>
          <option>Concurso</option>
          <option>Exame</option>
          <option>Vestibular</option>
          <option>Plano de Leitura</option>
          <option>Pesquisa</option>
          <option>Outro</option>
        </select>
      </div>

      <div class="grupo">
        <label>Status</label>
        <select name="status">
          <option>Planejado</option>
          <option>Inscrito</option>
          <option>Em andamento</option>
          <option>Concluído</option>
          <option>Aprovado</option>
          <option>Classificado</option>
          <option>Não Classificado</option>
          <option>Interrompido</option>
        </select>
      </div>
    </div>

    <div class="grupo">
      <label>Data de início</label>
      <input type="date" name="data_inicio" required>
    </div>

    <div class="grupo">
      <label>Objetivo / Meta</label>
      <textarea name="objetivo"></textarea>
    </div>

    <div class="acoes-form">
      <button class="btn btn-cadastrar">Cadastrar</button>
      <a href="listar.php" class="btn btn-listar">Cancelar</a>
    </div>

  </form>
</div>

<?php
$conteudo = ob_get_clean();
include "../../templates/template.php";
