<?php

class CursoService
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
                instituicao,
                status
            FROM cursos
            ORDER BY titulo
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
                instituicao,
                data_inicio,
                data_previsao_termino,
                data_conclusao,
                status,
                carga_horaria_total,
                titulo_obtido,
                tcc_titulo,
                tcc_orientador,
                tcc_nota,
                coeficiente_rendimento,
                diploma_link
            FROM cursos
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);
        $curso = $stmt->fetch(PDO::FETCH_ASSOC);

        return $curso ?: null;
    }

    /* =====================================================
       CRIAR
       ===================================================== */

    public function criar(array $dados): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO cursos (
                titulo,
                tipo,
                instituicao,
                data_inicio,
                data_previsao_termino,
                status
            ) VALUES (
                :titulo,
                :tipo,
                :instituicao,
                :data_inicio,
                :data_previsao,
                :status
            )
        ");

        $stmt->execute([
            'titulo'        => $dados['titulo'],
            'tipo'          => $dados['tipo'],
            'instituicao'   => $dados['instituicao'],
            'data_inicio'   => $dados['data_inicio'],
            'data_previsao' => $dados['data_previsao_termino'] ?? null,
            'status'        => $dados['status'] ?? 'Em andamento'
        ]);
    }

    /* =====================================================
       ATUALIZAR
       ===================================================== */

    public function atualizar(int $id, array $dados): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE cursos SET
                titulo                   = :titulo,
                tipo                     = :tipo,
                instituicao              = :instituicao,
                data_inicio              = :data_inicio,
                data_previsao_termino    = :data_previsao,
                data_conclusao           = :data_conclusao,
                status                   = :status,
                carga_horaria_total      = :carga,
                titulo_obtido             = :titulo_obtido,
                tcc_titulo               = :tcc_titulo,
                tcc_orientador           = :tcc_orientador,
                tcc_nota                 = :tcc_nota,
                coeficiente_rendimento   = :cr,
                diploma_link             = :diploma,
                updated_at               = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'id'              => $id,
            'titulo'          => $dados['titulo'],
            'tipo'            => $dados['tipo'],
            'instituicao'     => $dados['instituicao'],
            'data_inicio'     => $dados['data_inicio'],
            'data_previsao'   => $dados['data_previsao_termino'] ?? null,
            'data_conclusao'  => $dados['data_conclusao'] ?? null,
            'status'          => $dados['status'],
            'carga'           => $dados['carga_horaria_total'] ?? null,
            'titulo_obtido'   => $dados['titulo_obtido'] ?? null,
            'tcc_titulo'      => $dados['tcc_titulo'] ?? null,
            'tcc_orientador'  => $dados['tcc_orientador'] ?? null,
            'tcc_nota'        => $dados['tcc_nota'] ?? null,
            'cr'              => $dados['coeficiente_rendimento'] ?? null,
            'diploma'         => $dados['diploma_link'] ?? null
        ]);
    }

    /* =====================================================
       EXCLUIR
       ===================================================== */

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM cursos WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
