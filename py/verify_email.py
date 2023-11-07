# -*- coding: utf-8 -*-
import sys
import mysql.connector
import smtplib
import random
import string
from email.mime.text import MIMEText
from datetime import datetime, timedelta

# Capturando o e-mail como um argumento da linha de comando
if len(sys.argv) != 2:
    print("Uso: verify_email.py <email>")
    sys.exit(1)

email = sys.argv[1]

def generate_reset_code():
    return ''.join(random.choices(string.ascii_letters + string.digits, k=6))

def send_email(to_email, reset_code):
    smtp_server = ''
    smtp_port = 587
    from_email = ''
    from_password = ''

    msg = MIMEText(f'Seu código de redefinição é: {reset_code}')
    msg['Subject'] = 'Redefinição de Senha'
    msg['From'] = from_email
    msg['To'] = to_email

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
