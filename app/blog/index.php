<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require_once APP_ROOT . '/app/templates/template.php';

$posts = $pdo->query("
  SELECT * FROM blog_posts
  WHERE status = 'publicado'
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
<div class="blog-actions">

  <a href="/controle_estudos/app/blog/admin/criar.php" class="action-card criar">
    <span class="icon">➕</span>
    <h3>Criar Post</h3>
  </a>

  <a href="/controle_estudos/app/blog/admin/editar.php" class="action-card editar">
    <span class="icon">✏️</span>
    <h3>Editar Post</h3>
  </a>

  <a href="/controle_estudos/app/blog/admin/salvar.php" class="action-card salvar">
    <span class="icon">💾</span>
    <h3>Salvar</h3>
  </a>

</div>

  <div class="blog-grid">

    <?php if (!empty($posts)): ?>
      <?php foreach ($posts as $post): ?>
        <article class="blog-card">

          <?php if (!empty($post['imagem'])): ?>
            <img 
              src="/controle_estudos/uploads/<?= htmlspecialchars($post['imagem']) ?>" 
              alt="<?= htmlspecialchars($post['titulo']) ?>"
            >
          <?php endif; ?>

          <div class="blog-content">

            <span class="blog-categoria cat-<?= htmlspecialchars($post['categoria']) ?>">
              <?= ucfirst(htmlspecialchars($post['categoria'])) ?>
            </span>

            <h2>
              <a href="post.php?slug=<?= urlencode($post['slug']) ?>">
                <?= htmlspecialchars($post['titulo']) ?>
              </a>
            </h2>

            <div class="blog-meta">
              <?= htmlspecialchars($post['autor']) ?> •
              <?= date('d/m/Y H:i', strtotime($post['publicado_em'])) ?>
            </div>

           
			<p class="blog-resumo">
			<?= htmlspecialchars(
			mb_strimwidth(
			strip_tags($post['conteudo']),
			0,
			280,
			'…',
			'UTF-8'
			)
			) ?>
			</p>

          </div>
        </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;">Nenhum post publicado até o momento.</p>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
