<?php

require_once __DIR__ . '/EstudoService.php';
require_once __DIR__ . '/AtividadeService.php';
require_once __DIR__ . '/MetaService.php';

class DashboardService
{
    private PDO $pdo;
    private EstudoService $estudoService;
    private AtividadeService $atividadeService;
    private MetaService $metaService;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->estudoService = new EstudoService($pdo);
        $this->atividadeService = new AtividadeService($pdo);
        $this->metaService = new MetaService($pdo);
    }

    /**
     * Retorna todos os dados necessários para o Dashboard
     */
    public function dadosDashboard(int $usuarioId): array
    {
        $hoje   = date('Y-m-d');
        $ano    = (int) date('Y');
        $mes    = (int) date('m');
        $semana = (int) date('W');

        $horasHoje   = $this->estudoService->totalHorasDia($hoje);
        $horasSemana = $this->estudoService->totalHorasSemana($ano, $semana);
        $horasMes    = $this->estudoService->totalHorasMes($ano, $mes);

        $metaDiaria  = $this->metaService->statusMetaDiaria($usuarioId, $hoje);
        $metaSemanal = $this->metaService->statusMetaSemanal($usuarioId, $ano, $semana);
        $metaMensal  = $this->metaService->statusMetaMensal($usuarioId, $ano, $mes);

        return [
            // horas
            'horas_hoje'   => $horasHoje,
            'horas_semana' => $horasSemana,
            'horas_mes'    => $horasMes,

            // metas
            'meta_diaria'  => $metaDiaria,
            'meta_semanal' => $metaSemanal,
            'meta_mensal'  => $metaMensal,

            // desempenho
            'sequencia'    => $this->estudoService->melhorSequencia(),
            'presencas'    => $this->estudoService->resumoPresencas($ano),

            // atividades
            'atividades'   => $this->atividadeService->listarAtividadesComResumo(),
        ];
    }

    /**
     * Dados resumidos para cards rápidos
     */
    public function cardsDashboard(int $usuarioId): array
    {
        $dados = $this->dadosDashboard($usuarioId);

        return [
            'horas_hoje'      => $dados['horas_hoje'],
            'meta_diaria_ok'  => $dados['meta_diaria']['status'] === 'atingida',
            'meta_semana_ok'  => $dados['meta_semanal']['status'] === 'atingida',
            'sequencia'       => $dados['sequencia'],
            'total_atividades'=> count($dados['atividades']),
        ];
    }
}
