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
                	$(".info-container").hide();
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
    	$(".info-container").show();
        $(this).hide(); // Esconde o botão de cancelar.
        $("#fileToUpload").prop('disabled', false); // Reativa o input de arquivo.
        $('.custom-file-label').text("Escolher arquivo"); // Reseta o label.
        // Limpa o valor do input do arquivo, para que o usuário tenha que selecionar um novo arquivo.
        $('#fileToUpload').val('');
        // Se necessário, adicione mais lógica para lidar com o arquivo já enviado.
        // Por exemplo, você pode querer removê-lo do servidor ou fazer outras limpezas.
    });

    // // ANTICLAYTIN ERROR Quando o botão de fechar do modal é clicado, esta função será executada.
    $("#closeAudiocode405hdVoiceModalButton, #closeAudiocode405hdModalButton, #closeFanvilX1SGModalButton, #closeFanvilX1SGvoiceModalButton, #NaoTiraPqBugaPraFecharOmodel").click(function () {
        // Aqui, você pode adicionar uma chamada AJAX para um script PHP que limpará a sessão.
        $.ajax({
            type: 'POST',
            url: '/php-scrp/index/clear_session.php',  // Este é o script PHP que irá lidar com a limpeza da sessão.
            success: function (response) {
                //// ANTICLAYTIN ERROR  Se a sessão foi limpa com sucesso, redirecionamos para a página inicial.
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
	document.title = "WebGui - Arquivos";
    // Esconde o container de upload 
    $(".upload-container, .provisioning-container, .info-container, .licencas-container, .webgui-container, .atualizacoes-container").hide();

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

// ANTICLAYTIN ERROR Quando o link "Home" é clicado.
$("#homeLink, .navbar-brand").click(function (e) {
    e.preventDefault();
	document.title = "WebGui - Auriwon";
    $(".upload-container, .info-container").show();
    $(".file-list-container, .licencas-container, .webgui-container, .atualizacoes-container").hide();

    // Aciona um clique no botão de cancelar automaticamente.
    $("#cancelButton").click();
});

// ANTICLAYTIN ERROR Quando o link "Licenças Xcontact" é clicado.
$("#licencasLink").click(function (e) {
    e.preventDefault();
	document.title = "WebGui - Xcontact";
    // Esconde o container de upload 
    $(".upload-container, .provisioning-container, .info-container, .file-list-container, .webgui-container, .atualizacoes-container").hide();
	$('.licencas-container').show();
});

// ANTICLAYTIN ERROR Quando o link "Webgui" é clicado.
$("#webguiLink").click(function (e) {
    e.preventDefault();
	document.title = "WebGui - Menu";
    // Esconde o container de upload 
    $(".upload-container, .provisioning-container, .info-container, .file-list-container, .licencas-container, .atualizacoes-container").hide();
	$('.webgui-container').show();
});

// ANTICLAYTIN ERROR Quando o link "Atualizações" é clicado.
$("#linkAtualizacoes").click(function (e) {
    e.preventDefault();
    document.title = "WebGui - Atualizações";
    $(".upload-container, .provisioning-container, .info-container, .file-list-container, .licencas-container, .webgui-container").hide();
    $('.atualizacoes-container').show();

    $.ajax({
        url: '/atualizacoes/att.md', // Caminho para o arquivo Markdown
        success: function(data) {
            // Aqui você precisará converter o Markdown em HTML
            var htmlContent = converterMarkdownParaHTML(data);
            $('.atualizacoes-container').html(htmlContent);
        },
        error: function() {
            $('.atualizacoes-container').html("<p>Erro ao carregar as atualizações.</p>");
        }
    });
});

// ANTICLAYTIN ERROR Quando o link "Webgui ENVIAR" é clicado.
$(document).ready(function() {
    // Manipulador de eventos de clique para o botão de consulta
    $("#btn-consulta").click(function() {
        // Obter o número do telefone
        var numeroTelefone = $("#numero-telefone").val();

        // Remover caracteres não numéricos
        var numeroApenasNumeros = numeroTelefone.replace(/\D/g, '');

        // Verificar se o número é válido (adicione sua própria lógica de validação aqui se necessário)
        if (numeroApenasNumeros.length >= 10) { // Exemplo: verifica se tem pelo menos 10 dígitos
            // Mostrar mensagem de carregamento
           	$("#loading").show();
        	$("#resultado-consulta").hide();

            // Fazer a requisição AJAX
            $.get("/php-scrp/busca_operadora.php", { numero: numeroApenasNumeros })
                .done(function(data) {
                    // Processar a resposta aqui
            		$("#loading").hide();
                    $("#resultado-consulta").html(data); // Assegure-se de que esta linha está presente e correta
            		$("#resultado-consulta").show(); // Isso tornará a div visível após os dados serem inseridos

                   // $("#resultado-consulta").html(data); // Adiciona os dados retornados ao elemento com ID 'resultado-consulta'
                })
                .fail(function() {
                    alert("Erro ao buscar informações da operadora.");
                })
                .always(function() {
                    // Remover a mensagem de carregamento
                    $("#loading-message").remove();
                });
        } else {
            alert("Por favor, digite um número de telefone válido.");
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


