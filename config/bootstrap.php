<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controle_estudos/config/path.php';

// Globais
require_once APP_ROOT . '/config/db.php';

// Services globais (usados em várias páginas)
require_once APP_ROOT . '/app/Services/DisciplinaService.php';
require_once APP_ROOT . '/app/Services/CursoService.php';

// Autenticação
require_once APP_ROOT . '/app/templates/auth.php';
