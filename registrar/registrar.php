<?php
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Registro de Usuário</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #CCC;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #logoutButton {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #ff3b3b;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 1em;
        }

        #logoutButton:hover {
            background-color: #cc2a2a;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #000;
            /* Fundo preto como base para o efeito do espaço */
            background-image: url('images/space.jpg');
            /* Opção para adicionar uma imagem de fundo de espaço */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 50% 50%;
            z-index: -1;
        }

        form {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.1);
            background: white;
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        h2 {
            color: #000;
            font-size: 24px;
            margin-bottom: 1em;
        }

        label {
            display: block;
            text-align: left;
            margin: 0.5em 0;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007aff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #005ecb;
        }

        #notification-container {
            position: fixed;
            /* ou absolute */
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background-color: #333;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            min-width: 200px;
        }

        #notification {
            margin: 0;
        }
    </style>
</head>

<body>
    <div id="notification"></div>
    <div id="particles-js"></div> <!-- Contêiner de partículas para o fundo -->
    <form action="password_hash.php" method="post">
        <h2>Registrar Usuário</h2>
        <label for="username">Nome de Usuário:</label>
        <input type="text" name="username" required>
        <label for="password">Senha:</label>
        <input type="password" name="password" required>
        <input type="submit" value="Registrar">
        <input type="button" id="logoutButton" value="Logout"> <!-- Botão de logout -->
    </form>
    <div id="notification-container" style="display: none;">
        <div id="notification"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 355,
                        "density": {
                            "enable": true,
                            "value_area": 789.1476416322727
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.48927153781200905,
                        "random": false,
                        "anim": {
                            "enable": true,
                            "speed": 0.2,
                            "opacity_min": 0,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 2,
                        "random": true,
                        "anim": {
                            "enable": true,
                            "speed": 2,
                            "size_min": 0,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": false,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 0.2,
                        "direction": "none",
                        "random": true,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": false,
                            "mode": "bubble"
                        },
                        "onclick": {
                            "enable": false,
                            "mode": "repulse"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 400,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });
        });
        // Intercepta a submissão do formulário
        $('form').submit(function(e) {
            e.preventDefault(); // Impede a submissão tradicional do formulário

            $.ajax({
                type: 'POST',
                url: 'password_hash.php', // O seu script PHP de registro
                data: $(this).serialize(), // Os dados do formulário
                success: function(response) {
                    alert("Sucesso: " + response); // Popup de sucesso
                },
                error: function() {
                    alert("Erro ao registrar o usuário."); // Popup de erro
                }
            });
        });

        // Manipulador de clique para o botão de logout
        $('#logoutButton').click(function() {
            window.location.href = 'logout.php'; // Redireciona para o arquivo logout.php
        });
    </script>
</body>

</html>