-- Create Database --
CREATE DATABASE _newdb_;

CREATE USER '_newdb_deployer'@'localhost' identified by '_newdb_pass';
GRANT create, alter, drop, insert on _newdb_.* to '_newdb_deployer'@'localhost';

CREATE USER '_newdb_user'@'localhost' identified by '_newdb_pass';
GRANT select, insert, delete, update on _newdb_.* to '_newdb_user'@'localhost';