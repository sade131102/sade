<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/app/templates/auth.php';

$id       = $_POST['id'] ?? null;
$titulo   = $_POST['titulo'];
$autor    = $_POST['autor'];
$categoria= $_POST['categoria'];
$conteudo = $_POST['conteudo'];
$status   = $_POST['status'];
$imagem   = $_POST['imagem'];

$slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $titulo)));

if ($id) {
  $stmt = $pdo->prepare("
    UPDATE blog_posts
    SET titulo=?, slug=?, autor=?, categoria=?, conteudo=?, status=?, imagem=?
    WHERE id=?
  ");
  $stmt->execute([$titulo,$slug,$autor,$categoria,$conteudo,$status,$imagem,$id]);
} else {
  $stmt = $pdo->prepare("
    INSERT INTO blog_posts
    (titulo, slug, autor, categoria, conteudo, status, imagem, publicado_em)
    VALUES (?,?,?,?,?,?,?,NOW())
  ");
  $stmt->execute([$titulo,$slug,$autor,$categoria,$conteudo,$status,$imagem]);
}

header("Location: index.php");
exit;
