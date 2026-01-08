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

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die('ID inválido.');
}

// (opcional) verifica se o post existe
$stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);

if (!$stmt->fetch()) {
    die('Post não encontrado.');
}

// exclui definitivamente
$stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
