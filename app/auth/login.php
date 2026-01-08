<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';

session_start();

/* inicializa para evitar warning */
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: /controle_estudos/app/dashboard/boas_vindas.php");
        exit;
    }

    $erro = 'Login inválido';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login</title>

  <link rel="stylesheet" href="/controle_estudos/assets/css/base.css">
  <link rel="stylesheet" href="/controle_estudos/assets/css/login.css">
</head>
<body>
<div class="box login-box">

  <img src="../../assets/img/sgp1.png"
       alt="Logo do sistema"
       class="login-logo">

  <h2>Sistema de Gestão Pessoal</h2>

  <form method="post">
    <h2>Acesse o Sistema</h2>

    <?php if ($erro): ?>
      <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <input name="email" type="email" placeholder="E-mail" required>
    <input name="senha" type="password" placeholder="Senha" required>
    <button type="submit">Entrar</button>
  </form>

</div>
</body>
</html>
