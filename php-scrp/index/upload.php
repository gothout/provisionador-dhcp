<?php
session_start();

// Verifica se o arquivo foi enviado sem erros.
if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
    // Lê o conteúdo do arquivo carregado e converte em um array de linhas
    $lines = file($_FILES["fileToUpload"]["tmp_name"], FILE_IGNORE_NEW_LINES);

    // Verifica se o arquivo não está vazio e se a primeira linha é o cabeçalho esperado
    if ($lines && trim($lines[0]) === 'mac,ramal,senha,servidor') {
        // Remove a primeira linha (cabeçalho)
        array_shift($lines);

        // Une as linhas de volta em uma string, se necessário, ou você pode trabalhar com elas como um array
        $fileContentWithoutHeader = implode("\n", $lines);

        // Armazena o conteúdo em uma variável de sessão
        $_SESSION['file_content'] = $fileContentWithoutHeader;

        echo "success";
    } else {
        // Se o cabeçalho não corresponder, retorna uma mensagem de erro
        echo "error: incompatible file format. Expected header: 'mac,ramal,senha,servidor'.";
    }
} else {
    // Caso tenha havido um erro no upload, retorna uma mensagem de erro
    echo "error: there was an issue uploading the file.";
}
?>