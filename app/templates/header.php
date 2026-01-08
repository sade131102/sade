<header class="topbar">

  <div class="topbar-left">
    <img src="/controle_estudos/assets/img/sgp1.png" alt="SGP">

    <div class="topbar-title">
      <strong>SGP</strong>
      <span>Sistema de Gestão Pessoal</span>
    </div>
  </div>

  <nav class="topbar-nav">
    <a href="/controle_estudos/app/dashboard/boas_vindas.php">Início</a>
    <a href="/controle_estudos/app/vida_pessoal/index.php" class="pessoal">Vida Pessoal</a>
    <a href="/controle_estudos/app/vida_profissional/index.php" class="profissional">Vida Profissional</a>
    <a href="/controle_estudos/app/vida_academica/index.php" class="academica">Vida Acadêmica</a>
    <a href="/controle_estudos/app/vida_religiosa/index.php" class="religiosa">Vida Religiosa</a>
	<a href="/controle_estudos/app/blog/index.php">Blog</a>

  </nav>

  <div class="topbar-right">
    <span><?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?></span>
    <a href="/controle_estudos/app/auth/logout.php">Sair</a>
  </div>

</header>