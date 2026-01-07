<?php
$titulo = 'Editar Projeto';
$pagina = 'page-form';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require "../../../app/Services/ProjetoService.php";

$id = (int) ($_GET['id'] ?? 0);

$service = new ProjetoService($pdo);
$projeto = $service->buscar($id);

if (!$projeto) {
  header("Location: listar.php");
  exit;
}

ob_start();
?>

<h1 class="titulo-central">Editar Projeto</h1>
<p class="subtitulo-central">Atualize o projeto</p>

<div class="card-crud formulario">
  <form method="post" action="salvar.php">

    <input type="hidden" name="id" value="<?= $projeto['id'] ?>">

    <div class="grupo">
      <label>Título</label>
      <input type="text" name="titulo"
             value="<?= htmlspecialchars($projeto['titulo']) ?>" required>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Tipo</label>
        <select name="tipo">
          <?php
          $tipos = ['Concurso','Exame','Vestibular','Plano de Leitura','Pesquisa','Outro'];
          foreach ($tipos as $tipo):
          ?>
            <option value="<?= $tipo ?>"
              <?= $projeto['tipo'] === $tipo ? 'selected' : '' ?>>
              <?= $tipo ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grupo">
        <label>Status</label>
        <select name="status">
          <?php
          $status = ['Planejado','Inscrito','Em andamento','Concluído','Aprovado','Classificado','Não Classificado','Interrompido'];
          foreach ($status as $s):
          ?>
            <option value="<?= $s ?>"
              <?= $projeto['status'] === $s ? 'selected' : '' ?>>
              <?= $s ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="grupo">
      <label>Data de início</label>
      <input type="date" name="data_inicio" value="<?= $projeto['data_inicio'] ?>" required>
    </div>

    <div class="grupo">
      <label>Objetivo / Meta</label>
      <textarea name="objetivo"><?= htmlspecialchars($projeto['objetivo'] ?? '') ?></textarea>
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
