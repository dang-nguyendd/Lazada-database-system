<?php

    class UserRegistration {
        private $name;
        private $username;
        private $password;
        private $role;
        private $address;
        private $long;
        private $lat;
        private $hubID;
        public $err;

        public function __construct($name, $username, $password, $role, $address, $long, $lat, $hubID) {
            $this->name = htmlspecialchars(strip_tags($name));
            $this->username = htmlspecialchars(strip_tags($username));
            $this->password = password_hash(htmlspecialchars(strip_tags($password)), PASSWORD_BCRYPT);
            $this->role = htmlspecialchars(strip_tags($role));
            $this->address = htmlspecialchars(strip_tags($address));
            $this->long = floatval(htmlspecialchars(strip_tags($long)));
            $this->lat = floatval(htmlspecialchars(strip_tags($lat)));
            $this->hubID = floatval(htmlspecialchars(strip_tags($hubID)));
        }

        public function checkUserExist($conn) {
            $sql = "SELECT * FROM Users WHERE username = :username";
            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":username", $this->username);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return true; // User does exist
                } else {
                    return false; // User does not exist
                }
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return $this->err;
            }
            
            
        }

        public function signup($conn) {
            try {
                if ($this->role == 'Vendor') {
                    // $conn->beginTransaction();
                    $sql = "INSERT INTO Vendors SET
                    vName = :fullname,
                    vUsername = :username,
                    vPassword = :userpass,
                    vAddress = :userAddress,
                    vLong = :longitude,
                    vLat = :latitude,
                    hubID = 0";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":fullname", $this->name);
                    $stmt->bindParam(":username", $this->username);
                    $stmt->bindParam(":userpass", $this->password); 
                    $stmt->bindParam(":userAddress", $this->address); 
                    $stmt->bindParam(":longitude", $this->long); 
                    $stmt->bindParam(":latitude", $this->lat); 
                    $stmt->execute();

                } elseif ($this->role == 'Customer') {
                    $sql = "INSERT INTO Customers SET
                    cName = :fullname,
                    cUsername = :username,
                    cPassword = :userpass,
                    cAddress = :userAddress,
                    cLong = :longitude,
                    cLat = :latitude,
                    hubID = 0";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":fullname", $this->name);
                    $stmt->bindParam(":username", $this->username);
                    $stmt->bindParam(":userpass", $this->password); 
                    $stmt->bindParam(":userAddress", $this->address); 
                    $stmt->bindParam(":longitude", $this->long); 
                    $stmt->bindParam(":latitude", $this->lat);
                    $stmt->execute();
                } else {
                    $sql = "INSERT INTO Shippers SET
                    sUsername = :username,
                    sPassword = :userpass,
                    hubID = :hubid";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":username", $this->username);
                    $stmt->bindParam(":userpass", $this->password);
                    $stmt->bindParam(":hubid", $this->hubID);
                    $stmt->execute();
                }
                return true;
            } catch (PDOException $e) {
                $this->err = $e->getMessage();
                return false;
            }
        }
    }
?>