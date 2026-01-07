<?php
$titulo = 'Projetos';
$pagina = 'page-list';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require "../../../app/Services/ProjetoService.php";

$service = new ProjetoService($pdo);
$projetos = $service->listar();

ob_start();
?>

<h1 class="titulo-central">Projetos</h1>
<p class="subtitulo-central">Acompanhamento de desempenho e metas</p>

<div class="acoes-topo">
  <a href="cadastrar.php" class="btn btn-cadastrar">+ Novo Projeto</a>
</div>

<div class="card">
  <table class="tabela">
    <thead>
      <tr>
        <th>Título</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Início</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($projetos as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['titulo']) ?></td>
          <td><?= htmlspecialchars($p['tipo']) ?></td>
          <td><?= htmlspecialchars($p['status']) ?></td>
          <td><?= htmlspecialchars($p['data_inicio']) ?></td>
          <td class="acoes">
            <a href="editar.php?id=<?= $p['id'] ?>" class="btn-acao editar">Editar</a>
            <a href="excluir.php?id=<?= $p['id'] ?>"
               class="btn-acao excluir"
               onclick="return confirm('Excluir projeto?')">
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
include "../../templates/template.php";
