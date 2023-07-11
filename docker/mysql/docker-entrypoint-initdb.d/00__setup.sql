create database mydatabase;

CREATE USER 'devuser'.'%' identified WITH mysql_native_password by 'devpass';

grant all privileges on mydatabase.* to 'devuser'.'%';
