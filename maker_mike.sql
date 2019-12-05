#DROP DATABASE IF EXISTS maker_mike;
CREATE DATABASE IF NOT EXISTS maker_mike;
USE maker_mike;
CREATE TABLE IF NOT EXISTS project(id int NOT NULL AUTO_INCREMENT, name varchar(255), config JSON, description varchar(255), primary key(id));
CREATE TABLE IF NOT EXISTS settings(id int NOT NULL AUTO_INCREMENT, name varchar(255), value varchar(1024), primary key(id));
CREATE TABLE IF NOT EXISTS user_type(id int NOT NULL AUTO_INCREMENT, name varchar(255), landing_page varchar(255), primary key(id));
CREATE TABLE IF NOT EXISTS user(id int NOT NULL AUTO_INCREMENT, user varchar(255), pass varchar(255), type int, foreign key(type) references user_type(id), primary key(id));

INSERT INTO user_type(id, name, landing_page) VALUES (1, 'System Administrator', 'index.php') ON DUPLICATE KEY UPDATE id = id;
INSERT INTO user_type(id, name, landing_page) VALUES (2, 'User', 'index.php') ON DUPLICATE KEY UPDATE id = id;
INSERT INTO user(id, user, pass, type ) VALUES (1, 'admin',  'admin', 1) ON DUPLICATE KEY UPDATE id = id;
INSERT INTO user(id, user, pass, type ) VALUES (2, 'user',  'user', 2) ON DUPLICATE KEY UPDATE id = id;
INSERT INTO project(id, config) VALUES (1, '{"name":"maker_mike","tables":[{"name":"project","columns":[{"name":"name","type":"255","show":"Name","permissions_create":".*","permissions_read":"-","permissions_update":".*"},{"name":"config","type":"JSON","show":"Config","permissions_create":".*","permissions_read":"-","permissions_update":".*"},{"name":"description","type":"255","show":"Description","permissions_create":".*","permissions_read":"-","permissions_update":".*"}],"permissions":{"create":".*","read":".*","update":".*","delete":".*"},"show":"name"},{"name":"settings","columns":[{"name":"name","type":"255","show":"Name","permissions_create":".*","permissions_read":"-","permissions_update":".*"},{"name":"value","type":1024,"show":"Value","permissions_create":".*","permissions_read":"-","permissions_update":".*"}],"permissions":{"create":".*","read":".*","update":".*","delete":".*"},"show":"name"}],"show":"Maker Mike"}') ON DUPLICATE KEY UPDATE id = id;