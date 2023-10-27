<?php
// create_user_file.php
//echo exec('whoami');

// Configurar o fuso horário para usar o horário local
date_default_timezone_set('America/Sao_Paulo'); // Ajuste para o seu fuso horário específico

session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se o usuário não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit;
}

// Verifique se o conteúdo do arquivo está presente na variável de sessão
if (!isset($_SESSION['file_content']) || empty($_SESSION['file_content'])) {
    die("Erro: Nenhum conteúdo disponível para ser escrito no arquivo.");
}

// O usuário está logado
$username = $_SESSION['username'];

// Conteúdo a ser escrito no arquivo
$fileContent = $_SESSION['file_content'];

// Pegar a data e hora atual
$currentDateTime = date("dmyHis"); // Formato: dia, mês, ano, hora, minuto, segundo

// Diretório onde os arquivos serão salvos (no mesmo diretório deste script)
$uploadDirectory = '/var/www/html/registroupload/';

// Verifique se o diretório existe e é gravável
if (!is_dir($uploadDirectory)) {
    die("Erro: o diretório não existe.");
} else if (!is_writable($uploadDirectory)) {
    die("Erro: o diretório não tem permissões de escrita.");
}

// Função para gerar um nome de arquivo que ainda não existe
function generateFilename($directory, $username, $dateTime) {
    $absoluteFilename = '';
    do {
        // gerar um número aleatório
        $randomNumber = rand(1000, 9999);

        // construir o caminho absoluto do arquivo
        // estrutura: username + data + hora + número aleatório
        $absoluteFilename = $directory . $username . $dateTime . $randomNumber . '.txt'; // Adicionando extensão

    } while (file_exists($absoluteFilename)); // se o arquivo já existe, repete a geração de um novo nome

    return $absoluteFilename;
}

// Gerar um nome de arquivo único no diretório de destino.
$newFileName = generateFilename($uploadDirectory, $username, $currentDateTime);

// Agora, vamos criar e abrir este arquivo para escrita
$handle = fopen($newFileName, 'w');

if ($handle === false) {
    // Obter a última mensagem de erro
    $error = error_get_last();
    die('Erro ao abrir o arquivo: ' . htmlspecialchars($error['message']));
}

// Escrever o conteúdo no arquivo
fwrite($handle, $fileContent);

fclose($handle);

// Neste ponto, o arquivo foi criado com sucesso.
// echo "Arquivo criado com sucesso: " . basename($newFileName);

// Chamar o script Python e passar o caminho do arquivo como argumento
$pythonScriptPath = "/var/www/html/py/fanvilx1sg.py"; // <- substitua pelo caminho real do seu script Python
$command = "python3 " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($newFileName);

// Define os descritores para o processo
$descriptors = array(
   0 => array("pipe", "r"),  // stdin
   1 => array("pipe", "w"),  // stdout
   2 => array("pipe", "w")   // stderr
);

// Inicia o processo
$process = proc_open($command, $descriptors, $pipes);

if (is_resource($process)) {
    // Fecha o stdin, já que não vamos escrever nada
    fclose($pipes[0]);

    // Captura a saída padrão e de erro
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    // Fecha os pipes
    fclose($pipes[1]);
    fclose($pipes[2]);

    // Fecha o processo
    $return_value = proc_close($process);

    // Verifica se houve algum erro
    if (!empty($stderr)) {
        // Houve um erro, exiba ou processe conforme necessário
        echo "Houve um erro ao executar o script Python: $stderr";
    } else {
        // Tudo ocorreu bem, exiba a saída do script
        // Aqui, estamos convertendo quebras de linha de texto simples em <br> para HTML.
        $formatted_stdout = nl2br($stdout);  // Converte quebras de linha em <br>
        echo $formatted_stdout;  // Exibe a saída formatada
    }
} else {
    echo "Falha ao executar o comando.";
}
?>

