
-- CREATE USERS AND ROLES
CREATE USER 'lazada_admin' @'localhost' IDENTIFIED BY 'password';
CREATE USER 'lazada_customer'@'localhost' IDENTIFIED BY 'password';
CREATE USER 'lazada_shipper'@'localhost' IDENTIFIED BY 'password';
CREATE USER 'lazada_vendor'@'localhost' IDENTIFIED BY 'password';
CREATE USER 'lazada_guest'@'localhost' IDENTIFIED BY 'password';
CREATE USER 'lazada_auth'@'localhost' IDENTIFIED BY 'password';
CREATE ROLE 'Customer', 'Shipper', 'Vendor', 'Guest', 'Auth';

-- GRANT PERMISSION FOR DATABASE ADMIN
GRANT ALL ON lazada_database.* TO 'lazada_admin' @'localhost';

-- GRANT PERMISSIONS TO ROLES
GRANT INSERT, UPDATE, DELETE, SELECT ON `Products` TO 'Vendor';
GRANT SELECT ON `Products` TO 'Customer';
GRANT INSERT, SELECT ON `Orders` TO 'Customer';
GRANT UPDATE, SELECT ON `Orders` TO 'Shipper';
GRANT SELECT ON `Hubs` TO 'Customer';
GRANT SELECT ON `Hubs` TO 'Shipper';
GRANT SELECT ON `Hubs` TO 'Vendor';
GRANT INSERT ON `Customers` TO 'Guest';
GRANT INSERT ON `Vendors` TO 'Guest';
GRANT INSERT ON `Shippers` TO 'Guest';
GRANT INSERT, SELECT ON `Users` TO 'Guest';
GRANT SELECT ON `Customers` TO 'Auth';
GRANT SELECT ON `Vendors` TO 'Auth';
GRANT SELECT ON `Shippers` TO 'Auth';
GRANT SELECT ON customers_hidepass TO 'Shipper';
GRANT SELECT ON customers_hidepass TO 'Vendor';
GRANT SELECT ON customers_hidepass TO 'Customer';
GRANT SELECT ON vendors_hidepass TO 'Customer';
GRANT SELECT ON vendors_hidepass TO 'Vendor';
GRANT SELECT ON vendors_hidepass TO 'Shipper';
GRANT SELECT ON shippers_hidepass TO 'Customer';
GRANT SELECT ON shippers_hidepass TO 'Vendor';
GRANT SELECT ON shippers_hidepass TO 'Shipper';
GRANT EXECUTE ON PROCEDURE `create_order` TO 'Customer';
GRANT EXECUTE ON PROCEDURE `update_order` TO 'Shipper';

-- GRANT ROLES TO USERS
GRANT 'Customer' TO 'lazada_customer'@'localhost';
GRANT 'Shipper' TO 'lazada_shipper'@'localhost';
GRANT 'Vendor' TO 'lazada_vendor'@'localhost';
GRANT 'Guest' TO 'lazada_guest'@'localhost';
GRANT 'Auth' TO 'lazada_auth'@'localhost';

-- SET ROLES
SET DEFAULT ROLE ALL TO
'lazada_guest'@'localhost',
'lazada_auth'@'localhost',
'lazada_vendor'@'localhost',
'lazada_shipper'@'localhost',
'lazada_customer'@'localhost',
'lazada_admin' @'localhost';
    
