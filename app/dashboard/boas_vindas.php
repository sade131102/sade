<?php
$titulo = 'Bem-vindo ao SGP';
$pagina = 'page-dashboard';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/bootstrap.php';
require_once APP_ROOT . '/app/templates/auth.php';

ob_start();
?>

<div class="page-central welcome">

  <img src="/controle_estudos/assets/img/sgp1.png"
       alt="Sistema de Gestão Pessoal"
       class="welcome-logo">

  <h1 class="titulo-central">
    Bem-vindo ao <strong>SGP</strong>
  </h1>

  <p class="subtitulo-central">
    Sistema de Gestão Pessoal
  </p>

  <p class="welcome-text">
    Organize, acompanhe e desenvolva todas as dimensões da sua vida
    em um único sistema integrado.
  </p>

  <div class="welcome-modulos">

    <a href="/controle_estudos/app/vida_pessoal/index.php"
       class="welcome-card pessoal">
      🔴 Vida Pessoal
    </a>

    <a href="/controle_estudos/app/vida_profissional/index.php"
       class="welcome-card profissional">
      🟡 Vida Profissional
    </a>

    <a href="/controle_estudos/app/vida_academica/index.php"
       class="welcome-card academica">
      🔵 Vida Acadêmica
    </a>

    <a href="/controle_estudos/app/vida_religiosa/index.php"
       class="welcome-card religiosa">
      🟢 Vida Religiosa
    </a>

  </div>

</div>

<?php
$conteudo = ob_get_clean();
require_once APP_ROOT . '/app/templates/template.php';
