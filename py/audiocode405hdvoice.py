import os
import sys
import io

# Muda a codificação padrão para UTF-8.
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Função para criar o conteúdo do arquivo de configuração .cfg
def criar_config(mac, ramal, senha, servidor):
    config = f"""voip/line/0/auth_name={ramal}
voip/line/0/auth_password={senha}
voip/line/0/enabled=1
voip/line/0/extension_display={ramal}
voip/line/0/id={ramal}
voip/line/0/description={ramal}
voip/line/1/auth_name=
voip/line/1/auth_password=
voip/line/1/enabled=0
voip/line/1/id=
voip/line/1/description= 
voip/line/0/do_not_disturb/activated=0
voip/signalling/sip/proxy_address={servidor}
voip/signalling/sip/proxy_port=65535
voip/signalling/sip/use_proxy=1
voip/signalling/sip/use_proxy_ip_port_for_registrar=1
voip/signalling/sip/sip_outbound_proxy/addr=sbc.voicemanager.cloud 
voip/signalling/sip/sip_outbound_proxy/enabled=1 
voip/signalling/sip/sip_outbound_proxy/port=65535
voip/services/application_server_type=BSFT
voip/services/busy_lamp_field/enabled=0
voip/services/msg_waiting_ind/enabled=0
voip/services/msg_waiting_ind/subscribe=0
voip/services/msg_waiting_ind/subscribe_address={ramal}
voip/services/msg_waiting_ind/voice_mail_number=
system/feature_key_synchronization/enabled=1
voip/services/conference/conf_ms_addr=conf_nway@{servidor}
voip/services/conference/mode=REMOTE
voip/services/notify/check_sync/force_reboot_enabled=1
voip/signalling/sip/outgoing_request_no_response_timeout_ms=3500
voip/signalling/sip/failback_retry_timeout=
voip/auto_answer/enabled=1
voip/talk_event/enabled=1
voip/services/reject_code=CODE_486
personal_settings/soft_keys/ongoing_call/0/key_function=TRANSFER
personal_settings/soft_keys/ongoing_call/0/psk_index=0
personal_settings/soft_keys/ongoing_call/1/key_function=BLIND_TRANSFER
personal_settings/soft_keys/ongoing_call/1/psk_index=0
personal_settings/soft_keys/ongoing_call/2/key_function=CONF
personal_settings/soft_keys/ongoing_call/2/psk_index=0
personal_settings/soft_keys/ongoing_call/3/key_function=END
network/lan/dhcp/ntp/gmt_offset/enabled=0
system/ntp/gmt_offset=-03:00
system/ntp/primary_server_address=200.160.0.8
system/ntp/secondary_server_address=200.189.40.8
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
