<?php
require "../../templates/auth.php";
require "../../../config/db.php";
require "../../../app/Services/ProjetoService.php";

$service = new ProjetoService($pdo);

$dados = [
  'titulo'      => $_POST['titulo'] ?? '',
  'tipo'        => $_POST['tipo'] ?? '',
  'status'      => $_POST['status'] ?? 'Planejado',
  'data_inicio' => $_POST['data_inicio'] ?? '',
  'objetivo'    => $_POST['objetivo'] ?? null
];

$id = $_POST['id'] ?? null;

if ($id) {
  $service->atualizar((int)$id, $dados);
} else {
  $service->criar($dados);
}

header("Location: listar.php");
exit;
