<?php

require_once __DIR__ . '/EstudoService.php';

class MetaService
{
    private PDO $pdopdo;
    private EstudoService $estudoService;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->estudoService = new EstudoService($pdo);
    }

    /**
     * Retorna as metas do usuário
     */
    public function obterMetasUsuario(int $usuarioId): array
    {
        $sql = "
            SELECT 
                meta_diaria,
                meta_semanal,
                meta_mensal
            FROM metas_usuario
            WHERE usuario_id = :usuario
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':usuario', $usuarioId);
        $stmt->execute();

        $metas = $stmt->fetch(PDO::FETCH_ASSOC);

        return $metas ?: [
            'meta_diaria' => 1,
            'meta_semanal' => 5,
            'meta_mensal' => 20
        ];
    }

    /**
     * Avalia o status da meta diária
     */
    public function statusMetaDiaria(int $usuarioId, string $data): array
    {
        $metas = $this->obterMetasUsuario($usuarioId);
        $realizado = $this->estudoService->totalHorasDia($data);

        return $this->avaliarStatus($realizado, $metas['meta_diaria']);
    }

    /**
     * Avalia o status da meta semanal
     */
    public function statusMetaSemanal(int $usuarioId, int $ano, int $semana): array
    {
        $metas = $this->obterMetasUsuario($usuarioId);
        $realizado = $this->estudoService->totalHorasSemana($ano, $semana);

        return $this->avaliarStatus($realizado, $metas['meta_semanal']);
    }

    /**
     * Avalia o status da meta mensal
     */
    public function statusMetaMensal(int $usuarioId, int $ano, int $mes): array
    {
        $metas = $this->obterMetasUsuario($usuarioId);
        $realizado = $this->estudoService->totalHorasMes($ano, $mes);

        return $this->avaliarStatus($realizado, $metas['meta_mensal']);
    }

    /**
     * Função central de avaliação de metas
     */
    private function avaliarStatus(float $realizado, float $meta): array
    {
        $percentual = ($meta > 0)
            ? round(($realizado / $meta) * 100, 2)
            : 0;

        if ($realizado >= $meta) {
            $status = 'atingida';
        } elseif ($realizado > 0) {
            $status = 'em_andamento';
        } else {
            $status = 'nao_iniciada';
        }

        return [
            'meta'        => $meta,
            'realizado'   => $realizado,
            'percentual'  => $percentual,
            'status'      => $status
        ];
    }
}
