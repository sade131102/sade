<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../app/dashboard/dashboard.php");
} else {
    header("Location: ../app/auth/login.php");
}
exit;
