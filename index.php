<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
    // A chamada var_dump deve estar antes do 'exit' se você quiser que ela seja executada.
    var_dump($_SESSION['loggedin']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProvSigma</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/css/principal.css">
    <link rel="icon" href="/img/favicon-sigmacom.png" type="image/png">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="/img/favicon-sigmacom.png" alt="ProvSigma Logo" width="30" height="30" class="d-inline-block align-top">
            ProvSigma
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="arquivosLink">Arquivos</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logLink">Log</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/php-scrp/index/logout.php" id="logoutBtn">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="upload-container">
            <h3>Selecione um arquivo para provisionamento:</h3>
            <form id="uploadForm" class="d-flex flex-column align-items-center">
                <!-- Aqui você tem o input para o arquivo -->
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload">
                    <label class="custom-file-label" for="fileToUpload">Escolher arquivo</label>
                </div>
                <button type="button" id="uploadButton" class="btn btn-light">Enviar</button>
                <button type="button" id="cancelButton" class="btn btn-danger" style="display: none;">X</button>
            </form>
        </div>

        <div class="provisioning-container" id="provisioningContainer" style="display: <?php echo $dataLoaded ? 'block' : 'none'; ?>;">
            <h3>Selecione o tipo de provisionamento:</h3>
            <div class="d-flex flex-column align-items-center">
            <button onclick="provisionFanvilX1SG()" class="btn btn-light mb-3">Provisionar FanvilX1SG - Xcontact</button>
            <button onclick="provisionAudiocode()" class="btn btn-light mb-3">Provisionar AudioCode-405HD - Xcontact</button>
			<button onclick="provisionAudiocodevoice()" class="btn btn-light mb-3">Provisionar AudioCode-405HD - VoiceManager TEMPORARIO</button>
            </div>
        </div>
        <!-- Modal para Audiocode405HD Voice -->
        <div class="modal fade" id="audiocodevoiceModal" tabindex="-1" role="dialog" aria-labelledby="audiocodevoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="audiocodevoiceModalLabel">Provisionamento de Audiocode 405HD - Voice</h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="audiocodevoiceProvisioningStatus">Iniciando provisionamento...</p>
                        <!-- Adicione outros elementos interativos ou informativos conforme necessário -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeAudiocode405hdVoiceModalButton">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Modal para Audiocode405HD -->
        <div class="modal fade" id="audiocodeModal" tabindex="-1" role="dialog" aria-labelledby="audiocodeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="audiocodeModalLabel">Provisionamento de Audiocode 405HD - Xcontact</h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="audiocodeProvisioningStatus">Iniciando provisionamento...</p>
                        <!-- Adicione outros elementos interativos ou informativos conforme necessário -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeAudiocode405hdModalButton">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para FanvilX1SG -->
        <div class="modal fade" id="fanvilX1SGModal" tabindex="-1" role="dialog" aria-labelledby="fanvilX1SGModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fanvilX1SGModalLabel">Provisionamento de FanvilX1SG - Xcontact</h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="fanvilX1SGProvisioningStatus">Iniciando provisionamento...</p>
                        <!-- Adicione outros elementos interativos ou informativos conforme necessário -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeFanvilX1SGModalButton">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-list-container" style="display: none;">
            <h3>Arquivos em /provisionador:</h3>
            <ul id="fileList"></ul>
        </div>

        <!-- Modal para visualizar o arquivo -->
        <div class="modal fade" id="fileViewModal" tabindex="-1" role="dialog" aria-labelledby="fileViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document"> <!-- Aqui adicionamos a classe modal-lg -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileViewModalLabel">Visualizar Arquivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <pre id="fileContent"></pre> <!-- Usamos a tag <pre> aqui -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer mt-auto py-2 bg-dark">
            <div class="container">
                <span class="text-muted">@Desenvolvido por Lucas C.</span>
                <span class="footer-icon">
                    <i class="fas fa-code"></i>
                </span>
            </div>
            

        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="/js/upload-object-buttons.js"></script> <!--Script para os objetos e upload-->
        <script src="/js/fanvilx1sg-provisionamento.js"></script> <!--Script interação para provisionador-->
        <script src="/js/audiocode405-provisionamento.js"></script> <!--Script interação para provisionador-->
        <script src="/js/audiocode405voice-provisionamento.js"></script> <!--Script interação para provisionador-->  
    </body>
</html>
