<?php
	
    // Captura o IP fornecido, por exemplo, via GET ou POST
    $ipAddress = $_GET['ip'] ?? '127.0.0.1'; // Use um valor padrão se nenhum IP for fornecido
    // Define o caminho do script Python
    $pythonScriptPath = '/var/www/html/consultor/py/busca_licenca_padrao.py';

    // Executa o script Python passando o IP como argumento
    exec("python3 $pythonScriptPath $ipAddress", $output, $returnVar);
    // Verifica se o script foi executado com sucesso
    if ($returnVar == 0) {
        // Imprime a saída do script Python
        foreach ($output as $line) {
            echo $line . "\n";
        }
    } else {
        echo "Erro ao executar o script Python";
    }
?>
