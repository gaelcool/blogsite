
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS comment;
 
CREATE TABLE user (
    id_usr INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grade INTEGER(10) NULL,
    genero_lit VARCHAR(50)
);


CREATE TABLE post (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(50) NOT NULL,
    subtitle VARCHAR(300) NULL,
    author_name VARCHAR(75) NULL,
    content TEXT NOT NULL,
    tag VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_name) REFERENCES user(usuario) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO user (usuario, nombre, email, clave) VALUES (
    'Mechy',
    'Gael',
    'hello@gmail.com',
    'password'
);

INSERT INTO user (usuario, nombre, email, clave) VALUES (
    'Jimmy',
    'James Rodriguez',
    'jimmy@gmail.com',
    'password123'
);


CREATE TABLE comment (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id_C VARCHAR(100) NOT NULL,
    grade INTEGER  NULL,
    text VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id_C) REFERENCES user(usuario) ON DELETE CASCADE
);


INSERT INTO post (title, subtitle, author_name, content, created_at) VALUES (
    'TitulO',
    'SubTItuLo',
    "Mechy",  
    "Lorem ipsum dolor YMD sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." ,
    datetime("2023-04-22") 
);


INSERT INTO
    comment
    (
        created_at, user_id_C, text  )
    VALUES(
        datetime('2025-19-25')
        'Mechy',
        "This is Mechs's GREAT contribution!!!" 
        );

