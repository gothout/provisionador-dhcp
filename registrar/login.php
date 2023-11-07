<?php
session_start();
$hashed_password = '$2y$10$1yqLIuLBjFRrtbe154zDH.TtrI.L48XKWHvwq9/kwETeSiDtU3anK'; // Substitua por seu hash gerado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && password_verify($_POST['password'], $hashed_password)) {
        $_SESSION['authenticated'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
exit;
