<?php

class AtividadeService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lista todas as atividades com dados básicos
     */
    public function listarAtividades(): array
    {
        $sql = "
            SELECT 
                id,
                tipo,
                nome,
                instituicao,
                finalidade,
                carga_prevista,
                ementa
            FROM atividades
            ORDER BY tipo, nome
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcula a carga horária realizada de uma atividade
     */
    public function cargaRealizada(int $atividadeId): float
    {
        $sql = "
            SELECT SUM(
                TIMESTAMPDIFF(MINUTE, hora_inicio, hora_fim)
            ) AS minutos
            FROM estudos
            WHERE atividade_id = :id
              AND presenca = 'Presença'
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $atividadeId);
        $stmt->execute();

        $minutos = $stmt->fetchColumn();
        return round(($minutos ?? 0) / 60, 2);
    }

    /**
     * Retorna a carga horária faltante de uma atividade
     */
    public function cargaFaltante(int $atividadeId): float
    {
        $sql = "
            SELECT carga_prevista
            FROM atividades
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $atividadeId);
        $stmt->execute();

        $cargaPrevista = (float) $stmt->fetchColumn();
        $realizada = $this->cargaRealizada($atividadeId);

        return max($cargaPrevista - $realizada, 0);
    }

    /**
     * Calcula o percentual de cumprimento da atividade
     */
    public function percentualCumprimento(int $atividadeId): float
    {
        $sql = "
            SELECT carga_prevista
            FROM atividades
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $atividadeId);
        $stmt->execute();

        $cargaPrevista = (float) $stmt->fetchColumn();

        if ($cargaPrevista <= 0) {
            return 0;
        }

        $realizada = $this->cargaRealizada($atividadeId);
        return round(($realizada / $cargaPrevista) * 100, 2);
    }

    /**
     * Retorna o status da atividade com base no cumprimento
     */
    public function statusAtividade(int $atividadeId): string
    {
        $percentual = $this->percentualCumprimento($atividadeId);

        if ($percentual >= 100) {
            return 'Concluída';
        }

        if ($percentual > 0) {
            return 'Em andamento';
        }

        return 'Não iniciada';
    }

    /**
     * Retorna todas as atividades com métricas calculadas
     * Ideal para telas de listagem
     */
    public function listarAtividadesComResumo(): array
    {
        $atividades = $this->listarAtividades();

        foreach ($atividades as &$atividade) {
            $id = (int) $atividade['id'];

            $atividade['carga_realizada'] = $this->cargaRealizada($id);
            $atividade['carga_faltante']  = $this->cargaFaltante($id);
            $atividade['percentual']      = $this->percentualCumprimento($id);
            $atividade['status']          = $this->statusAtividade($id);
        }

        return $atividades;
    }
}
