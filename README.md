## Atualizações (Concluidas)

#Versão Beta(v1.0)
- É possivel criar arquivos de provisionamento para o /provisionador
- Trabalho em conjunto com servidor TFTP e FTP
- Log de arquivos criados em Arquivo no home da pagina

## Atualizações futuras (Em projeto)

#Versão Beta(v1.1)
- Esqueci a senha para login, aonde será enviado e-mail com requisição para alterar senha.
- Log de criação de arquivo.

#Versão Beta(v1.1)
- Modificação de layout para generico

#Versão Beta(v1.2)

- Conexão com API (Fanvil e Grandstream) para provisionamento em nuvem de aparelhos.

### Documento informativo

- Responsável pelo documento: Lucas Daniel Chaves

- Responsável pelo desenvolvimento do sistema: Lucas Daniel Chaves

- Visão Geral: O Provisionador é uma plataforma integrada desenvolvida para automatizar gerenciar o provisionamento de dispositivos de telecomunicações. Através de uma interface web segura e intuitiva, o sistema facilita a configuração de dispositivos como FanvilX1SG e Audiocode405HD, permitindo que os usuários carreguem arquivos de configuração e executem scripts de provisionamento específicos para cada modelo de dispositivo.

## Sumário
- [Requisitos do Sistema](#requisitos-do-sistema)
- [Sistema de Provisionamento de Aparelhos SIP](#sistema-de-provisionamento-de-aparelhos-sip)
  - [Requisitos do Sistema](#requisitos-do-sistema-1)
  - [Arquitetura do Sistema](#arquitetura-do-sistema)
  - [Fluxo de Trabalho do Provisionamento](#fluxo-de-trabalho-do-provisionamento)
  - [Ecossistema básico dos arquivos do sistema (versão beta)](#ecossistema-básico-dos-arquivos-do-sistema-versão-beta)
    - [Dentro da pasta /php-scrp/index:](#dentro-da-pasta-php-scrpindex)
    - [Dentro da pasta /php-scrp/login:](#dentro-da-pasta-php-scrplogin)
    - [Dentro da pasta /php-scrp/provisionador:](#dentro-da-pasta-php-scrpprovisionador)
    - [Dentro da pasta /py:](#dentro-da-pasta-py)
    - [Dentro da pasta /js:](#dentro-da-pasta-js)
  - [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
  - [Instalação de Dependências](#instalação-de-dependências)
  - [Tutorial de Configuração do Servidor](#tutorial-de-configuração-do-servidor)


# Sistema de Provisionamento de Aparelhos SIP

## Requisitos do Sistema
- Sistema Operacional: CentOS 7 Minimal
- Servidor Web: Apache/2.4.6 (CentOS)
- Banco de Dados: MySQL
- Linguagens de Script: PHP/8.0.30 e Python 3.6
- Servidor TFTP: xinetd
- Servidor FTP: vsftpd
#### Arquitetura do Sistema:
- Frontend: O ponto de entrada do sistema é o 'index.php', o dashboard central que serve como a interface principal do usuário. Ele é construído com HTML5, CSS e JavaScript, juntamente com frameworks como Bootstrap para responsividade e design consistente. O frontend permite que os usuários façam upload de arquivos de configuração e escolham o dispositivo apropriado para o provisionamento. Ele também exibe modais com status em tempo real do processo de provisionamento.

- Backend: O backend é composto por vários scripts PHP e Python responsáveis pela lógica de negócios e manipulação de dados. Os scripts PHP, como 'login.php', gerenciam a autenticação e a sessão do usuário, enquanto 'audiocode405hd.php' e 'fanvilx1sg.php' lidam com a lógica de provisionamento. Eles chamam scripts Python correspondentes que interagem com os dispositivos e aplicam as configurações necessárias.


- Integração PHP-Python: Uma característica distintiva do sistema é a integração entre PHP e Python. Os scripts PHP iniciam processos Python e fornecem os dados necessários para o provisionamento. Os scripts Python, como 'audiocode405hd.py' e 'fanvilx1sg.py', executam tarefas específicas relacionadas ao provisionamento, como comunicação com dispositivos e aplicação de configurações.

- Segurança e Sessão: A segurança é rigorosamente implementada através do gerenciamento de sessões e autenticação de usuários. O sistema garante que apenas usuários autenticados possam acessar funções sensíveis e dados de provisionamento.

#### Fluxo de Trabalho do Provisionamento:
- Upload de Arquivo: Os usuários iniciam o processo fazendo o upload de um arquivo de configuração através do 'index.php'. O script 'upload.php' no backend armazena esse arquivo na sessão do usuário e o sistema agora está pronto para iniciar o provisionamento.

- Seleção de Dispositivo e Provisionamento: Após o upload bem-sucedido, os usuários selecionam o tipo de dispositivo que desejam configurar. Dependendo da escolha, o sistema ativa o script PHP correspondente que, por sua vez, chama o script Python apropriado. Este script Python lê o arquivo de configuração e interage com o dispositivo para aplicar as configurações.

- Feedback em Tempo Real: Durante o provisionamento, o sistema fornece feedback em tempo real através de uma janela modal na interface do usuário. Isso permite que os usuários acompanhem o progresso e recebam confirmação imediata do sucesso do provisionamento.

- Limpeza e Logoff: Após o provisionamento, o sistema limpa quaisquer dados temporários relacionados à sessão do usuário para manter a segurança. Os usuários podem então optar por sair do sistema de forma segura através do 'logout.php', que encerra a sessão atual.

- Log de arquivos - Após provisionamentos, implentado novo menu "Arquivos" aonde é possivel visualizar os arquivos criados em /provisionador, assim os usuários conseguem ver os aparelhos configurados.

#### Ecossistema básico dos arquivos do sistema (versão beta)
- Conexao.php:
Responsável pela criação e manutenção da conexão com o banco de dados. Este arquivo é crucial para operações que requerem acesso a dados persistentes, garantindo que todas as interações com o banco de dados sejam manipuladas de forma segura e eficiente.

- index.php:
Serve como o ponto de entrada principal do aplicativo, apresentando o dashboard. Aqui, os usuários podem iniciar o processo de provisionamento, fazer upload de arquivos de configuração e receber feedback sobre o status do provisionamento.


#### Dentro da pasta /php-scrp/index:

- clear_session.php:
Este script é invocado para limpar os dados da sessão após a conclusão do provisionamento. É uma etapa importante para garantir que informações sensíveis ou temporárias sejam removidas após cada sessão de provisionamento.

- logout.php:
Termina a sessão do usuário, limpando todas as informações da sessão e redirecionando o usuário para a página de login. Isso ajuda a manter a segurança do sistema.

- upload.php:
Gerencia o upload de arquivos, permitindo que os usuários carreguem arquivos de configuração que são essenciais para o provisionamento. Armazena os arquivos na sessão para acesso posterior durante o provisionamento.

- list_files.php: Invocado para trazer a aba "Arquivos" prontamente com o javascript responsável pelo front-ent, para lidar com a lista de arquivos que há dentro de /provisionador.

- get_file.content.php: Invocado pelo javascript responsável pelo front-end para trazer o conteúdo que há dentro dos arquivos de /provisionador.


#### Dentro da pasta /php-scrp/login:
- erro_login.php:
Gerencia os cenários de falha de login, redirecionando os usuários para a página de login se as credenciais fornecidas estiverem incorretas. Isso ajuda a manter o sistema seguro, impedindo acessos não autorizados.

- login.php:
Lida com o processo de autenticação. Este script valida as credenciais dos usuários contra as informações armazenadas no banco de dados. Se bem-sucedido, os usuários são direcionados para o dashboard; caso contrário, são redirecionados para tentar o login novamente.

#### Dentro da pasta /php-scrp/provisionador:

- audiocode405hd.php:
Inicia o processo de provisionamento para dispositivos Audiocode405HD. Ele chama o script Python correspondente, passando os dados necessários do arquivo que foi feito upload e armazenado na sessão pelo usuário.

- fanvilx1sg.php:
Similar ao audiocode405hd.php, mas específico para dispositivos FanvilX1SG. Ele prepara e delega o provisionamento para o script Python correspondente, fornecendo os dados necessários.


#### Dentro da pasta /py:
- audiocode405hd.py:
Script Python chamado pelo audiocode405hd.php. Realiza o provisionamento real, lendo o arquivo de configuração fornecido e aplicando as configurações ao dispositivo Audiocode405HD.

- fanvilx1sg.py:
Funciona de maneira semelhante ao audiocode405hd.py, mas é específico para o provisionamento de dispositivos FanvilX1SG. Aplica as configurações necessárias com base no arquivo de configuração carregado.

- (Ainda em tratamento) verify_email: Responsável para criar código aleatorio e temporario para reset de senha que será criado no banco de dados, e resposável para tratar no front-end quando há já gerado, quando não foi gerado ainda e quando o usuario não existe.

#### Dentro da pasta /js:
- upload-object-buttons.js:
Controla a lógica do lado do cliente para fazer upload de arquivos e interagir com a UI. Ele também gerencia a visibilidade dos controles na interface do usuário, dependendo do estado do processo de upload/provisionamento.

- audiocode405-provisionamento.js e fanvilx1sg-provisionamento.js:
Estes arquivos JavaScript lidam com a lógica específica do lado do cliente para o provisionamento de dispositivos Audiocode405HD e FanvilX1SG, respectivamente. Eles mostram modais, iniciam o processo de provisionamento e fornecem feedback em tempo real para o usuário.

- Dentro da pasta registroupload:
Esta pasta é utilizada como um armazenamento temporário para os arquivos gerados durante o processo de provisionamento. Os scripts dentro de /php-scrp salvam os arquivos aqui para que os scripts Python em /py possam acessá-los e utilizá-los para o provisionamento.

Cada um desses arquivos desempenha um papel específico dentro do ecossistema do aplicativo, contribuindo para a funcionalidade geral de provisionamento automatizado de dispositivos. A modularidade do sistema facilita a manutenção, a escalabilidade e a compreensão do fluxo de trabalho do aplicativo.


# Tutorial de Configuração do Servidor

Coloque seus scripts PHP no diretório correto (geralmente é /var/www/html no CentOS para o Apache).
Assegure-se de que os scripts Python em /py têm permissões de execução.
Depois de ter seguido todos os passos necessários e ter colocado os arquivos nos locais corretos, seu sistema deve estar pronto para ser utilizado.

<span style="color: red;">Por favor, note que algumas configurações podem variar dependendo de como o seu ambiente de rede está configurado e quaisquer restrições de segurança que você possa ter. Este guia é baseado em uma configuração padrão do CentOS 7.</span>

Este guia irá ajudá-lo a instalar e configurar as dependências necessárias para o seu serviço, bem como configurar os logs e auditorias de serviços específicos como TFTP e FTP em um sistema Linux.

## Pré-requisitos

- Acesso root ou com privilégios sudo ao servidor
- Conhecimento básico de linha de comando no Linux

## Instalação de Dependências

### MySQL Connector for Python

Para instalar o MySQL Connector para Python, que é necessário para que o seu código Python interaja com o banco de dados MySQL, execute o seguinte comando:

```bash
pip3 install mysql-connector-python==8.0.23
```


### Configuração de Logs

# Para CentOS/RHEL
```bash
sudo yum install tftp-server xinetd -y
```
# Para Ubuntu/Debian
```bash
sudo apt install tftp-hpa xinetd -y
```

Não esqueça de abrir a porta 69 no firewall se você estiver planejando acessar o serviço TFTP de fora do seu servidor local:
```bash
sudo firewall-cmd --add-service=tftp --permanent
sudo firewall-cmd --reload
```

Configure o serviço TFTP para iniciar com o xinetd adicionando ou modificando a configuração em /etc/xinetd.d/tftp.
Configure os logs de TFTP. Edite o arquivo /etc/rsyslog.conf e adicione as seguintes linhas:
```bash
# Log de TFTP
:programname, isequal, "in.tftpd" /var/log/tftp/tftp.log
& stop
```

Salve e feche o arquivo.
Reinicie o serviço de log:
```bash
sudo systemctl restart rsyslog
```
# SELinux
Permita que o diretório /provisionador seja acessível pelos serviços aplicáveis:


Configurações para acesso anonimo e pasta de provisionamento em /etc/xinetd.d/tftp: 
```bash
vim /etc/xinetd.d/tftp
```
```bash
service tftp
{
        socket_type             = dgram
        protocol                = udp
        wait                    = yes
        user                    = root
        server                  = /usr/sbin/in.tftpd
        server_args             = -s -s /provisionador -vv
        disable                 = no
        per_source              = 11
        cps                     = 100 2
        flags                   = IPv4
}
```

```bash
sudo chcon -Rt tftpdir_rw_t /provisionador
sudo setsebool -P tftp_anon_write 1
```

Para HTTPD (Apache):

```bash
sudo chcon -R -t httpd_sys_content_t /provisionador
sudo setsebool -P httpd_anon_write 1
```
Para permitir acesso generalizado:

```bash
sudo chcon -R -t public_content_rw_t /provisionador/
```

# Verifique os bloqueios do SELinux usando:

```bash
cat /var/log/audit/audit.log | grep httpd | grep denied
```
Apache
Para permitir que o Apache escreva no diretório /var/www/html/registroupload/, use os seguintes comandos:

```bash
sudo chcon -R -t httpd_sys_rw_content_t /var/www/html/registroupload/
sudo semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/html/registroupload(/.*)?"
sudo restorecon -R /var/www/html/registroupload/
```

FTP (vsftpd)
Instale o vsftpd se ainda não estiver instalado:

# Para CentOS/RHEL
```bash
sudo yum install vsftpd -y
```
# Para Ubuntu/Debian
```bash
sudo apt install vsftpd -y
```
Configure o vsftpd editando o arquivo /etc/vsftpd/vsftpd.conf com as configurações para acesso anonimo. Certifique-se de ajustar as linhas conforme necessário e salvar as alterações.


Inicie e habilite o serviço vsftpd:

```bash
sudo systemctl start vsftpd
sudo systemctl enable vsftpd
Permita o acesso ao vsftpd através do firewall, se necessário:

bash
Copy code
sudo firewall-cmd --add-service=ftp --permanent
sudo firewall-cmd --reload
```
## Configuração do Banco de Dados

Execute os seguintes comandos SQL no MySQL para configurar o banco de dados necessário para o sistema:

```sql
Database: Service

-- Tabela: usuarios --
CREATE TABLE usuarios (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabela: registros_login --
CREATE TABLE registros_login (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    success BOOLEAN
);

-- Tabela: password_resets --
CREATE TABLE password_resets (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    reset_code VARCHAR(255) NOT NULL,
    reset_expiry DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id)
);

-- Evento: clean_expired_resets --
SET GLOBAL event_scheduler = ON;
CREATE EVENT IF NOT EXISTS clean_expired_resets
ON SCHEDULE EVERY 1 MINUTE
DO
    DELETE FROM password_resets WHERE reset_expiry < NOW();
```
