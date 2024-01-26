<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="/img/favicon-sigmacom.png" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="reset-container">
        <!-- Formulário para solicitar código de redefinição -->
        <form id="requestResetForm">
            <label for="email">E-mail para recuperação:</label>
            <input type="email" name="email" required>
            <br>
            <input type="submit" value="Enviar">
        </form>

        <!-- Formulário para inserir o código e nova senha -->
        <form id="resetPasswordForm" style="display: none;">
            <label for="reset_code">Código de Recuperação:</label>
            <input type="text" name="reset_code" required>

            <label for="new_password">Nova Senha:</label>
            <input type="password" name="new_password" required>

            <input type="submit" value="Redefinir Senha">
        </form>

        <div id="loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>
        <div id="response"></div>
    </div>
	<script src="reset-passw.js"></script>
    <footer>
        <p>@Desenvolvido por Lucas C. <i class="fas fa-code"></i></p>
    </footer>
	
</body>

</html>