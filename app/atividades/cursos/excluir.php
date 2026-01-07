<?php
require "../../templates/auth.php";
require "../../../config/db.php";
require "../../../app/Services/CursoService.php";

$id = (int) ($_GET['id'] ?? 0);

if ($id) {
  $service = new CursoService($pdo);
  $service->excluir($id);
}

header("Location: listar.php");
exit;

