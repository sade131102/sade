<?php
if (!isset($titulo))   $titulo   = 'SADE';
if (!isset($pagina))   $pagina   = '';
if (!isset($conteudo)) $conteudo = '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>

  <link rel="stylesheet" href="/controle_estudos/assets/css/base.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/layout.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/components.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/pages.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/header.css">
</head>

<body class="<?= htmlspecialchars($pagina) ?>">

  <?php include __DIR__ . '/header.php'; ?>

  <main class="container">
    <div class="page-wrapper">
      <?= $conteudo ?>
    </div>
  </main>

</body>
</html>
