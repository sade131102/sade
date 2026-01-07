<?php
require "../../templates/auth.php";
require "../../../config/db.php";
require "../../../app/Services/DisciplinaService.php";

$id = (int) ($_GET['id'] ?? 0);

$service = new DisciplinaService($pdo);
$service->excluir($id);

header("Location: listar.php");
exit;
