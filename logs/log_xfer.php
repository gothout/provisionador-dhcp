<?php
define('LOG_FILE', '/var/log/proftpd/xferlog.log');
define('LOG_NOT_FOUND', 'Log file not found.');
define('ERROR_READING_LOG', 'Error reading log file.');

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

function getActionType($pattern) {
    if (strpos($pattern, 'c') !== false) {
        return 'Download';
    } elseif (strpos($pattern, 'i') !== false) {
        return 'Pesquisa';
    }
    return 'Ação não especificada';
}

function displayLogContent($logLines) {
    echo '<div class="log-content">';

    foreach ($logLines as $line) {
        $parts = preg_split('/\s+/', trim($line));

        if (count($parts) >= 10) {
            // Converte a data para o formato desejado
            $date = DateTime::createFromFormat('D M d', $parts[0] . ' ' . $parts[1] . ' ' . $parts[2]);
            echo 'Data: ' . $date->format('Y-m-d') . '<br>';
            echo 'Hora: ' . $parts[3] . '<br>';
            $ip = preg_match('/(\d+\.\d+\.\d+\.\d+)/', $parts[6], $matches) ? $matches[1] : 'N/A';
            echo 'IP: ' . $ip . '<br>';
            echo 'Arquivo: ' . $parts[8] . '<br>';
            echo 'Tipo de Ação: ' . getActionType(end($parts)) . '<br><br>';  // Pega o último elemento da array
        }
    }

    echo '</div>';
}

$fullLogRequested = isset($_GET['fullLog']) && $_GET['fullLog'] === 'true';
$logLines = readLogContent(LOG_FILE);

if (is_array($logLines)) {
    displayLogContent($logLines);

    if ($fullLogRequested) {
        echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
    }
} else {
    echo $logLines; // Display error message
}
 echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
?>
