# -*- coding: utf-8 -*-
import sys
import mysql.connector

def verify_reset_code(email, reset_code):
    try:
        # Estabeleça a conexão com o banco de dados MySQL
        conn = mysql.connector.connect(
            host='localhost',     # ou o endereço do seu servidor MySQL
            user='root',   # substitua com seu usuário
            password='', # substitua com sua senha
            database='Service' # substitua com o nome do seu banco de dados
        )
        cursor = conn.cursor()

        # Consulta para pegar o ID do usuário baseado no e-mail
        cursor.execute("SELECT id FROM usuarios WHERE username = %s", (email,))
        user_id = cursor.fetchone()
        
        if not user_id:
            return 'usuario_inexistente'
        
        user_id = user_id[0] # Extrai o ID do usuário
        
        # Consulta para verificar o código de reset baseado no ID do usuário
        cursor.execute("SELECT reset_code FROM password_resets WHERE user_id = %s", (user_id,))
        stored_reset_code = cursor.fetchone()
        
        if not stored_reset_code:
            return 'codigo_inexistente'
        
        # Verifica se o código corresponde
        if stored_reset_code[0] == reset_code:
            return 'codigo_correto'
        else:
            return 'codigo_errado'

    except mysql.connector.Error as err:
        print(f"Erro de banco de dados: {err}")
        sys.exit(1)
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

# Captura e-mail e código de recuperação a partir dos argumentos da linha de comando
if len(sys.argv) != 3:
    print("Uso: code_verify.py <email> <reset_code>")
    sys.exit(1)

email_arg = sys.argv[1]
reset_code_arg = sys.argv[2]

# Chama a função e imprime o resultado
print(verify_reset_code(email_arg, reset_code_arg))