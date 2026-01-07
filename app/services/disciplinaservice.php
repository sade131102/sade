<?php

class DisciplinaService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       LISTAR
       ===================================================== */

    // Lista TODAS as disciplinas (com nome do curso)
    public function listar(): array
    {
        $sql = "
            SELECT 
                d.id,
                d.titulo,
                d.codigo,
                d.carga_horaria,
                c.titulo AS curso
            FROM disciplinas d
            INNER JOIN cursos c ON c.id = d.curso_id
            ORDER BY c.titulo, d.titulo
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista disciplinas de UM curso específico
    public function listarPorCurso(int $cursoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, titulo, codigo, carga_horaria
            FROM disciplinas
            WHERE curso_id = :curso
            ORDER BY titulo
        ");

        $stmt->execute(['curso' => $cursoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       BUSCAR
       ===================================================== */

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                id,
                curso_id,
                titulo,
                codigo,
                carga_horaria,
                ementa,
                bibliografia,
                professor
            FROM disciplinas
            WHERE id = :id
        ");

        $stmt->execute(['id' => $id]);
        $disciplina = $stmt->fetch(PDO::FETCH_ASSOC);

        return $disciplina ?: null;
    }

    /* =====================================================
       CRIAR
       ===================================================== */

    public function criar(array $dados): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO disciplinas (
                curso_id,
                titulo,
                codigo,
                carga_horaria,
                ementa,
                bibliografia,
                professor
            ) VALUES (
                :curso,
                :titulo,
                :codigo,
                :carga,
                :ementa,
                :bibliografia,
                :professor
            )
        ");

        $stmt->execute([
            'curso'        => $dados['curso_id'],
            'titulo'       => $dados['titulo'],
            'codigo'       => $dados['codigo'] ?? null,
            'carga'        => $dados['carga_horaria'] ?? null,
            'ementa'       => $dados['ementa'] ?? null,
            'bibliografia' => $dados['bibliografia'] ?? null,
            'professor'    => $dados['professor'] ?? null
        ]);
    }

    /* =====================================================
       ATUALIZAR
       ===================================================== */

    public function atualizar(int $id, array $dados): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE disciplinas SET
                curso_id      = :curso,
                titulo        = :titulo,
                codigo        = :codigo,
                carga_horaria = :carga,
                ementa        = :ementa,
                bibliografia  = :bibliografia,
                professor     = :professor,
                updated_at    = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'id'           => $id,
            'curso'        => $dados['curso_id'],
            'titulo'       => $dados['titulo'],
            'codigo'       => $dados['codigo'] ?? null,
            'carga'        => $dados['carga_horaria'] ?? null,
            'ementa'       => $dados['ementa'] ?? null,
            'bibliografia' => $dados['bibliografia'] ?? null,
            'professor'    => $dados['professor'] ?? null
        ]);
    }

    /* =====================================================
       EXCLUIR
       ===================================================== */

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM disciplinas WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
