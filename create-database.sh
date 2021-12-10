mysql -h 127.0.0.1 -u root << EOS
CREATE DATABASE test_db;
SHOW DATABASES;
CREATE USER 'test_user'@'%' IDENTIFIED BY 'password';
SELECT user, host FROM mysql.user;
GRANT ALL ON test_db.* TO test_user;
SHOW GRANTS FOR 'test_user'@'%';
USE test_db;
SHOW TABLES;
EOS
