<?php
$titulo = 'Blog – Administração';
$pagina = 'page-dashboard';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require_once APP_ROOT . '/app/templates/auth.php';

$posts = $pdo->query("
  SELECT * FROM blog_posts
  ORDER BY criado_em DESC
")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h1 class="titulo-central">Blog do SGP</h1>
<p class="subtitulo-central">Gerenciamento de publicações</p>

<div class="acoes-topo">
  <a href="criar.php" class="btn btn-cadastrar">Novo Post</a>
</div>

<table class="tabela">
  <thead>
    <tr>
      <th>Título</th>
      <th>Categoria</th>
      <th>Status</th>
      <th>Publicado em</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($posts as $post): ?>
    <tr>
      <td><?= htmlspecialchars($post['titulo']) ?></td>
      <td><?= ucfirst($post['categoria']) ?></td>
      <td><?= ucfirst($post['status']) ?></td>
      <td>
        <?= $post['publicado_em']
          ? date('d/m/Y H:i', strtotime($post['publicado_em']))
          : '-' ?>
      </td>
      <td class="acoes">
        <a href="editar.php?id=<?= $post['id'] ?>" class="btn-acao editar">Editar</a>
      </td>
    </tr>
  <?php endforeach; ?>

  </tbody>
</table>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
