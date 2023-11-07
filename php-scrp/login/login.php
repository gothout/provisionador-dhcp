<?php
session_start(); // Inicia a sessão no início do script
require $_SERVER['DOCUMENT_ROOT'] . '/conexao.php';

//require 'var/php-scrp/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // O login é bem-sucedido; armazenar informações do usuário na sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username']; // Armazenando o nome de usuário na sessão

        // Registrar a tentativa de login bem-sucedida
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt = $pdo->prepare("INSERT INTO registros_login (username, ip_address, success) VALUES (?, ?, ?)");
        $stmt->execute([$username, $ip_address, true]);

        // Redirecionar usuário para a página desejada
        header("Location: /index.php");
        exit; // É uma boa prática chamar 'exit' após redirecionamentos para evitar a execução de código subsequente
    } else {
        // Login falhou, registrar a tentativa
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt = $pdo->prepare("INSERT INTO registros_login (username, ip_address, success) VALUES (?, ?, ?)");
        $stmt->execute([$username, $ip_address, false]);

        // Redirecionar para a página de erro
        header("Location: /php-scrp/login/erro_login.php");
        exit; // Encerra a execução do script após redirecionar
    }
}
?>
