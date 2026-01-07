<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$service = new DisciplinaService($pdo);

$id   = $_POST['id']   ?? null;
$nome = trim($_POST['nome'] ?? '');

if ($nome === '') {
  header("Location: listar.php");
  exit;
}

if ($id) {
  $service->atualizar($id, $nome);
} else {
  $service->criar($nome);
}

header("Location: listar.php");
exit;
