<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require "../../app/Services/DashboardService.php";
require "../../app/Services/BadgeService.php";

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$usuarioId = (int) $_SESSION['usuario_id'];

$dashboardService = new DashboardService($pdo);
$badgeService = new BadgeService($pdo);

$dados = $dashboardService->dadosDashboard($usuarioId);

$horasHoje   = $dados['horas_hoje'];
$horasSemana = $dados['horas_semana'];
$streak      = $dados['sequencia'];

$titulo = 'Dashboard | SADE';
$pagina = 'page-dashboard';

ob_start();
?>

<section class="cards">
  <div class="card">
    <h4>Horas hoje</h4>
    <strong><?= number_format($horasHoje, 1, ',', '.') ?> h</strong>
  </div>

  <div class="card">
    <h4>Horas na semana</h4>
    <strong><?= number_format($horasSemana, 1, ',', '.') ?> h</strong>
  </div>

  <div class="card">
    <h4>🔥 Sequência</h4>
    <strong><?= $streak ?> dia<?= $streak == 1 ? '' : 's' ?></strong>
  </div>
</section>

<section class="card">
  <h3>Horas de estudo por mês</h3>
  <div class="grafico">
    <canvas id="graficoMensal"></canvas>
  </div>
</section>

<?php
$conteudo = ob_get_clean();

$conteudo .= '
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById("graficoMensal");
new Chart(ctx, {
  type: "bar",
  data: {
    labels: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
    datasets: [{
      label: "Horas",
      data: [0,0,0,0,0,0,0,0,0,0,0,0]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});
</script>
';

require_once APP_ROOT . '/app/templates/template.php';
