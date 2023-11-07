document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const password = document.getElementById('passwordInput').value;
    // Substitua 'login.php' pelo seu endpoint de autenticação
    fetch('login.php', {
        method: 'POST',
        body: new URLSearchParams(`password=${password}`)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.body.style.backgroundColor = 'green';
            setTimeout(() => window.location.href = 'registrar.php', 2000);
        } else {
            document.getElementById('message').textContent = 'Senha incorreta!';
            document.body.style.backgroundColor = 'red';
            setTimeout(() => document.body.style.backgroundColor = '', 2000);
        }
    });
});
