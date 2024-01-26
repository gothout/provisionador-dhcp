<?php
// create_user_file.php

// Configurar o fuso horário para usar o horário local
date_default_timezone_set('America/Sao_Paulo'); // Ajuste para o seu fuso horário específico

session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se o usuário não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit;
}

// Verifica se o número foi passado via HTTP GET
if (isset($_GET['numero'])) {
    $numero = $_GET['numero']; // Argumento via HTTP GET
} else {
    echo "Por favor, forneça um número de telefone.";
    exit(1);
}

// URL da API
$url = "http://localhost:5000/consulta-operadora?numero=$numero";

// Inicializa cURL
$ch = curl_init($url);

// Configura cURL para retornar a resposta
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a requisição cURL
$response = curl_exec($ch);

// Fecha a sessão cURL
curl_close($ch);

// Decodifica a resposta JSON
$data = json_decode($response, true);

// Verifica se a requisição foi bem-sucedida
if ($data) {
    // Exibe os dados
    echo "Número: " . htmlspecialchars($data['numero']) . "<br>";
    echo "Operadora: " . htmlspecialchars($data['operadora']) . "<br>";
    echo "Portado: " . htmlspecialchars($data['portado']) . "<br>";
} else {
    echo "Erro ao obter dados da API.";
}
?>
