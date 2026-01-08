<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/db.php';

$erro = "";

if ($_POST) {
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=?");
    $sql->execute([$_POST['email']]);
    $u = $sql->fetch();

    if ($u && password_verify($_POST['senha'], $u['senha'])) {
        $_SESSION['usuario_id'] = $u['id'];
        $_SESSION['usuario_nome'] = $u['nome'];
		header("Location: /controle_estudos/app/dashboard/dashboard.php");
        exit;
    }
    $erro = "Login inválido";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>

  <!-- CSS base do sistema -->
  <link rel="stylesheet" href="/controle_estudos/assets/css/base.css">

  <!-- CSS específico do login -->
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
	<p><?= $erro ?></p>
	<input name="email" type="email" placeholder="E-mail" required>
	<input name="senha" type="password" placeholder="Senha" required>
	<button>Entrar</button>
  </form>

</div>
</body>
</html>
