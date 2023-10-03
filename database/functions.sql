
-- ------ ROLES TRIGGERS ---------
-- TRIGGER TO ASSIGN SHIPPER ROLE
DELIMITER $$
CREATE TRIGGER auth_register_shippers AFTER INSERT ON Shippers FOR EACH ROW
    BEGIN
        INSERT INTO Users (username, roleID)
        VALUES (NEW.sUsername, 3);
	END $$

-- TRIGGER TO ASSIGN CUSTOMER ROLE
CREATE TRIGGER auth_register_customers AFTER INSERT ON Customers FOR EACH ROW
    BEGIN
        INSERT INTO Users (username, roleID)
        VALUES (NEW.cUsername, 2);
    END $$

-- TRIGGER TO ASSIGN VENDOR ROLE
CREATE TRIGGER auth_register_vendors AFTER INSERT ON Vendors FOR EACH ROW
    BEGIN
        INSERT INTO Users (username, roleID)
        VALUES (NEW.vUsername, 1);
    END $$
DELIMITER ;
-- ------ ROLES TRIGGERS ---------


-- ------------------------------------ DISTANCE --------------------------------------
-- DISTANCE CALCULATION FUNCTION
DELIMITER $$
CREATE FUNCTION CalcDistance (latC DOUBLE, longC DOUBLE, latH DOUBLE, longH DOUBLE)
RETURNS DOUBLE
    DETERMINISTIC
BEGIN
    IF latC = latH AND longC = longH THEN RETURN 0;
    ELSE
      BEGIN
        DECLARE longDelta DOUBLE;
        DECLARE a DOUBLE;
        DECLARE b DOUBLE;
        DECLARE angle DOUBLE;
        DECLARE result DOUBLE;

        SET latC = RADIANS(latC);
        SET latH = RADIANS(latH);
        SET longC = RADIANS(longC);
        SET longH = RADIANS(longH);
        SET longDelta = longH - longC;
        SET a = POW(COS(latH) * SIN(longDelta), 2) + POW(COS(latC)*SIN(latH)-SIN(latC)*COS(latH)*COS(longDelta), 2);
        SET b = SIN(latH)*SIN(latC) + COS(latC)*COS(latH)*COS(longDelta);
        SET angle = ATAN2(SQRT(a), b);

        SET result = angle*6371;
        RETURN result;
      END;
    END IF;
END $$
DELIMITER ;


-- TRIGGER FOR ASSIGNING HUB TO CUSTOMER
DELIMITER $$
CREATE TRIGGER before_insert_customer BEFORE INSERT ON Customers FOR EACH ROW
    BEGIN
        DECLARE hub INT;
        SELECT Hubs.hubID INTO hub FROM Hubs ORDER BY CalcDistance(NEW.cLat, NEW.cLong, hLat, hLong) ASC LIMIT 1;
        SET NEW.hubID = hub;
    END $$
DELIMITER ;

-- TRIGGER FOR RE-ASSIGN HUB TO UPDATED CUSTOMER LATITUDE, LONGITUDE (BEFORE UPDATE)
DELIMITER $$
CREATE TRIGGER before_update_customer BEFORE UPDATE ON Customers FOR EACH ROW
    BEGIN
        DECLARE result INT;
        IF ((NEW.cLat <> OLD.cLat) OR (NEW.cLong <> OLD.cLong) OR (OLD.hubID = 0) OR (NEW.hubID = 0)) THEN
            SELECT Hubs.hubID INTO result FROM Customers, Hubs WHERE cUsername = OLD.cUsername ORDER BY CalcDistance(cLat, cLong, hLat, hLong) ASC LIMIT 1;
            SET NEW.hubID = result;
        END IF;
    END $$
DELIMITER ;


-- TRIGGER FOR ASSIGNING HUB TO NEWLY CREATED VENDOR (BEFORE INSERT)
DELIMITER $$
CREATE TRIGGER before_insert_vendor BEFORE INSERT ON Vendors FOR EACH ROW
    BEGIN
        DECLARE hub INT;
        SELECT Hubs.hubID INTO hub FROM Hubs ORDER BY CalcDistance(NEW.vLat, NEW.vLong, hLat, hLong) ASC LIMIT 1;
        SET NEW.hubID = hub;
    END $$
DELIMITER ;

-- TRIGGER FOR RE-ASSIGN HUB TO UPDATED VENDOR LATITUDE, LONGITUDE (BEFORE UPDATE)
DELIMITER $$
CREATE TRIGGER before_update_vendor BEFORE UPDATE ON Vendors FOR EACH ROW
    BEGIN
        DECLARE result INT;
        IF ((NEW.vLat <> OLD.vLat) OR (NEW.vLong <> OLD.vLong) OR (OLD.hubID = 0) OR (NEW.hubID = 0)) THEN
            SELECT Hubs.hubID INTO result FROM Vendors, Hubs WHERE vUsername = OLD.vUsername ORDER BY CalcDistance(vLat, vLong, hLat, hLong) ASC LIMIT 1;
            SET NEW.hubID = result;
        END IF;
    END $$
DELIMITER ;
-- ------------------------------------ DISTANCE --------------------------------------



-- ------------------------------------ TRANSACTION ROW LOCKING --------------------------------------
-- PROCEDURE FOR UPDATE ORDER TRANSACTION LOCK
DROP PROCEDURE IF EXISTS update_order;
DELIMITER $$
CREATE PROCEDURE update_order(IN order_status VARCHAR(20), IN order_id INT)
BEGIN
    START TRANSACTION;
        SELECT * FROM Orders WHERE orderID = order_id FOR UPDATE;
        UPDATE Orders SET oStatus = order_status WHERE orderID = order_id;
    COMMIT;
END $$
DELIMITER ;

-- PROCEDURE FOR BUY PRODUCT (INSERT NEW ORDER) TRANSACTION LOCK
DROP PROCEDURE IF EXISTS create_order;
DELIMITER $$
CREATE PROCEDURE create_order(IN order_status VARCHAR(20), IN customer_ID INT, IN vendor_ID INT, IN product_ID INT)
BEGIN
    START TRANSACTION;
        SELECT * FROM Products WHERE productID = product_ID FOR SHARE;
        INSERT Orders SET oStatus = order_status, customerID = customer_ID, vendorID = vendor_ID, productID = product_ID;
    COMMIT;
END $$
DELIMITER ;
-- -------------- TRANSACTION ROW LOCKING --------------------------------------