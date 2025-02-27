import sqlite3

conn = sqlite3.connect("database.db")
cursor = conn.cursor()

cursor.execute('''CREATE TABLE IF NOT EXISTS posts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT
                )''')

cursor.executemany("INSERT INTO posts (title) VALUES (?)", [
    ("Hello World",),
    ("Flask Tutorial",),
    ("SQLite and Flask",)
])

conn.commit()
conn.close()
