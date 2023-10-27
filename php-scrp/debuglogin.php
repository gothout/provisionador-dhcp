<?php
// debug.php
session_start(); // Isso é necessário para acessar as variáveis de sessão

echo "<h1>Informações da Sessão</h1>";

// Verifique se o usuário está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo "<p>Usuário está logado.</p>";
    
    // Mostrar o nome de usuário da sessão
    if (isset($_SESSION['username'])) {
        echo "<p>Nome de usuário: " . htmlspecialchars($_SESSION['username']) . "</p>"; // Usar 'htmlspecialchars' é uma boa prática para evitar a execução de script indesejado
    } else {
        echo "<p>Nome de usuário não está definido na sessão.</p>";
    }
} else {
    echo "<p>Usuário não está logado.</p>";
}

// Opcional: imprimir toda a sessão para fins de depuração
echo "<hr>";
echo "<h2>Dados completos da sessão:</h2>";
echo "<pre>";
print_r($_SESSION); // Isso imprimirá todos os dados da sessão
echo "</pre>";
?>
