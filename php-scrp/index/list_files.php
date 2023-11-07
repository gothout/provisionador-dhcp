<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: /login.html');
    exit;
    // A chamada var_dump deve estar antes do 'exit' se você quiser que ela seja executada.
    var_dump($_SESSION['loggedin']);
}

$directory = "/provisionador";
$files = array();
$allowedExtensions = array('txt', 'xml', 'cfg');

if (is_dir($directory)) {
    if ($handle = opendir($directory)) {
        while (($file = readdir($handle)) !== false) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ($file != "." && $file != ".." && in_array($extension, $allowedExtensions)) {
                $files[] = array(
                    "name" => $file,
                    "path" => "/provisionador/" . $file
                );
            }
        }
        closedir($handle);
    }
}

echo json_encode($files);
?>
