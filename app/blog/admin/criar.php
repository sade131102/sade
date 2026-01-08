<?php
$titulo = 'Novo Post';
$pagina = 'page-dashboard';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require_once APP_ROOT . '/app/templates/auth.php';

ob_start();
?>

<h1 class="titulo-central">Novo Post</h1>

<form method="post" action="salvar.php" class="card-crud formulario">

  <label>Título</label>
  <input name="titulo" required>

  <label>Autor</label>
  <input name="autor" required>

  <label>Categoria</label>
  <select name="categoria" required>
    <option value="pessoal">Área Pessoal</option>
    <option value="profissional">Área Profissional</option>
    <option value="academica">Área Acadêmica</option>
    <option value="religiosa">Área Religiosa</option>
  </select>

  <label>Imagem destacada (nome do arquivo)</label>
  <input name="imagem">

  <label>Conteúdo</label>
  <textarea name="conteudo" rows="10" required></textarea>

  <label>Status</label>
  <select name="status">
    <option value="rascunho">Rascunho</option>
    <option value="publicado">Publicado</option>
  </select>

  <div class="acoes-form">
    <button class="btn btn-cadastrar">Salvar</button>
  </div>

</form>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
