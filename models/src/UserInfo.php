<?php

    class UserInfo {
        private $conn;
        public $err;
        public function __construct($dbConn){
            $this->conn = $dbConn;
        }

        public function getVendorInfo($id) {
            $user = floatval(htmlspecialchars(strip_tags($id)));
            $sql = "SELECT * FROM vendors_hidepass WHERE vendorID = :vendorID";
    
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":vendorID", $user);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
            
        }
        public function getCustomerInfo($id) {
            $user = floatval(htmlspecialchars(strip_tags($id)));
            $sql = "SELECT * FROM customers_hidepass WHERE customerID = :customerID";
    
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":customerID", $user);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
            
        }
        public function getShipperInfo($id) {
            $user = floatval(htmlspecialchars(strip_tags($id)));
            $sql = "SELECT * FROM shippers_hidepass WHERE shipperID = :shipperID";
    
            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":shipperID", $user);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
            
        }
    }

?>