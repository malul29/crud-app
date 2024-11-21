sudo mysql
CREATE DATABASE crud_app;
CREATE USER 'crud_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON crud_app.* TO 'crud_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
