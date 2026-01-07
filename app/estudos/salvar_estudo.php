<?php
session_start();
require "../../config/db.php";
require "../../app/Services/BadgeService.php";

$badgeService = new BadgeService($pdo);
$badgeService->avaliarBadges($_SESSION['usuario_id']);


if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['mensagem' => 'Acesso negado']);
    exit;
}

$sql = $pdo->prepare("
    INSERT INTO estudos
    (atividade_id, data, hora_inicio, hora_fim, presenca, conteudo, ano_letivo)
    VALUES (?, ?, ?, ?, 'Presença', ?, ?)
");

$sql->execute([
    $_POST['atividade_id'],
    $_POST['data'],
    $_POST['hora_inicio'],
    $_POST['hora_fim'],
    $_POST['conteudo'] ?? '',
    $_POST['ano_letivo']
]);

echo json_encode(['mensagem' => 'Estudo registrado com sucesso!']);

