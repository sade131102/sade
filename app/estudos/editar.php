<?php
session_start();
require "../../config/db.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listar.php");
    exit;
}

/* BUSCAR REGISTRO */
$sql = $pdo->prepare("SELECT * FROM estudos WHERE id = ?");
$sql->execute([$id]);
$registro = $sql->fetch();

if (!$registro) {
    header("Location: listar.php");
    exit;
}

/* BUSCAR ATIVIDADES */
$atividades = $pdo->query("SELECT id, nome, tipo FROM atividades ORDER BY tipo, nome")->fetchAll();

/* SALVAR EDIÇÃO */
if ($_POST) {
    $sql = $pdo->prepare("
        UPDATE estudos
        SET atividade_id=?, data=?, hora_inicio=?, hora_fim=?, presenca=?, conteudo=?, ano_letivo=?
        WHERE id=?
    ");

    $sql->execute([
        $_POST['atividade_id'],
        $_POST['data'],
        $_POST['hora_inicio'],
        $_POST['hora_fim'],
        $_POST['presenca'],
        $_POST['conteudo'],
        $_POST['ano_letivo'],
        $id
    ]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Registro</title>
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/sidebar.php'; ?>

<header>
    Editar Registro de Estudo
    <a href="listar.php">Voltar</a>
</header>

<main>

<form method="post" class="box">

<label>Atividade</label>
<select name="atividade_id">
<?php foreach ($atividades as $a): ?>
<option value="<?= $a['id'] ?>" <?= $a['id'] == $registro['atividade_id'] ? 'selected' : '' ?>>
<?= ucfirst($a['tipo']) ?> — <?= htmlspecialchars($a['nome']) ?>
</option>
<?php endforeach; ?>
</select>

<label>Data</label>
<input type="date" name="data" value="<?= $registro['data'] ?>" required>

<label>Início</label>
<input type="time" name="hora_inicio" value="<?= $registro['hora_inicio'] ?>" required>

<label>Término</label>
<input type="time" name="hora_fim" value="<?= $registro['hora_fim'] ?>" required>

<label>Presença</label>
<select name="presenca">
<option <?= $registro['presenca']=='Presença'?'selected':'' ?>>Presença</option>
<option <?= $registro['presenca']=='Falta'?'selected':'' ?>>Falta</option>
</select>

<label>Ano letivo</label>
<input type="number" name="ano_letivo" value="<?= $registro['ano_letivo'] ?>" required>

<label>Conteúdo</label>
<textarea name="conteudo"><?= htmlspecialchars($registro['conteudo']) ?></textarea>

<button>Salvar Alterações</button>

</form>

</main>

</body>
</html>
