# -*- coding: utf-8 -*-
import sys
import mysql.connector
from passlib.hash import bcrypt

def update_password(email, new_password, reset_code):
    try:
        conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='Service'
        )
        cursor = conn.cursor()

        cursor.execute("SELECT user_id FROM password_resets WHERE reset_code = %s", (reset_code,))
        reset_user = cursor.fetchone()
        
        if not reset_user:
            return 'codigo_reset_invalido'
        
        user_id = reset_user[0]

        cursor.execute("SELECT id FROM usuarios WHERE username = %s AND id = %s", (email, user_id))
        user = cursor.fetchone()

        if not user:
            return 'usuario_nao_corresponde'
        
        hashed_password = bcrypt.using(rounds=12).hash(new_password)

        cursor.execute("UPDATE usuarios SET password = %s WHERE id = %s", (hashed_password, user_id))
        conn.commit()

        cursor.execute("DELETE FROM password_resets WHERE user_id = %s", (user_id,))
        conn.commit()

        return 'senha_atualizada_com_sucesso'

    except mysql.connector.Error as err:
        print(f"Erro de banco de dados: {err}")
        sys.exit(1)
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

if len(sys.argv) != 4:
    print("Uso: registry_update.py <email> <nova_senha> <reset_code>")
    sys.exit(1)

email_arg = sys.argv[1]
new_password_arg = sys.argv[2]
reset_code_arg = sys.argv[3]

print(update_password(email_arg, new_password_arg, reset_code_arg))
