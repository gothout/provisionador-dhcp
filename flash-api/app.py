from flask import Flask
from apis.consulta_operadora import consulta_operadora_bp

app = Flask(__name__)
app.register_blueprint(consulta_operadora_bp)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
