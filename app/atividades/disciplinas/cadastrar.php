<?php
$titulo = 'Cadastrar Disciplina';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

ob_start();
?>

<?php
$titulo = 'Cadastrar Disciplina';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$cursoService = new CursoService($pdo);
$cursos = $cursoService->listar();

ob_start();
?>

<h1 class="titulo-central">Cadastrar Disciplina</h1>
<p class="subtitulo-central">Informe os dados da nova disciplina</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <div class="grupo">
      <label>Curso</label>
      <select name="curso_id" required>
        <option value="">Selecione o curso</option>
        <?php foreach ($cursos as $curso): ?>
          <option value="<?= $curso['id'] ?>">
            <?= htmlspecialchars($curso['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="grupo">
      <label>Título da disciplina</label>
      <input type="text" name="titulo" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Código</label>
        <input type="text" name="codigo">
      </div>

      <div class="grupo">
        <label>Carga horária</label>
        <input type="number" name="carga_horaria">
      </div>
    </div>

    <div class="grupo">
      <label>Professor</label>
      <input type="text" name="professor">
    </div>

    <div class="grupo">
      <label>Ementa</label>
      <textarea name="ementa"></textarea>
    </div>

    <div class="grupo">
      <label>Bibliografia</label>
      <textarea name="bibliografia"></textarea>
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
