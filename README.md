# provisionador-dhcp
Sistema para facilitar a criação de arquivos para provisionamento de aparelhos SIP.


Sistema instalado no sistema CentOS 7 Minimal
Necessario ter MySQL Apache/2.4.6 (CentOS) PHP/8.0.30 e Python 3.6

Criação do banco de dados no MySQL deve conter os seguintes dados

---------------TABELA DE USUARIOS---------------------
USE Service;

CREATE TABLE usuarios (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
---------------TENTATIVAS DE LOGIN---------------------
CREATE TABLE registros_login (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    success BOOLEAN
);

-----------------------Cent OS--------------------------



Documentação referente ao sistema de provisionamento DHCP

Responsável pelo documento: Lucas Daniel Chaves
Responsável pelo desenvolvimento do sistema: Lucas Daniel Chaves
Visão Geral: O Provisionador é uma plataforma integrada desenvolvida para automatizar gerenciar o provisionamento de dispositivos de telecomunicações. Através de uma interface web segura e intuitiva, o sistema facilita a configuração de dispositivos como FanvilX1SG e Audiocode405HD, permitindo que os usuários carreguem arquivos de configuração e executem scripts de provisionamento específicos para cada modelo de dispositivo.
Arquitetura do Sistema:
•	Frontend: O ponto de entrada do sistema é o 'index.php', o dashboard central que serve como a interface principal do usuário. Ele é construído com HTML5, CSS e JavaScript, juntamente com frameworks como Bootstrap para responsividade e design consistente. O frontend permite que os usuários façam upload de arquivos de configuração e escolham o dispositivo apropriado para o provisionamento. Ele também exibe modais com status em tempo real do processo de provisionamento.

•	Backend: O backend é composto por vários scripts PHP e Python responsáveis pela lógica de negócios e manipulação de dados. Os scripts PHP, como 'login.php', gerenciam a autenticação e a sessão do usuário, enquanto 'audiocode405hd.php' e 'fanvilx1sg.php' lidam com a lógica de provisionamento. Eles chamam scripts Python correspondentes que interagem com os dispositivos e aplicam as configurações necessárias.


•	Integração PHP-Python: Uma característica distintiva do sistema é a integração entre PHP e Python. Os scripts PHP iniciam processos Python e fornecem os dados necessários para o provisionamento. Os scripts Python, como 'audiocode405hd.py' e 'fanvilx1sg.py', executam tarefas específicas relacionadas ao provisionamento, como comunicação com dispositivos e aplicação de configurações.

•	Segurança e Sessão: A segurança é rigorosamente implementada através do gerenciamento de sessões e autenticação de usuários. O sistema garante que apenas usuários autenticados possam acessar funções sensíveis e dados de provisionamento.
Fluxo de Trabalho do Provisionamento:
•	Upload de Arquivo: Os usuários iniciam o processo fazendo o upload de um arquivo de configuração através do 'index.php'. O script 'upload.php' no backend armazena esse arquivo na sessão do usuário e o sistema agora está pronto para iniciar o provisionamento.
•	Seleção de Dispositivo e Provisionamento: Após o upload bem-sucedido, os usuários selecionam o tipo de dispositivo que desejam configurar. Dependendo da escolha, o sistema ativa o script PHP correspondente que, por sua vez, chama o script Python apropriado. Este script Python lê o arquivo de configuração e interage com o dispositivo para aplicar as configurações.
•	Feedback em Tempo Real: Durante o provisionamento, o sistema fornece feedback em tempo real através de uma janela modal na interface do usuário. Isso permite que os usuários acompanhem o progresso e recebam confirmação imediata do sucesso do provisionamento.
•	Limpeza e Logoff: Após o provisionamento, o sistema limpa quaisquer dados temporários relacionados à sessão do usuário para manter a segurança. Os usuários podem então optar por sair do sistema de forma segura através do 'logout.php', que encerra a sessão atual.
Ecossistema básico dos arquivos do sistema (versão beta)
•	Conexao.php:
Responsável pela criação e manutenção da conexão com o banco de dados. Este arquivo é crucial para operações que requerem acesso a dados persistentes, garantindo que todas as interações com o banco de dados sejam manipuladas de forma segura e eficiente.
•	Erro_login.php:
Gerencia os cenários de falha de login, redirecionando os usuários para a página de login se as credenciais fornecidas estiverem incorretas. Isso ajuda a manter o sistema seguro, impedindo acessos não autorizados.
•	index.php:
Serve como o ponto de entrada principal do aplicativo, apresentando o dashboard. Aqui, os usuários podem iniciar o processo de provisionamento, fazer upload de arquivos de configuração e receber feedback sobre o status do provisionamento.
•	login.php:
Lida com o processo de autenticação. Este script valida as credenciais dos usuários contra as informações armazenadas no banco de dados. Se bem-sucedido, os usuários são direcionados para o dashboard; caso contrário, são redirecionados para tentar o login novamente.
Dentro da pasta /php-scrp:
•	audiocode405hd.php:
Inicia o processo de provisionamento para dispositivos Audiocode405HD. Ele chama o script Python correspondente, passando os dados necessários do arquivo que foi feito upload e armazenado na sessão pelo usuário.
•	fanvilx1sg.php:
Similar ao audiocode405hd.php, mas específico para dispositivos FanvilX1SG. Ele prepara e delega o provisionamento para o script Python correspondente, fornecendo os dados necessários.
•	clear_session.php:
Este script é invocado para limpar os dados da sessão após a conclusão do provisionamento. É uma etapa importante para garantir que informações sensíveis ou temporárias sejam removidas após cada sessão de provisionamento.
•	logout.php:
Termina a sessão do usuário, limpando todas as informações da sessão e redirecionando o usuário para a página de login. Isso ajuda a manter a segurança do sistema.
•	upload.php:
Gerencia o upload de arquivos, permitindo que os usuários carreguem arquivos de configuração que são essenciais para o provisionamento. Armazena os arquivos na sessão para acesso posterior durante o provisionamento.
Dentro da pasta /py:
•	audiocode405hd.py:
Script Python chamado pelo audiocode405hd.php. Realiza o provisionamento real, lendo o arquivo de configuração fornecido e aplicando as configurações ao dispositivo Audiocode405HD.
•	fanvilx1sg.py:
Funciona de maneira semelhante ao audiocode405hd.py, mas é específico para o provisionamento de dispositivos FanvilX1SG. Aplica as configurações necessárias com base no arquivo de configuração carregado.
Dentro da pasta /js:
•	upload-object-buttons.js:
Controla a lógica do lado do cliente para fazer upload de arquivos e interagir com a UI. Ele também gerencia a visibilidade dos controles na interface do usuário, dependendo do estado do processo de upload/provisionamento.
•	audiocode405-provisionamento.js e fanvilx1sg-provisionamento.js:
Estes arquivos JavaScript lidam com a lógica específica do lado do cliente para o provisionamento de dispositivos Audiocode405HD e FanvilX1SG, respectivamente. Eles mostram modais, iniciam o processo de provisionamento e fornecem feedback em tempo real para o usuário.
•	Dentro da pasta registroupload:
Esta pasta é utilizada como um armazenamento temporário para os arquivos gerados durante o processo de provisionamento. Os scripts dentro de /php-scrp salvam os arquivos aqui para que os scripts Python em /py possam acessá-los e utilizá-los para o provisionamento.
Cada um desses arquivos desempenha um papel específico dentro do ecossistema do aplicativo, contribuindo para a funcionalidade geral de provisionamento automatizado de dispositivos. A modularidade do sistema facilita a manutenção, a escalabilidade e a compreensão do fluxo de trabalho do aplicativo.

