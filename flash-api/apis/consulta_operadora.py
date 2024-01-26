import requests
from bs4 import BeautifulSoup
from flask import Blueprint, request, jsonify
import time

consulta_operadora_bp = Blueprint('consulta_operadora', __name__)

@consulta_operadora_bp.route('/consulta-operadora', methods=['GET'])
def consulta_operadora():
    numero_telefone = request.args.get('numero')

    if not numero_telefone:
        return jsonify({'erro': 'Número de telefone não fornecido'}), 400

    url = 'http://www.consultaoperadora.com.br/site2015/resposta.php'
    dados = {
        'tipo': 'consulta',
        'numero': numero_telefone
    }

    max_tentativas = 5
    intervalo = 17  # Intervalo em segundos

    for tentativa in range(max_tentativas):
        try:
            resposta = requests.post(url, data=dados)
            resposta.raise_for_status()
            soup = BeautifulSoup(resposta.text, 'html.parser')

            if soup.find(text="Aguarde"):  # Verifica se a página pede para aguardar
                time.sleep(intervalo)  # Aguarda um período antes de tentar novamente
                continue

            # Extração dos dados
            operadora = obter_texto_irmao(soup, "span", "Operadora:")
            portado = obter_texto_irmao(soup, "span", "Portado:")
            numero = obter_texto_irmao(soup, "span", "Número:")

            if not all([operadora, portado, numero]):
                return jsonify({'erro': 'Informações não encontradas para o número fornecido'}), 404

            return jsonify({
                'operadora': operadora,
                'portado': portado,
                'numero': numero
            })

        except requests.RequestException as e:
            return jsonify({'erro': str(e)}), 500

    return jsonify({'erro': 'Tempo máximo de espera excedido'}), 504

def obter_texto_irmao(soup, tag, texto):
    elemento = soup.find(tag, text=texto)
    return elemento.find_next_sibling(tag).text if elemento else None
