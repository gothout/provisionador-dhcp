<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="/css/principal.css">
    <link rel="icon" href="favicon-sigmacom.png" type="image/png">
    <meta http-equiv="refresh" content="3;url=/login.html">
    
</head>
<body>
    <div id="mainContent" class="logout-content">
        <h1>Deslogando...</h1>
        <p>Você será redirecionado em 3 segundos.</p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "/login.html";
        }, 3000);
    </script>
</body>
</html>
