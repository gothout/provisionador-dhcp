<?php
// Ativar relatório de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se os dados de redefinição de senha estão presentes
    if (isset($_POST['email'], $_POST['reset_code'], $_POST['new_password'])) {
        $email = $_POST['email'];
        $resetCode = $_POST['reset_code'];
        $newPassword = $_POST['new_password'];

        // Caminho para o script Python
        $pythonScriptPath = "/var/www/html/py/upt_id.py";
        $escapedEmail = escapeshellarg($email);
        $escapedResetCode = escapeshellarg($resetCode);
        $escapedNewPassword = escapeshellarg($newPassword);

        // Monta o comando para executar o script Python e passa os argumentos
        $command = escapeshellcmd("python3 " . $pythonScriptPath . " " . $escapedEmail . " " . $escapedNewPassword . " " . $escapedResetCode);

        // Executa o comando e armazena a saída e a saída de erro
        $output = shell_exec($command . " 2>&1");

        // Log para debug
        error_log("Saída do script Python: " . $output);

        // Baseado na saída do Python, retornar a resposta adequada
        if (trim($output) === 'codigo_reset_invalido') {
            echo 'codigo_reset_invalido';
        } elseif (trim($output) === 'usuario_nao_corresponde') {
            echo 'usuario_nao_corresponde';
        } elseif (trim($output) === 'senha_atualizada_com_sucesso') {
            echo 'senha_atualizada_com_sucesso';
        } else {
            // Log para debug
            error_log("Resposta não reconhecida do script Python: " . $output);
            echo 'Erro não identificado. Por favor, tente novamente. Detalhes: ' . htmlspecialchars($output);
        }
    }
}
?>
