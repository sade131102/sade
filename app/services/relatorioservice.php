<?php

require_once __DIR__ . '/EstudoService.php';
require_once __DIR__ . '/AtividadeService.php';
require_once __DIR__ . '/MetaService.php';

class RelatorioService
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
     * Relatório geral de estudos por período letivo
     */
    public function relatorioEstudosAno(int $anoLetivo): array
    {
        return [
            'ano_letivo'     => $anoLetivo,
            'horas_por_atividade' => $this->estudoService->horasPorAtividade($anoLetivo),
            'presencas'      => $this->estudoService->resumoPresencas($anoLetivo),
            'total_horas'    => $this->estudoService->totalHorasMes($anoLetivo, 1)
                               + $this->estudoService->totalHorasMes($anoLetivo, 2)
                               + $this->estudoService->totalHorasMes($anoLetivo, 3)
                               + $this->estudoService->totalHorasMes($anoLetivo, 4)
                               + $this->estudoService->totalHorasMes($anoLetivo, 5)
                               + $this->estudoService->totalHorasMes($anoLetivo, 6)
                               + $this->estudoService->totalHorasMes($anoLetivo, 7)
                               + $this->estudoService->totalHorasMes($anoLetivo, 8)
                               + $this->estudoService->totalHorasMes($anoLetivo, 9)
                               + $this->estudoService->totalHorasMes($anoLetivo, 10)
                               + $this->estudoService->totalHorasMes($anoLetivo, 11)
                               + $this->estudoService->totalHorasMes($anoLetivo, 12),
        ];
    }

    /**
     * Relatório detalhado de uma atividade
     */
    public function relatorioAtividade(int $atividadeId): array
    {
        return [
            'atividade' => $this->atividadeService->listarAtividadesComResumo(),
            'carga_realizada' => $this->atividadeService->cargaRealizada($atividadeId),
            'carga_faltante'  => $this->atividadeService->cargaFaltante($atividadeId),
            'percentual'      => $this->atividadeService->percentualCumprimento($atividadeId),
            'status'          => $this->atividadeService->statusAtividade($atividadeId)
        ];
    }

    /**
     * Relatório de metas por período
     */
    public function relatorioMetas(
        int $usuarioId,
        int $ano,
        int $mes,
        int $semana
    ): array {
        return [
            'meta_diaria'  => $this->metaService->statusMetaDiaria($usuarioId, date('Y-m-d')),
            'meta_semanal' => $this->metaService->statusMetaSemanal($usuarioId, $ano, $semana),
            'meta_mensal'  => $this->metaService->statusMetaMensal($usuarioId, $ano, $mes)
        ];
    }

    /**
     * Dados consolidados para o PDF do Dashboard
     */
    public function relatorioDashboard(int $usuarioId): array
    {
        $ano = (int) date('Y');
        $mes = (int) date('m');
        $semana = (int) date('W');

        return [
            'horas_hoje'   => $this->estudoService->totalHorasDia(date('Y-m-d')),
            'horas_semana' => $this->estudoService->totalHorasSemana($ano, $semana),
            'horas_mes'    => $this->estudoService->totalHorasMes($ano, $mes),
            'sequencia'    => $this->estudoService->melhorSequencia(),
            'atividades'   => $this->atividadeService->listarAtividadesComResumo(),
            'metas'        => $this->relatorioMetas($usuarioId, $ano, $mes, $semana)
        ];
    }
}
