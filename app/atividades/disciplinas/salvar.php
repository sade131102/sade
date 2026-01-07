<?php
require "../../templates/auth.php";
require "../../../config/db.php";
require "../../../app/Services/DisciplinaService.php";

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
