import sys
import io
import requests
from bs4 import BeautifulSoup
import time

# Muda a codificação padrão para UTF-8.
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Verifica se um número foi passado como argumento
if len(sys.argv) < 2:
    print("Por favor, forneça um número de telefone.")
    sys.exit(1)

numero_telefone = sys.argv[1]

url = 'http://www.consultaoperadora.com.br/site2015/resposta.php'
dados = {
    'tipo': 'consulta',
    'numero': numero_telefone
}

tentativas = 0
max_tentativas = 5  # Máximo de tentativas de solicitação
intervalo = 2       # Intervalo em segundos entre as tentativas

while tentativas < max_tentativas:
    resposta = requests.post(url, data=dados)

    if resposta.status_code == 200:
        soup = BeautifulSoup(resposta.text, 'html.parser')
        aguarde = soup.find("span", text="Aguarde")
        
        if not aguarde:
            operadora = soup.find("span", text="Operadora:").find_next_sibling("span").text
            portado = soup.find("span", text="Portado:").find_next_sibling("span").text
            numero = soup.find("span", text="Número:").find_next_sibling("span").text

            print(f"Operadora: {operadora}")
            print(f"Portado: {portado}")
            print(f"Número: {numero}")
            sys.exit(0)
        
    else:
        print("Falha ao acessar a página da consulta.")
        sys.exit(1)

    tentativas += 1
    time.sleep(intervalo)

print("Tempo de espera excedido.")
sys.exit(1)
