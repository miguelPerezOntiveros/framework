DROP DATABASE IF EXISTS ;
CREATE DATABASE ;
USE ;
CREATE TABLE IF NOT EXISTS user_type(id int NOT NULL AUTO_INCREMENT, name varchar(255), primary key(id));
CREATE TABLE IF NOT EXISTS user(id int NOT NULL AUTO_INCREMENT, user varchar(255), pass varchar(255), type int, foreign key(type) references user_type(id), primary key(id));
INSERT INTO user_type(name) VALUES ('System Administrator');
INSERT INTO user_type(name) VALUES ('User');
INSERT INTO user(user, pass, type ) VALUES ('admin',  'admin', 1);
INSERT INTO user(user, pass, type ) VALUES ('user',  'user', 2);
