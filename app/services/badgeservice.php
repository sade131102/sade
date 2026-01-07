<?php

require_once __DIR__ . '/EstudoService.php';
require_once __DIR__ . '/MetaService.php';

class BadgeService
{
    private PDO $pdo;
    private EstudoService $estudoService;
    private MetaService $metaService;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->estudoService = new EstudoService($pdo);
        $this->metaService   = new MetaService($pdo);
    }

    /**
     * Concede um badge ao usuário (se ainda não tiver)
     */
    public function concederBadge(int $usuarioId, string $codigo): bool
    {
        // Verifica se o badge existe
        $stmt = $this->pdo->prepare("SELECT id FROM badges WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $badgeId = $stmt->fetchColumn();

        if (!$badgeId) {
            return false; // badge inexistente
        }

        // Verifica se o usuário já possui o badge
        $stmt = $this->pdo->prepare("
            SELECT 1
            FROM badges_usuario
            WHERE usuario_id = ? AND badge_id = ?
        ");
        $stmt->execute([$usuarioId, $badgeId]);

        if ($stmt->fetchColumn()) {
            return false; // já concedido
        }

        // Concede o badge
        $stmt = $this->pdo->prepare("
            INSERT INTO badges_usuario (usuario_id, badge_id)
            VALUES (?, ?)
        ");
        return $stmt->execute([$usuarioId, $badgeId]);
    }

    /**
     * Avalia e concede badges automaticamente
     * Chamar após registrar estudo ou ao carregar o dashboard
     */
    public function avaliarBadges(int $usuarioId): void
    {
        $hoje   = date('Y-m-d');
        $ano    = (int) date('Y');
        $mes    = (int) date('m');
        $semana = (int) date('W');

        // Metas
        $metaDiaria  = $this->metaService->statusMetaDiaria($usuarioId, $hoje);
        $metaSemanal = $this->metaService->statusMetaSemanal($usuarioId, $ano, $semana);

        if ($metaDiaria['status'] === 'atingida') {
            $this->concederBadge($usuarioId, 'meta_diaria');
        }

        if ($metaSemanal['status'] === 'atingida') {
            $this->concederBadge($usuarioId, 'meta_semanal');
        }

        // Streaks
        $streak = $this->estudoService->melhorSequencia();

        if ($streak >= 3) {
            $this->concederBadge($usuarioId, 'streak_3');
        }

        if ($streak >= 7) {
            $this->concederBadge($usuarioId, 'streak_7');
        }

        if ($streak >= 30) {
            $this->concederBadge($usuarioId, 'streak_30');
        }
    }

    /**
     * Retorna badges do usuário
     */
    public function listarBadgesUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT b.codigo, b.nome, b.descricao, b.icone, bu.conquistado_em
            FROM badges_usuario bu
            INNER JOIN badges b ON b.id = bu.badge_id
            WHERE bu.usuario_id = ?
            ORDER BY bu.conquistado_em DESC
        ");
        $stmt->execute([$usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
