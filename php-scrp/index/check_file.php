<?php
session_start();

// Preparar array de resposta
$response = ['file' => null];

if (isset($_SESSION['uploaded_file'])) {
    // Se um arquivo está na sessão, inclua o nome do arquivo na resposta
    $response['file'] = basename($_SESSION['uploaded_file']);
}

// Retorne os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
