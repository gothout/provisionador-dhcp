import os
import sys
import io

# Muda a codificação padrão para UTF-8.
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Função para criar o conteúdo do arquivo de configuração .cfg
def criar_config(mac, ramal, senha, servidor):
    config = f"""voip/line/0/auth_name={ramal}
voip/line/0/auth_password={senha}
voip/line/0/call_forward/active=0
voip/line/0/call_forward/destination=
voip/line/0/call_forward/enabled=1
voip/line/0/call_forward/timeout=6
voip/line/0/call_forward/type=NO_REPLY
voip/line/0/description={ramal}
voip/line/0/do_not_disturb/activated=0
voip/line/0/enabled=1
voip/line/0/extension_display=
voip/line/0/group=0
voip/line/0/id={ramal}
voip/line/0/line_mode=PRIVATE
voip/line/0/shared_call_appearance/call_info_expiration_timeout=3600
voip/line/0/shared_call_appearance/call_info_subscription_failed_timeout=60
voip/line/0/shared_call_appearance/line_seize_expiration_timeout=15
voip/line/0/shared_call_appearance/speed_dial_delay=2
voip/line/0/shared_call_appearance/waiting_to_line_seize_tone=SILENCE
voip/signalling/sip/sip_registrar/addr={servidor}
voip/signalling/sip/sip_registrar/enabled=1
voip/signalling/sip/sip_registrar/port=5060
personal_settings/soft_keys/ongoing_call/0/key_function=TRANSFER
personal_settings/soft_keys/ongoing_call/0/psk_index=0
personal_settings/soft_keys/ongoing_call/1/key_function=BLIND_TRANSFER
personal_settings/soft_keys/ongoing_call/1/psk_index=0
personal_settings/soft_keys/ongoing_call/2/key_function=CONF
personal_settings/soft_keys/ongoing_call/2/psk_index=0
personal_settings/soft_keys/ongoing_call/3/key_function=END
system/ntp/date_display_format=AMERICAN
system/ntp/enabled=1
system/ntp/gmt_offset=-03:00
system/ntp/primary_server_address=a.st1.ntp.br
system/ntp/secondary_server_address=b.st1.ntp.br
personal_settings/language=PORTUGUESEBRAZILIAN
"""  # Certifique-se de que não há espaços ou tabs após a última aspa tripla
    return config

def main(caminho_arquivo):
    # Verifica se o arquivo especificado existe
    if not os.path.isfile(caminho_arquivo):
        print(f"O arquivo '{caminho_arquivo}' não foi encontrado.")
        sys.exit(1)  # Encerra o programa com um código de erro
    #print(f"Lendo o arquivo: {caminho_arquivo}")  # A indentação desta linha deve ser consistente com o resto do código
    # Diretório onde os arquivos .cfg serão salvos
    diretorio_destino = "/provisionador"  # ajuste para o caminho correto se necessário

    # Verifica se o diretório de destino existe, se não, cria o diretório
    if not os.path.exists(diretorio_destino):
        os.makedirs(diretorio_destino)

    # Verifica se é possível escrever no diretório.
    if not os.access(diretorio_destino, os.W_OK):
        print(f'Sem permissão de escrita no diretório: {diretorio_destino}')
        sys.exit(1)

    # Lê o arquivo e cria os arquivos .cfg
    with open(caminho_arquivo, 'r', encoding='utf-8') as arquivo:  # Especificando a codificação aqui
        linhas = arquivo.readlines()
        for linha in linhas:
            campos = linha.strip().split(',')
            if len(campos) == 4:
                mac, ramal, senha, servidor = campos
                mac = mac.lower()
                nome_arquivo = os.path.join(diretorio_destino, f"{mac}.cfg")
                conteudo = criar_config(mac, ramal, senha, servidor)

                # Escrevendo o conteúdo no arquivo de configuração
                with open(nome_arquivo, 'w', encoding='utf-8') as cfg_arquivo:  # E aqui também
                    cfg_arquivo.write(conteudo)
                print(f"Arquivo {nome_arquivo} criado com sucesso.")
            else:
                print("A linha não possui todos os campos necessários.")

    print("Concluído! ✅")

if __name__ == "__main__":
    try:  # O bloco try começa aqui
        if len(sys.argv) != 2:
            print("Uso: script.py <caminho_do_arquivo>")
            sys.exit(1)

        # O primeiro argumento é o caminho do arquivo
        caminho_arquivo = sys.argv[1]

        main(caminho_arquivo)

    except Exception as e:
        print(str(e), file=sys.stderr)  # Isso imprime a mensagem de erro no stderr
        sys.exit(1)  # Encerra o programa com um código de erro, indicando que ele falhou
