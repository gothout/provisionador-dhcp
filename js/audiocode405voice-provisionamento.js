            function provisionAudiocodevoice() {
                // Mostre o modal específico para Audiocode
                $('#audiocodevoiceModal').modal('show'); // estava errado, apontando para o modal do Fanvil

                // Aqui, você pode adicionar lógicas de interface, como iniciar um spinner ou indicador de carregamento
                $('#audiocodevoiceProvisioningStatus').html('<div>Carregando...</div>'); // Você pode substituir isso por um ícone de carregamento ou outra coisa

                // Inicia o pedido AJAX
                $.ajax({
                    type: 'POST',
                    url: '/php-scrp/provisionador/audiocode405hdvoice.php', // estava errado, apontando para o script do Fanvil
                    // Envie quaisquer dados necessários para o script PHP como parte da requisição
                    data: { 
                        // possíveis dados a serem enviados 
                    },
                    success: function(response) {
                        // Use setTimeout para esperar 5 segundos
                        setTimeout(function() {
                            // Manipule a resposta. Atualize o modal com a informação de progresso ou conclusão
                            // Aqui, 'response' é o conteúdo retornado pelo seu script PHP.
                            $('#audiocodevoiceProvisioningStatus').html(response); // Mostra a resposta do backend

                            // Pare quaisquer indicadores de carregamento, se aplicável

                            // Implemente quaisquer etapas adicionais ou atualizações de interface necessárias
                        }, 5000); // 5000 milissegundos = 5 segundos
                    },
                    error: function() {
                        // Em caso de erro na chamada AJAX, atualize o modal com uma mensagem de erro
                        $('#audiocodevoiceProvisioningStatus').text('Erro no processo. Tente novamente.');

                        // Implemente lógicas de erro, como parar um indicador de carregamento ou permitir que o usuário tente novamente
                    }
                });
            }