$(document).ready(function () {
    // Esta função é chamada quando o usuário seleciona um arquivo.
    $('#fileToUpload').on('change', function () {
        var files = $(this).get(0).files;
        if (files.length > 0) {
            var fileName = files[0].name;
            $('.custom-file-label').text(fileName); // Atualiza o texto do label.

            // Mostre o botão de enviar e esconda o botão de cancelar quando um arquivo for selecionado
            $("#uploadButton").show();
            $("#cancelButton").hide();
        }
    });

    // Função para lidar com o evento de upload
    $("#uploadButton").click(function () {
        var formData = new FormData();
        var file = $('#fileToUpload')[0].files[0];
        formData.append('fileToUpload', file);

        // Adicione um indicador visual de carregamento aqui, se necessário

        $.ajax({
            type: 'POST',
            url: '/php-scrp/index/upload.php',  // Ajuste para o seu script de upload
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Remova o indicador visual de carregamento aqui, se aplicável
                if (response === 'success') {
                    // Se o upload foi bem-sucedido, exiba as opções de provisionamento
                    $("#provisioningContainer").show();
                    $("#uploadButton").hide();
                    $("#cancelButton").show();
                    $("#fileToUpload").prop('disabled', true); // Desativa o input de arquivo.
                } else {
                    alert('Favor verificar o arquivo enviado se atende o requisito para envio!');
                }
            },
            error: function (response) {
                // Tratamento de erro adicional, se necessário
                alert('Erro no servidor. Tente novamente.');
            }
        });
    });

    // Quando o botão de cancelar (X) é clicado.
    $("#cancelButton").click(function () {
        // Reverte tudo para o estado original.
        $("#provisioningContainer").hide();
        $("#uploadButton").show();
        $(this).hide(); // Esconde o botão de cancelar.
        $("#fileToUpload").prop('disabled', false); // Reativa o input de arquivo.
        $('.custom-file-label').text("Escolher arquivo"); // Reseta o label.
        // Limpa o valor do input do arquivo, para que o usuário tenha que selecionar um novo arquivo.
        $('#fileToUpload').val('');
        // Se necessário, adicione mais lógica para lidar com o arquivo já enviado.
        // Por exemplo, você pode querer removê-lo do servidor ou fazer outras limpezas.
    });

    // Quando o botão de fechar do modal é clicado, esta função será executada.
    $("#closeAudiocode405hdVoiceModalButton, #closeAudiocode405hdModalButton, #closeFanvilX1SGModalButton, #NaoTiraPqBugaPraFecharOmodel").click(function () {
        // Aqui, você pode adicionar uma chamada AJAX para um script PHP que limpará a sessão.
        $.ajax({
            type: 'POST',
            url: '/php-scrp/index/clear_session.php',  // Este é o script PHP que irá lidar com a limpeza da sessão.
            success: function (response) {
                // Se a sessão foi limpa com sucesso, redirecionamos para a página inicial.
                window.location.href = 'index.php';
            },
            error: function (response) {
                // Tratamento de erro adicional, se necessário.
                alert('Erro no servidor. Não foi possível limpar a sessão.');
            }
        });
    });
});
// Quando o link "Arquivos" é clicado.
$("#arquivosLink").click(function (e) {
    e.preventDefault();

    // Esconde o container de upload
    $(".upload-container, .provisioning-container").hide();

    // Faz uma chamada AJAX para obter a lista de arquivos
    $.ajax({
        type: 'GET',
        url: '/php-scrp/index/list_files.php',
        dataType: 'json',
        success: function (files) {
            // Limpa a lista atual
            $("#fileList").empty();

            // Adiciona cada arquivo à lista
            files.forEach(function (file) {
                var listItem = $('<li></li>');
                listItem.append('<span>' + file.name + '</span>');
                listItem.append('<button class="btn btn-light viewFileBtn" data-filename="' + file.name + '">Visualizar</button>');
                $("#fileList").append(listItem);
            });

            // Mostra o container de lista de arquivos
            $(".file-list-container").show();
        },
        error: function () {
            alert('Erro ao recuperar a lista de arquivos.');
        }
    });
});

$(document).on('click', '.viewFileBtn', function() {
    var filename = $(this).data('filename');
    
    // Faz uma chamada AJAX para obter o conteúdo do arquivo
    $.ajax({
        type: 'GET',
        url: '/php-scrp/index/get_file_content.php',
        data: { filename: filename },
        success: function(content) {
            $("#fileContent").text(content);
            $("#fileViewModal").modal('show');
        },
        error: function() {
            alert('Erro ao carregar o conteúdo do arquivo.');
        }
    });
});


