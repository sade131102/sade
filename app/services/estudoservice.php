<?php

class EstudoService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       NOVO MODELO — REGISTRO DE HORAS
    ===================================================== */

    public function criar(array $dados): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO estudos (
                disciplina_id,
                projeto_id,
                data,
                horas,
                conteudo,
                ano_letivo
            ) VALUES (
                :disciplina,
                :projeto,
                :data,
                :horas,
                :conteudo,
                :ano
            )
        ");

        $stmt->execute([
            'disciplina' => $dados['disciplina_id'] ?? null,
            'projeto'    => $dados['projeto_id'] ?? null,
            'data'       => $dados['data_estudo'],
            'horas'      => $dados['horas'],
            'conteudo'   => $dados['descricao'] ?? null,
            'ano'        => date('Y')
        ]);
    }

    /* =====================================================
       LISTAGEM UNIFICADA
    ===================================================== */

    public function listar(): array
    {
        $sql = "
            SELECT
                e.id,
                e.data,
                e.horas,
                e.conteudo,
                d.titulo AS disciplina,
                p.titulo AS projeto,
                e.atividade_id,
                e.presenca
            FROM estudos e
            LEFT JOIN disciplinas d ON d.id = e.disciplina_id
            LEFT JOIN projetos p ON p.id = e.projeto_id
            ORDER BY e.data DESC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       DASHBOARD — TOTAIS DE HORAS
    ===================================================== */

    public function totalHorasDia(string $data): float
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(horas), 0)
            FROM estudos
            WHERE data = :data
        ");
        $stmt->execute(['data' => $data]);
        return (float) $stmt->fetchColumn();
    }

    public function totalHorasSemana(int $ano, int $semana): float
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(horas), 0)
            FROM estudos
            WHERE YEAR(data) = :ano
              AND WEEK(data, 1) = :semana
        ");
        $stmt->execute([
            'ano'    => $ano,
            'semana' => $semana
        ]);
        return (float) $stmt->fetchColumn();
    }

    public function totalHorasMes(int $ano, int $mes): float
    {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(horas), 0)
            FROM estudos
            WHERE YEAR(data) = :ano
              AND MONTH(data) = :mes
        ");
        $stmt->execute([
            'ano' => $ano,
            'mes' => $mes
        ]);
        return (float) $stmt->fetchColumn();
    }

    /* =====================================================
       DESEMPENHO
    ===================================================== */

    public function melhorSequencia(): int
    {
        $datas = $this->pdo->query("
            SELECT DISTINCT data
            FROM estudos
            ORDER BY data ASC
        ")->fetchAll(PDO::FETCH_COLUMN);

        $recorde = 0;
        $atual   = 0;
        $anterior = null;

        foreach ($datas as $data) {
            if ($anterior && date('Y-m-d', strtotime($anterior . ' +1 day')) === $data) {
                $atual++;
            } else {
                $atual = 1;
            }
            $recorde = max($recorde, $atual);
            $anterior = $data;
        }

        return $recorde;
    }

    public function resumoPresencas(int $ano): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(*) AS total,
                SUM(presenca = 1) AS presentes,
                SUM(presenca = 0) AS ausentes
            FROM estudos
            WHERE ano_letivo = :ano
        ");
        $stmt->execute(['ano' => $ano]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total'     => 0,
            'presentes' => 0,
            'ausentes'  => 0
        ];
    }

    /* =====================================================
       RELATÓRIOS
    ===================================================== */

    public function horasPorDisciplina(): array
    {
        $sql = "
            SELECT
                d.titulo,
                SUM(e.horas) AS total_horas
            FROM estudos e
            INNER JOIN disciplinas d ON d.id = e.disciplina_id
            WHERE e.horas IS NOT NULL
            GROUP BY d.id
            ORDER BY total_horas DESC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function horasPorProjeto(): array
    {
        $sql = "
            SELECT
                p.titulo,
                SUM(e.horas) AS total_horas
            FROM estudos e
            INNER JOIN projetos p ON p.id = e.projeto_id
            WHERE e.horas IS NOT NULL
            GROUP BY p.id
            ORDER BY total_horas DESC
        ";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       MODO ANTIGO — FREQUÊNCIA / ATIVIDADE
    ===================================================== */

    public function registrarAtividade(array $dados): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO estudos (
                atividade_id,
                data,
                hora_inicio,
                hora_fim,
                presenca,
                conteudo,
                ano_letivo
            ) VALUES (
                :atividade,
                :data,
                :inicio,
                :fim,
                :presenca,
                :conteudo,
                :ano
            )
        ");

        $stmt->execute([
            'atividade' => $dados['atividade_id'],
            'data'      => $dados['data'],
            'inicio'    => $dados['hora_inicio'],
            'fim'       => $dados['hora_fim'],
            'presenca'  => $dados['presenca'],
            'conteudo'  => $dados['conteudo'] ?? null,
            'ano'       => $dados['ano_letivo']
        ]);
    }
}
