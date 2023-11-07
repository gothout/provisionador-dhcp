<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: /login.html');
    exit;
    // A chamada var_dump deve estar antes do 'exit' se você quiser que ela seja executada.
    var_dump($_SESSION['loggedin']);
}

$directory = "/provisionador";
$filename = $_GET['filename'];

// Verifique se o arquivo solicitado realmente existe no diretório
if (file_exists($directory . '/' . $filename)) {
    $content = file_get_contents($directory . '/' . $filename);
    
    // Definindo o cabeçalho para texto puro
    header('Content-Type: text/plain; charset=utf-8');
    
    echo $content;
} else {
    http_response_code(404);
    echo "Arquivo não encontrado.";
}

?>
