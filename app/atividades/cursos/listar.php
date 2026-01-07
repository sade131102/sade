<?php
$titulo = 'Cursos';
$pagina = 'page-list';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$service = new CursoService($pdo);
$cursos = $service->listar();

ob_start();
?>

<h1 class="titulo-central">Cursos</h1>
<p class="subtitulo-central">Formações cadastradas</p>

<div class="card-crud">

  <div class="acoes-topo">
    <a href="cadastrar.php" class="btn btn-cadastrar">+ Novo Curso</a>
  </div>

  <table class="tabela">
    <thead>
      <tr>
        <th>Título</th>
        <th>Tipo</th>
        <th>Instituição</th>
        <th>Status</th>
        <th style="width: 160px;">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($cursos)): ?>
        <tr>
          <td colspan="5" style="text-align:center; padding: 24px;">
            Nenhum curso cadastrado.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($cursos as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['titulo']) ?></td>
            <td><?= htmlspecialchars($c['tipo']) ?></td>
            <td><?= htmlspecialchars($c['instituicao']) ?></td>
            <td><?= htmlspecialchars($c['status']) ?></td>
            <td class="acoes">
              <a href="editar.php?id=<?= $c['id'] ?>" class="btn-acao editar">
                Editar
              </a>
              <a href="excluir.php?id=<?= $c['id'] ?>"
                 class="btn-acao excluir"
                 onclick="return confirm('Excluir este curso?')">
                Excluir
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</div>

<?php
$conteudo = ob_get_clean();
include "../../templates/template.php";
