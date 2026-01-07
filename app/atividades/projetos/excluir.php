<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require "../../../app/Services/ProjetoService.php";

$id = (int) ($_GET['id'] ?? 0);

if ($id) {
  $service = new ProjetoService($pdo);
  $service->excluir($id);
}

header("Location: listar.php");
exit;
