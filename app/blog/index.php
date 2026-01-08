<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';

$posts = $pdo->query("
  SELECT * FROM blog_posts
  WHERE status='publicado'
  ORDER BY publicado_em DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Blog – SGP</title>
  <link rel="stylesheet" href="/controle_estudos/assets/css/base.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/blog.css">
</head>
<body>

<div class="blog-container">

<h1 class="titulo-central">Blog do SGP</h1>

<?php foreach ($posts as $post): ?>
<article class="blog-card">

  <?php if ($post['imagem']): ?>
    <img src="/controle_estudos/uploads/<?= htmlspecialchars($post['imagem']) ?>">
  <?php endif; ?>

  <div class="blog-content">
    <span class="blog-categoria cat-<?= $post['categoria'] ?>">
      <?= ucfirst($post['categoria']) ?>
    </span>

    <h2>
      <a href="post.php?slug=<?= $post['slug'] ?>">
        <?= htmlspecialchars($post['titulo']) ?>
      </a>
    </h2>

    <div class="blog-meta">
      <?= htmlspecialchars($post['autor']) ?> •
      <?= date('d/m/Y H:i', strtotime($post['publicado_em'])) ?>
    </div>

    <p class="blog-resumo">
      <?= nl2br(substr($post['conteudo'],0,280)) ?>...
    </p>
  </div>

</article>
<?php endforeach; ?>

</div>
</body>
</html>
