<?php
$titulo = 'Registrar Horas de Estudo';
$pagina = 'page-form';


require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require "../../app/Services/ProjetoService.php";
require "../../app/Services/EstudoService.php";

$disciplinaService = new DisciplinaService($pdo);
$projetoService    = new ProjetoService($pdo);

$disciplinas = $disciplinaService->listar();
$projetos    = $projetoService->listar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $dados = [
    'disciplina_id' => $_POST['disciplina_id'] ?: null,
    'projeto_id'    => $_POST['projeto_id'] ?: null,
    'data_estudo'   => $_POST['data_estudo'],
    'horas'         => $_POST['horas'],
    'descricao'     => $_POST['descricao'] ?? null
  ];

  // Validação lógica
  if (!$dados['disciplina_id'] && !$dados['projeto_id']) {
    $erro = 'Selecione uma disciplina OU um projeto.';
  } else {
    $service = new EstudoService($pdo);
    $service->criar($dados);
    header("Location: registrar.php?ok=1");
    exit;
  }
}

ob_start();
?>

<h1 class="titulo-central">Registrar Horas de Estudo</h1>
<p class="subtitulo-central">Controle seu tempo de estudo por disciplina ou projeto</p>

<?php if (!empty($erro)): ?>
  <div class="card" style="background:#fee2e2;color:#991b1b;margin-bottom:20px;">
    <?= $erro ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['ok'])): ?>
  <div class="card" style="background:#dcfce7;color:#166534;margin-bottom:20px;">
    Horas registradas com sucesso!
  </div>
<?php endif; ?>

<div class="card formulario">
  <form method="post">

    <div class="linha">
      <div class="grupo">
        <label>Disciplina (opcional)</label>
        <select name="disciplina_id">
          <option value="">— Nenhuma —</option>
          <?php foreach ($disciplinas as $d): ?>
            <option value="<?= $d['id'] ?>">
              <?= htmlspecialchars($d['curso'].' — '.$d['titulo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grupo">
        <label>Projeto (opcional)</label>
        <select name="projeto_id">
          <option value="">— Nenhum —</option>
          <?php foreach ($projetos as $p): ?>
            <option value="<?= $p['id'] ?>">
              <?= htmlspecialchars($p['titulo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="linha">
      <div class="grupo">
        <label>Data do estudo</label>
        <input type="date" name="data_estudo" required value="<?= date('Y-m-d') ?>">
      </div>

      <div class="grupo">
        <label>Horas estudadas</label>
        <input type="number" name="horas" step="0.25" min="0.25" placeholder="Ex: 1.5" required>
      </div>
    </div>

    <div class="grupo">
      <label>Descrição / Conteúdo estudado</label>
      <textarea name="descricao" placeholder="Ex: Capítulo 3 do livro, revisão de exercícios..."></textarea>
    </div>

    <div class="acoes-form">
      <button class="btn btn-cadastrar">Registrar</button>
      <a href="../dashboard/dashboard.php" class="btn btn-listar">Voltar</a>
    </div>

  </form>
</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
