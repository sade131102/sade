<?php
$titulo = 'Editar Post';
$pagina = 'page-dashboard';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require_once APP_ROOT . '/app/templates/auth.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  header("Location: index.php");
  exit;
}

ob_start();
?>

<h1 class="titulo-central">Editar Post</h1>

<form method="post" action="salvar.php" class="card-crud formulario">

  <input type="hidden" name="id" value="<?= $post['id'] ?>">

  <label>Título</label>
  <input name="titulo" value="<?= htmlspecialchars($post['titulo']) ?>" required>

  <label>Autor</label>
  <input name="autor" value="<?= htmlspecialchars($post['autor']) ?>" required>

  <label>Categoria</label>
  <select name="categoria">
    <option value="pessoal" <?= $post['categoria']=='pessoal'?'selected':'' ?>>Área Pessoal</option>
    <option value="profissional" <?= $post['categoria']=='profissional'?'selected':'' ?>>Área Profissional</option>
    <option value="academica" <?= $post['categoria']=='academica'?'selected':'' ?>>Área Acadêmica</option>
    <option value="religiosa" <?= $post['categoria']=='religiosa'?'selected':'' ?>>Área Religiosa</option>
  </select>

  <label>Imagem destacada</label>
  <input name="imagem" value="<?= htmlspecialchars($post['imagem']) ?>">

  <label>Conteúdo</label>
  <textarea name="conteudo" rows="10"><?= htmlspecialchars($post['conteudo']) ?></textarea>

  <label>Status</label>
  <select name="status">
    <option value="rascunho" <?= $post['status']=='rascunho'?'selected':'' ?>>Rascunho</option>
    <option value="publicado" <?= $post['status']=='publicado'?'selected':'' ?>>Publicado</option>
  </select>

  <div class="acoes-form">
    <button class="btn btn-editar">Atualizar</button>
  </div>

</form>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
