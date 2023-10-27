<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Login</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="3;url=login.html">
</head>
<body>
    <div id="mainContent">
        <h1>Erro no Login</h1>
        <p>Usuário ou senha incorretos. Você será redirecionado em 3 segundos.</p>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "login.html";
        }, 3000);
    </script>
</body>
</html>
