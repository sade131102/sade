<?php

class ProjetoService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       LISTAR
       ===================================================== */

    public function listar(): array
    {
        $sql = "
            SELECT
                id,
                titulo,
                tipo,
                status,
                data_inicio
            FROM projetos
            ORDER BY data_inicio DESC, titulo
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       BUSCAR
       ===================================================== */

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                id,
                titulo,
                tipo,
                status,
                data_inicio,
                objetivo,
                banca_organizadora,
                edital_link,
                cargo_ou_vaga,
                pontuacao_total,
                nota_corte,
                classificacao_final,
                notas_por_materia,
                metodologia,
                progresso_percentual,
                producao_cientifica
            FROM projetos
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);
        $projeto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $projeto ?: null;
    }

    /* =====================================================
       CRIAR
       ===================================================== */

    public function criar(array $dados): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO projetos (
                titulo,
                tipo,
                status,
                data_inicio,
                objetivo
            ) VALUES (
                :titulo,
                :tipo,
                :status,
                :data_inicio,
                :objetivo
            )
        ");

        $stmt->execute([
            'titulo'      => $dados['titulo'],
            'tipo'        => $dados['tipo'],
            'status'      => $dados['status'] ?? 'Planejado',
            'data_inicio' => $dados['data_inicio'],
            'objetivo'    => $dados['objetivo'] ?? null
        ]);
    }

    /* =====================================================
       ATUALIZAR
       ===================================================== */

    public function atualizar(int $id, array $dados): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE projetos SET
                titulo       = :titulo,
                tipo         = :tipo,
                status       = :status,
                data_inicio  = :data_inicio,
                objetivo     = :objetivo,
                updated_at   = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'id'          => $id,
            'titulo'      => $dados['titulo'],
            'tipo'        => $dados['tipo'],
            'status'      => $dados['status'],
            'data_inicio' => $dados['data_inicio'],
            'objetivo'    => $dados['objetivo'] ?? null
        ]);
    }

    /* =====================================================
       EXCLUIR
       ===================================================== */

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM projetos WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
