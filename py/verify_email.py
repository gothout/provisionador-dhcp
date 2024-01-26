# -*- coding: utf-8 -*-
import sys
import mysql.connector
import smtplib
import random
import string
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart  # Importação adicionada
from email.mime.image import MIMEImage  # Importação adicionada
from datetime import datetime, timedelta

# Capturando o e-mail como um argumento da linha de comando
if len(sys.argv) != 2:
    print("Uso: verify_email.py <email>")
    sys.exit(1)

email = sys.argv[1]

def generate_reset_code():
    return ''.join(random.choices(string.ascii_letters + string.digits, k=6))

def send_email(to_email, reset_code):
    smtp_server = 'smtp.office365.com'  # Defina essas variáveis dentro da função
    smtp_port = 587
    from_email = 'lucas.chaves@wonit.com.br'  # Defina essas variáveis dentro da função
    from_password = 'Jak27554'  # Mova para uma variável de ambiente ou configuração segura

    # Criando um MIMEMultipart para suportar texto e imagem
    msg = MIMEMultipart('alternative')
    msg['Subject'] = 'Redefinição de Senha'
    msg['From'] = from_email
    msg['To'] = to_email

    # Corpo do e-mail
    email_body = f'''
        <html>
        <body>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: auto;">
            <tr>
                <td style="background-color: #ffffff; padding: 20px;">
                <table align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                    <tr>
                    <td style="padding-right: 10px; text-align: center; vertical-align: middle;">
                        <!-- Atualize o 'src' para a URL da imagem hospedada publicamente -->
                        <img src="https://uploaddeimagens.com.br/images/004/682/581/original/favicon-sigmacom.png?1701782860" width="100" height="auto" style="max-width: 100px; height: auto;" alt="Logo da SigmaCom">
                    </td>
                    <td style="text-align: left; vertical-align: middle;">
                        <span style="font-size: 24px; font-weight: bold; color: #6A5ACD; font-family: 'Arial', sans-serif;">Auriwon - Webgui</span>
                    </td>
                    </tr>
                </table>
                <p style="font-family: 'Arial', sans-serif; margin-top: 20px;">Seu código de redefinição é: <strong>{reset_code}</strong></p>
                <p style="font-family: 'Arial', sans-serif;">Atenciosamente,</p>
                <p style="font-family: 'Arial', sans-serif;">Lucas Chaves</p>
                <p style="font-family: 'Arial', sans-serif;">
                    <a href="https://github.com/gothout" style="text-decoration: none; color: #6A5ACD;">GitHub</a> |
                    <a href="https://www.linkedin.com/in/lucasdchaves/" style="text-decoration: none; color: #6A5ACD;">LinkedIn</a>
                </p>
                </td>
            </tr>
            </table>
        </body>
        </html>
    '''

    msg.attach(MIMEText(email_body, 'html'))
    
    try:
        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.starttls()
            server.login(from_email, from_password)
            server.sendmail(from_email, [to_email], msg.as_string())
    except smtplib.SMTPException as e:
        print(f"Erro ao enviar e-mail: {e}")
        sys.exit(1)

def check_user(email):
    try:
        conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='Service'
        )
        cursor = conn.cursor()

        cursor.execute("SELECT id FROM usuarios WHERE username = %s", (email,))
        user = cursor.fetchone()

        if not user:
            return 'usuario_inexistente'

        user_id = user[0]

        cursor.execute("SELECT reset_code, reset_expiry FROM password_resets WHERE user_id = %s", (user_id,))
        reset_entry = cursor.fetchone()

        if reset_entry:
            reset_code, reset_expiry = reset_entry
            # Aqui você pode verificar se o código expirou e tomar a ação apropriada
            return 'codigo_existente'

        new_reset_code = generate_reset_code()
        send_email(email, new_reset_code)

        reset_expiry = datetime.now() + timedelta(minutes=3)  # Expiração definida para 3 minutos a partir de agora
        cursor.execute("INSERT INTO password_resets (user_id, reset_code, reset_expiry) VALUES (%s, %s, %s)", (user_id, new_reset_code, reset_expiry))
        conn.commit()
        return 'codigo_enviado'

    except mysql.connector.Error as err:
        print(f"Erro de banco de dados: {err}")
        sys.exit(1)
    finally:
        cursor.close()
        conn.close()

# Execute a função e imprima o resultado
print(check_user(email))
