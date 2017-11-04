create database contest_example;
use contest_example;
create table Entries (ID MEDIUMINT NOT NULL AUTO_INCREMENT, Token varchar(255) NOT NULL, FirstName varchar(255) NOT NULL, LastName varchar(255), Email varchar(255) NOT NULL, Winner bit(1), PRIMARY KEY (id));
