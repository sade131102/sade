<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
  SELECT * FROM blog_posts
  WHERE slug=? AND status='publicado'
");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  echo "Post não encontrado.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['titulo']) ?> – SGP</title>
  <link rel="stylesheet" href="/controle_estudos/assets/css/base.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/blog.css">
</head>
<body>

<div class="blog-container">

<article class="blog-card">

<?php if ($post['imagem']): ?>
  <img src="/controle_estudos/uploads/<?= htmlspecialchars($post['imagem']) ?>">
<?php endif; ?>

<div class="blog-content">

  <span class="blog-categoria cat-<?= $post['categoria'] ?>">
    <?= ucfirst($post['categoria']) ?>
  </span>

  <h2><?= htmlspecialchars($post['titulo']) ?></h2>

  <div class="blog-meta">
    <?= htmlspecialchars($post['autor']) ?> •
    <?= date('d/m/Y', strtotime($post['publicado_em'])) ?> às
    <?= date('H:i', strtotime($post['publicado_em'])) ?>
  </div>

  <div class="blog-resumo">
    <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
  </div>

</div>
</article>

</div>
</body>
</html>
