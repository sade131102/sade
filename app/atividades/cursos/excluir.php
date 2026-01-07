<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id) {
  $service = new CursoService($pdo);
  $service->excluir($id);
}

header("Location: listar.php");
exit;

