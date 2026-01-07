<?php
$titulo = 'Editar Curso';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$id = (int) ($_GET['id'] ?? 0);

$service = new CursoService($pdo);
$curso = $service->buscar($id);

if (!$curso) {
  header("Location: listar.php");
  exit;
}

ob_start();
?>

<h1 class="titulo-central">Editar Curso</h1>
<p class="subtitulo-central">Atualize os dados do curso</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <input type="hidden" name="id" value="<?= $curso['id'] ?>">

    <div class="grupo">
      <label>Título do curso</label>
      <input type="text" name="titulo"
             value="<?= htmlspecialchars($curso['titulo']) ?>" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Tipo</label>
        <select name="tipo" required>
          <?php
          $tipos = ['Educação Básica','Graduação','Pós-Graduação','Formação Continuada'];
          foreach ($tipos as $tipo):
          ?>
            <option value="<?= $tipo ?>"
              <?= $curso['tipo'] === $tipo ? 'selected' : '' ?>>
              <?= $tipo ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grupo">
        <label>Status</label>
        <select name="status">
          <?php
          $status = ['Em andamento','Concluído','Trancado','Interrompido'];
          foreach ($status as $s):
          ?>
            <option value="<?= $s ?>"
              <?= $curso['status'] === $s ? 'selected' : '' ?>>
              <?= $s ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="grupo">
      <label>Instituição</label>
      <input type="text" name="instituicao"
             value="<?= htmlspecialchars($curso['instituicao']) ?>" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Data de início</label>
        <input type="date" name="data_inicio"
               value="<?= $curso['data_inicio'] ?>" required>
      </div>

      <div class="grupo">
        <label>Previsão de término</label>
        <input type="date" name="data_previsao_termino"
               value="<?= $curso['data_previsao_termino'] ?>">
      </div>
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
