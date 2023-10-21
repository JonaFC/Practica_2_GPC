#!/usr/bin/python3
import cgi

import psycopg2
from config import config



def connect():
    connection = None
    try:
      params = config()
      print('Conectando a la base de datos de postgresql...')
      connection = psycopg2.connect(**params)
      form=cgi.FieldStorage()

      #create a cursos
      crsr = connection.cursor()
      crsr.execute('SELECT * FROM users WHERE username=%s AND password=%s',(form["user"].value,form["pass"].value))
      user = crsr.fetchone()
      if(user is not None):
       print("Bienvenido.")
    except(Exception, psycopg2.DatabaseError) as error:
       print(error)

    finally:
       if connection is not None:
          connection.close()
          print("Conexión de la base de dato terminada.")

connect()


