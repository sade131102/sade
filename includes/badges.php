<?php

function concederBadge(PDO $pdo, int $usuario_id, string $codigoBadge) {

    $stmt = $pdo->prepare("
        SELECT id FROM badges WHERE codigo = ?
    ");
    $stmt->execute([$codigoBadge]);
    $badge = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$badge) return;

    $stmt = $pdo->prepare("
        INSERT IGNORE INTO badges_usuario (usuario_id, badge_id)
        VALUES (?, ?)
    ");
    $stmt->execute([$usuario_id, $badge['id']]);
}
