$(document).ready(function () {
  // Lida com o envio do formulário de solicitação de reset
  $("#requestResetForm").on("submit", function (e) {
    e.preventDefault(); // Evita o envio padrão do formulário
    $("#loading").show();
    $("#requestResetForm").hide();
    var email = ""; // Esta variável armazenará o e-mail submetido
    email = $('input[name="email"]').val(); // Armazena o e-mail enviado

    $.ajax({
      type: "POST",
      url: "/php-scrp/reset.php",
      data: $(this).serialize(),
      success: function (response) {
        $("#loading").hide();
        switch (response) {
          case "usuario_inexistente":
            $("#response").html("<p>Usuário inexistente.</p>");
            $("#requestResetForm").show();
            break;
          case "codigo_existente":
            // Se o código já foi enviado, vai diretamente para a etapa de redefinição de senha
            // Adiciona o campo oculto com o e-mail ao segundo formulário
            $("<input>")
              .attr({
                type: "hidden",
                id: "email",
                name: "email",
                value: email,
              })
              .appendTo("#resetPasswordForm");
            $("#resetPasswordForm").show();
            break;
          case "codigo_enviado":
            $("#response").html(
              "<p>Código de recuperação enviado com sucesso.</p>"
            );
            // Adiciona o campo oculto com o e-mail ao segundo formulário
            $("<input>")
              .attr({
                type: "hidden",
                id: "email",
                name: "email",
                value: email,
              })
              .appendTo("#resetPasswordForm");
            $("#resetPasswordForm").show();
            break;
          default:
            $("#response").html("<p>Resposta inesperada do servidor.</p>");
            $("#requestResetForm").show();
        }
      },
      error: function () {
        $("#loading").hide();
        $("#requestResetForm").show();
        $("#response").html(
          "<p>Ocorreu um erro ao enviar a solicitação. Tente novamente.</p>"
        );
      },
    });
  });

  // Lida com o envio do formulário de redefinição de senha
  $("#resetPasswordForm").on("submit", function (e) {
    e.preventDefault();
    $("#loading").show();
    $("#resetPasswordForm").hide();

    $.ajax({
      type: "POST",
      url: "/php-scrp/resetp.php",
      data: $(this).serialize(),
      success: function (response) {
        $("#loading").hide();
        switch (response) {
          case "senha_atualizada_com_sucesso":
            $("#response").html(
              '<p>Senha atualizada com sucesso! Você será redirecionado em <span id="countdown">3</span> segundos.</p>'
            );
            var counter = 3;
            var interval = setInterval(function () {
              counter--;
              $("#countdown").text(counter);
              if (counter === 0) {
                clearInterval(interval);
                window.location.href = "/login.html"; // Redireciona após o contador
              }
            }, 1000);
            break;
          case "codigo_reset_invalido":
            // Se o código de reset for inválido, volta para o formulário de inserção do código
            $("#response").html(
              "<p>Código de redefinição inválido. Insira novamente o código e a nova senha.</p>"
            );
            $("#resetPasswordForm").show();
            break;
          case "usuario_nao_corresponde":
            // Se o usuário não corresponder, recarrega a página
            window.location.reload();
            break;
          default:
            $("#response").html("<p>Ocorreu um erro. Tente novamente.</p>");
            $("#resetPasswordForm").show();
        }
      },
      error: function () {
        $("#loading").hide();
        $("#resetPasswordForm").show();
        $("#response").html(
          "<p>Ocorreu um erro ao redefinir a senha. Tente novamente.</p>"
        );
      },
    });
  });
});
