<?php
session_start();
require "../../config/db.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$atividades = $pdo->query("
    SELECT id, nome, tipo
    FROM atividades
    ORDER BY tipo, nome
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cronômetro de Estudo</title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<header>
  Cronômetro de Estudo
  <a href="../dashboard/dashboard.php">Voltar</a>
</header>

<main>
<form id="formCronometro">

  <label>Atividade</label>
  <select id="atividade" name="atividade_id" required>
    <option value="">Selecione</option>
    <?php foreach ($atividades as $a): ?>
      <option value="<?= $a['id'] ?>">
        <?= ucfirst($a['tipo']) ?> — <?= htmlspecialchars($a['nome']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Ano letivo</label>
  <input type="number" name="ano_letivo" value="<?= date('Y') ?>" required>

  <div id="display">0</div>
  <button type="button" onclick="iniciar()">Iniciar</button>
  <button type="button" onclick="pausar()" id="btnPausar" disabled>Pausar</button>
  <button type="button" onclick="parar()" id="btnParar" disabled>Parar</button>
</form>

<script src="../../assets/js/cronometro.js"></script>

</section>

</main>

<script src="../../assets/js/cronometro.js"></script>
</body>
</html>
