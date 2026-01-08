<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/app/templates/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id        = $_POST['id'] ?? null;
$titulo    = trim($_POST['titulo'] ?? '');
$autor     = trim($_POST['autor'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$conteudo  = trim($_POST['conteudo'] ?? '');
$status    = $_POST['status'] ?? 'rascunho';
$imagem    = $_POST['imagem'] ?? null;

if ($titulo === '' || $autor === '' || $categoria === '' || $conteudo === '') {
    die('Campos obrigatórios não preenchidos.');
}

/* slug base */
$slugBase = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $titulo), '-'));

/* =====================================================
   ATUALIZAÇÃO DE POST EXISTENTE
   ===================================================== */
if ($id) {

    $stmt = $pdo->prepare("
        SELECT slug, publicado_em
        FROM blog_posts
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $postAtual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$postAtual) {
        die('Post não encontrado.');
    }

    // mantém slug se o título não mudou
    $slug = ($postAtual['slug'] === $slugBase)
        ? $postAtual['slug']
        : $slugBase;

    // regra de publicado_em
    if ($status === 'publicado' && !$postAtual['publicado_em']) {
        $publicadoEm = date('Y-m-d H:i:s');
    } elseif ($status !== 'publicado') {
        $publicadoEm = null;
    } else {
        $publicadoEm = $postAtual['publicado_em'];
    }

    $stmt = $pdo->prepare("
        UPDATE blog_posts SET
            titulo = ?,
            slug = ?,
            autor = ?,
            categoria = ?,
            conteudo = ?,
            status = ?,
            imagem = ?,
            publicado_em = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $titulo,
        $slug,
        $autor,
        $categoria,
        $conteudo,
        $status,
        $imagem,
        $publicadoEm,
        $id
    ]);
}

/* =====================================================
   CRIAÇÃO DE NOVO POST
   ===================================================== */
else {

    $slug = $slugBase;

    // garante slug único
    $contador = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() == 0) {
            break;
        }
        $slug = $slugBase . '-' . $contador;
        $contador++;
    }

    $publicadoEm = ($status === 'publicado')
        ? date('Y-m-d H:i:s')
        : null;

    $stmt = $pdo->prepare("
        INSERT INTO blog_posts
        (titulo, slug, autor, categoria, conteudo, status, imagem, publicado_em)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $titulo,
        $slug,
        $autor,
        $categoria,
        $conteudo,
        $status,
        $imagem,
        $publicadoEm
    ]);
}

header('Location: index.php');
exit;
