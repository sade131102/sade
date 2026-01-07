<?php
session_start();
require "../../config/db.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$tipo = $_GET['tipo'] ?? 'disciplina';

if ($_POST) {
    $sql = $pdo->prepare("
        INSERT INTO atividades
        (tipo, nome, instituicao, finalidade, carga_prevista, ementa)
        VALUES (?,?,?,?,?,?)
    ");

    $sql->execute([
        $_POST['tipo'],
        $_POST['nome'],
        $_POST['instituicao'] ?? null,
        $_POST['finalidade'] ?? null,
        $_POST['carga_prevista'],
        $_POST['ementa']
    ]);

    header("Location: ../dashboard/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastrar <?= ucfirst($tipo) ?></title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>
<header>
    Cadastro de <?= ucfirst($tipo) ?>
    <a href="../dashboard/dashboard.php">Voltar</a>
</header>

<main>

<form method="post" class="box">
    <input type="hidden" name="tipo" value="<?= $tipo ?>">

    <label>Nome</label>
    <input name="nome" required>

    <?php if ($tipo !== 'projeto'): ?>
        <label>Instituição</label>
        <input name="instituicao">
    <?php endif; ?>

    <?php if ($tipo === 'projeto'): ?>
        <label>Finalidade</label>
        <select name="finalidade">
            <option>Concurso Público</option>
            <option>Vestibular</option>
            <option>ENEM</option>
            <option>ENADE</option>
            <option>PND</option>
            <option>Outros</option>
        </select>
    <?php endif; ?>

    <label>Carga horária prevista</label>
    <input type="number" name="carga_prevista">

    <label>Ementa</label>
    <textarea name="ementa"></textarea>

    <button type="submit">Salvar</button>
</form>

</main>

</body>
</html>
