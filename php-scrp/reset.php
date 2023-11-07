<?php
// Ativar relatório de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Log para debug
    error_log("Email recebido para reset: " . $email);

    // Caminho para o script Python
    $pythonScriptPath = "/var/www/html/py/verify_email.py";
    $escapedEmail = escapeshellarg($email);

    // Monta o comando para executar o script Python e passa o e-mail como argumento
    $command = escapeshellcmd("python3 " . $pythonScriptPath . " " . $escapedEmail);

    // Executa o comando e armazena a saída e a saída de erro
    $output = shell_exec($command . " 2>&1");

    // Log para debug
    error_log("Saída do script Python: " . $output);

    // Baseado na saída do Python, retornar a resposta adequada
    if (trim($output) === 'usuario_inexistente') {
        echo 'usuario_inexistente';
    } elseif (trim($output) === 'codigo_existente') {
        echo 'codigo_existente';
    } elseif (trim($output) === 'codigo_enviado') {
        echo 'codigo_enviado';
    } else {
        // Log para debug
        error_log("Resposta não reconhecida do script Python: " . $output);
        echo 'Erro não identificado. Por favor, tente novamente. Detalhes: ' . htmlspecialchars($output);
    }
}
?>