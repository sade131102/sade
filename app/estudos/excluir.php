<?php
session_start();
require "../../config/db.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $sql = $pdo->prepare("DELETE FROM estudos WHERE id = ?");
    $sql->execute([$id]);
}

header("Location: listar.php");
exit;
