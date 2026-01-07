<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';

$service = new CursoService($pdo);

$dados = [
  'titulo'                 => $_POST['titulo'] ?? '',
  'tipo'                   => $_POST['tipo'] ?? '',
  'instituicao'            => $_POST['instituicao'] ?? '',
  'data_inicio'            => $_POST['data_inicio'] ?? '',
  'data_previsao_termino'  => $_POST['data_previsao_termino'] ?? null,
  'status'                 => $_POST['status'] ?? 'Em andamento'
];

$id = $_POST['id'] ?? null;

if ($id) {
  $service->atualizar((int)$id, $dados);
} else {
  $service->criar($dados);
}

header("Location: listar.php");
exit;
