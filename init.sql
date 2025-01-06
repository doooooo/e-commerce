GRANT ALL PRIVILEGES ON *.* TO 'springstudent'@'%';
FLUSH PRIVILEGES;
CREATE DATABASE testdb;

USE testdb;
CREATE TABLE PRODUCT (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `Category` varchar(45) DEFAULT NULL,
  `NAME` varchar(150) DEFAULT NULL,
  `Price` DECIMAL(10,2) NOT NULL,
  `Photo` varchar(250) DEFAULT NULL
);

CREATE TABLE USER (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `NAME` varchar(200) NOT NULL,
  `USERID` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL
);

CREATE TABLE PURCHASES (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `USERID` int NOT NULL,
  `PRODUCTID` int NOT NULL,
  `Date` DATETIME DEFAULT NULL,
  `Qty` int NOT NULL,
  `Price` DECIMAL(10,2) NOT NULL,
  `IS_CART` BOOLEAN DEFAULT FALSE
);

CREATE TABLE ADMIN (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `NAME` varchar(200) NOT NULL,
  `USERID` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL
);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`)
VALUES ('Candy', 'Choco 1', 12.4);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`)
VALUES ('Candy', 'Choco 2', 20.5);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`)
VALUES ('Snacks', 'Chips', 15.0);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`)
VALUES ('Meat', 'Steak', 200.50);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`)
VALUES ('Poultry', 'Chicken Fajita', 175.50);

INSERT INTO ADMIN (`NAME`,`USERID`,`PASSWORD`)
VALUES ('admin user','admin','admin@123')