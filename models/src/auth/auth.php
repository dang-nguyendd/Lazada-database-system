<?php
    // require_once '../vendor/autoload.php';
    // use Firebase\JWT\JWT;
    class UserAuthentication {
        private $id;
        private $username;
        private $password;
        private $role;
        private $jwt;
        private $user;

        public function __construct($username, $password) {
            $this->username = htmlspecialchars(strip_tags($username));
            $this->password = htmlspecialchars(strip_tags($password));
        }

        public function login($userTableName, $usrCol, $pwdCol, $conn) {
            $sql = "SELECT * FROM $userTableName WHERE $usrCol = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $this->username);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $userIDCol = strtolower(substr($userTableName,0,strlen($userTableName)-1)) . "ID";

            if ($userData && password_verify($this->password, $userData[$pwdCol]) == true) {

                $this->id = $userData[$userIDCol];
                return true;

            } else {
                return false;
            }

        }

        public function checkUserAndRole($conn) {
            $sql = "SELECT * FROM Users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $this->username);
            $stmt->execute();
            $user_details = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_details) {
                if ($user_details['roleID'] == 1) {
                    $this->role = 'Vendor';
                } elseif ($user_details['roleID'] == 2) {
                    $this->role = 'Customer';
                } else {
                    $this->role = 'Shipper';
                }
                return true;

            } else {
                return false;
            }
        }

        public function getUserJWT() {
            return $this->jwt;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getRole() {
            return $this->role;
        }

        public function getPWD() {
            return $this->password;
        }

        public function getID() {
            return $this->id;
        }
    }

?>