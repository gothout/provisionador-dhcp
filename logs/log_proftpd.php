    <?php
    define('LOG_FILE', '/var/log/atftpd/tftp.log');
    define('LOG_NOT_FOUND', 'Arquivo de log não encontrado.');
    define('ERROR_READING_LOG', 'Erro ao ler o arquivo de log.');

    function readLogContent($filename) {
        if (!file_exists($filename)) {
            return LOG_NOT_FOUND;
        }

        $lines = file($filename);
        if ($lines === false) {
            return ERROR_READING_LOG;
        }

        return $lines;
    }

    function displayLogContent($logLines) {
        echo '<div class="log-content">';

        foreach ($logLines as $line) {
            echo htmlspecialchars($line) . "<br>";
        }

        echo '</div>';
    }

    $logLines = readLogContent(LOG_FILE);

    if (is_array($logLines)) {
        displayLogContent($logLines);
    } else {
        echo $logLines; // Exibe mensagem de erro
    }
	echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
    ?>