import requests
import sys
import io

# Muda a codificação padrão para UTF-8.
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding='utf-8')

# Define um valor padrão para o IP, caso nenhum seja fornecido.
if len(sys.argv) > 1:
    ip_address = sys.argv[1]
else:
    ip_address = "127.0.0.1"  # Um valor padrão, caso nenhum IP seja fornecido

def imprimir_dados_usuario(dados):
    print("\nDados do Usuário:")
    print(f"ID: {dados['id']}")
    print(f"Nome: {dados['nome']}")
    print(f"Token: {dados['token']}")

def acessar_licenca(token):
    url_licenca = f"http://{ip_address}:8001/api/v3/licenca"
    headers = {"Authorization": f"Bearer {token}"}
    response = requests.get(url_licenca, headers=headers)

    if response.status_code == 200:
        dados_licenca = response.json()
        print(dados_licenca)
    else:
        print("Erro ao acessar dados da licença:", response.status_code)
        print("Resposta:", response.json())

# Define o token diretamente.
token = "1e5a475b7421c20cf0edb60543b20d406a2e55fd2619f5f04ee10dbdc41a007b"

# Acessa os dados da licença usando o token fornecido.
acessar_licenca(token)
