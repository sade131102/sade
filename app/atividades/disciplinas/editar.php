<?php
$titulo = 'Editar Disciplina';
$pagina = 'page-form';

require "../../templates/auth.php";
require "../../../config/db.php";
require "../../../app/Services/DisciplinaService.php";
require "../../../app/Services/CursoService.php";

$id = (int) ($_GET['id'] ?? 0);

$disciplinaService = new DisciplinaService($pdo);
$cursoService      = new CursoService($pdo);

$disciplina = $disciplinaService->buscar($id);
$cursos     = $cursoService->listar();

if (!$disciplina) {
  header("Location: listar.php");
  exit;
}

ob_start();
?>
<h1 class="titulo-central">Editar Disciplina</h1>
<p class="subtitulo-central">Atualize os dados da disciplina</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <input type="hidden" name="id" value="<?= $disciplina['id'] ?>">

    <div class="grupo">
      <label>Curso</label>
      <select name="curso_id" required>
        <?php foreach ($cursos as $curso): ?>
          <option value="<?= $curso['id'] ?>"
            <?= $curso['id'] == $disciplina['curso_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($curso['titulo']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="grupo">
      <label>Título da disciplina</label>
      <input type="text" name="titulo"
             value="<?= htmlspecialchars($disciplina['titulo']) ?>" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Código</label>
        <input type="text" name="codigo"
               value="<?= htmlspecialchars($disciplina['codigo'] ?? '') ?>">
      </div>

      <div class="grupo">
        <label>Carga horária</label>
        <input type="number" name="carga_horaria"
               value="<?= htmlspecialchars($disciplina['carga_horaria'] ?? '') ?>">
      </div>
    </div>

    <div class="grupo">
      <label>Professor</label>
      <input type="text" name="professor"
             value="<?= htmlspecialchars($disciplina['professor'] ?? '') ?>">
    </div>

    <div class="grupo">
      <label>Ementa</label>
      <textarea name="ementa"><?= htmlspecialchars($disciplina['ementa'] ?? '') ?></textarea>
    </div>

    <div class="grupo">
      <label>Bibliografia</label>
      <textarea name="bibliografia"><?= htmlspecialchars($disciplina['bibliografia'] ?? '') ?></textarea>
    </div>

    <div class="acoes-form">
      <button class="btn btn-editar">Salvar Alterações</button>
      <a href="listar.php" class="btn btn-listar">Cancelar</a>
    </div>

  </form>
</div>

<?php
$conteudo = ob_get_clean();
include "../../templates/template.php";
