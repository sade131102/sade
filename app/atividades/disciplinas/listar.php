<?php
$titulo = 'Disciplinas';
$pagina = 'page-list';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$service = new DisciplinaService($pdo);
$disciplinas = $service->listar();

ob_start();
?>
<h1 class="titulo-central">Disciplinas</h1>
<p class="subtitulo-central">Disciplinas vinculadas aos cursos</p>

<div class="acoes-topo">
  <a href="cadastrar.php" class="btn btn-cadastrar">+ Nova Disciplina</a>
</div>

<div class="card">
  <table class="tabela">
    <thead>
      <tr>
        <th>Curso</th>
        <th>Disciplina</th>
        <th>Código</th>
        <th>Carga Horária</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($disciplinas as $d): ?>
        <tr>
          <td><?= htmlspecialchars($d['curso']) ?></td>
          <td><?= htmlspecialchars($d['titulo']) ?></td>
          <td><?= htmlspecialchars($d['codigo'] ?? '-') ?></td>
          <td><?= $d['carga_horaria'] ? $d['carga_horaria'].'h' : '-' ?></td>
          <td class="acoes">
            <a href="editar.php?id=<?= $d['id'] ?>" class="btn-acao editar">Editar</a>
            <a href="excluir.php?id=<?= $d['id'] ?>"
               class="btn-acao excluir"
               onclick="return confirm('Excluir disciplina?')">
               Excluir
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
