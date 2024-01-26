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