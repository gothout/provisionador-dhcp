//Verifica sessão

<?php
session_start();

// Verifique se o usuário está logado. Se não, redirecione para a página de login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: /login.html');
    exit;
}

// Aqui, você limparia a parte específica da sessão que armazena os dados do arquivo.
// Por exemplo, se os dados do arquivo estão em $_SESSION['file_data'], você faria:
unset($_SESSION['file_data']);

// Você poderia retornar uma resposta de sucesso, se quiser.
echo "success";
?>
