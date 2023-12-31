DROP DATABASE IF EXISTS Lazada_Database;
CREATE DATABASE Lazada_Database;

USE Lazada_Database;

-- VENDORS
CREATE TABLE Vendors
(
  vendorID INT NOT NULL AUTO_INCREMENT,
  vName VARCHAR(50) UNIQUE NOT NULL,
  vUsername VARCHAR(50) UNIQUE NOT NULL,
  vPassword VARCHAR(255) NOT NULL,
  vAddress VARCHAR(255) UNIQUE NOT NULL,   
  vLong DOUBLE UNIQUE NOT NULL,
  vLat DOUBLE UNIQUE NOT NULL,
  hubID INT NOT NULL,
  PRIMARY KEY (vendorID)
) ENGINE = InnoDB;

-- DISTRIBUTION HUBS
CREATE TABLE Hubs
(
  hubID INT NOT NULL AUTO_INCREMENT,
  hName VARCHAR(50) UNIQUE NOT NULL,
  hAddress VARCHAR(255) UNIQUE NOT NULL,   
  hLong DOUBLE UNIQUE NOT NULL,
  hLat DOUBLE UNIQUE NOT NULL,
  PRIMARY KEY (hubID)
) ENGINE = InnoDB;


-- SHIPPERS
CREATE TABLE Shippers
(
  shipperID INT NOT NULL AUTO_INCREMENT,
  sUsername VARCHAR(50) UNIQUE NOT NULL,
  sPassword VARCHAR(255) NOT NULL,
  hubID INT NOT NULL,
  PRIMARY KEY (ShipperID)
) ENGINE = InnoDB;


-- CUSTOMERS
CREATE TABLE Customers
(
  customerID INT NOT NULL AUTO_INCREMENT,
  cName VARCHAR(50) UNIQUE NOT NULL,
  cUsername VARCHAR(50) UNIQUE NOT NULL,
  cPassword VARCHAR(255) NOT NULL,
  cAddress VARCHAR(255) UNIQUE NOT NULL,   
  cLong DOUBLE UNIQUE NOT NULL,
  cLat DOUBLE UNIQUE NOT NULL,
  hubID INT,
  PRIMARY KEY (customerID)
) ENGINE = InnoDB;

-- PRODUCTS
Create Table Products
(
	productID BIGINT NOT NULL,
	pName VARCHAR(128) ,
	vendorID INT NOT NULL,
	price INT,
	createdDate DATETIME DEFAULT NOW(),
  PRIMARY KEY(productID, pName, Price)
) ENGINE = InnoDB
PARTITION BY RANGE (Price)(   
PARTITION p0 VALUES LESS THAN (100),   
PARTITION p1 VALUES LESS THAN (500),   
PARTITION p2 VALUES LESS THAN (1000),   
PARTITION p3 VALUES LESS THAN (2000),   
PARTITION p4 VALUES LESS THAN MAXVALUE
);  

CREATE INDEX idx_product_name ON Products (pName);


-- ORDERS
CREATE TABLE Orders
(
  orderID INT NOT NULL AUTO_INCREMENT,
  oStatus VARCHAR(20),
  customerID INT NOT NULL,
  vendorID INT NOT NULL,
  productID INT NOT NULL,
  dateCreated DATETIME DEFAULT NOW(),
  PRIMARY KEY (orderID)
) ENGINE = InnoDB;


-- ROLES
CREATE TABLE Roles (
    roleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    roleName VARCHAR(50)
) ENGINE = InnoDB;


-- USERS AND ROLES
CREATE TABLE Users (
    userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    roleID INT
) ENGINE = InnoDB;


-- VIEWS
CREATE OR REPLACE VIEW Vendors_HidePass AS
SELECT vendorID, vName, vUsername, vAddress, vLong, vLat, hubID
FROM Vendors;

CREATE OR REPLACE VIEW Customers_HidePass AS
SELECT customerID, cName, cUsername, cAddress, cLong, cLat
FROM Customers;

CREATE OR REPLACE VIEW Shippers_HidePass AS
SELECT shipperID, sUsername, hubID
FROM Shippers;

