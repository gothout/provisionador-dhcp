<?php
require '/var/www/html/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Usuário registrado com sucesso!";
}
?>