GRANT ALL PRIVILEGES ON *.* TO 'springstudent'@'%';
FLUSH PRIVILEGES;
CREATE DATABASE testdb;

USE testdb;
CREATE TABLE PRODUCT (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `Category` varchar(45) DEFAULT NULL,
  `NAME` varchar(150) DEFAULT NULL,
  `Price` DECIMAL(10,2) NOT NULL,
  `Photo` varchar(250) DEFAULT ''
);

CREATE TABLE USER (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `NAME` varchar(200) NOT NULL,
  `USERID` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL
);

CREATE TABLE cart(
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quantity` int NOT NULL
);

CREATE TABLE PURCHASES (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `USERID` int NOT NULL,
  `PRODUCTID` int NOT NULL,
  `Date` DATETIME DEFAULT NULL,
  `Qty` int NOT NULL,
  `Price` DECIMAL(10,2) DEFAULT NULL,
  `IS_CART` BOOLEAN DEFAULT FALSE
);

CREATE TABLE ADMIN (
  `ID` int NOT NULL AUTO_INCREMENT primary key,
  `NAME` varchar(200) NOT NULL,
  `USERID` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL
);

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Dresses', 'Dress 1', 1250, 'Dress1.jpeg');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Dresses', 'Dress 2', 5000, 'Dress2.jpg');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Dresses', 'Dress 3', 4500, 'Dress3.avif');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Pants', 'Pant 1', 500.25, 'Pant1.avif');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Pants', 'Pant 2', 750, 'Pant2.avif');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Veil', 'Scarf 1', 500.0, 'Scarf1.jpg');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Veil', 'Scarf 2', 200.50, 'Scarf2.jpg');

INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`)
VALUES ('Skirts', 'Skirt 1', 350.50, 'Skirt1.webp');

INSERT INTO ADMIN (`NAME`,`USERID`,`PASSWORD`)
VALUES ('admin user','admin','admin@123')