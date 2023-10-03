<?php
	class Customer {

		private $conn;
		public $err;

		public function __construct($conn){
			$this->conn = $conn;
		}

		public function createOrderTransaction($oStatus, $customerID,  $vendorID, $productID) {
			$sql = "CALL create_order(:oStatus, :customerID, :vendorID, :productID)";
			$oStatus=htmlspecialchars(strip_tags($oStatus));
			$customerID=htmlspecialchars(strip_tags($customerID));
			$vendorID=htmlspecialchars(strip_tags($vendorID));
			$productID=htmlspecialchars(strip_tags($productID));

			try {
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(":oStatus", $oStatus);
				$stmt->bindParam(":customerID", $customerID, PDO::PARAM_INT);
				$stmt->bindParam(":vendorID", $vendorID, PDO::PARAM_INT);
				$stmt->bindParam(":productID", $productID, PDO::PARAM_INT);
				$stmt->execute();
				return true;
			} catch (Exception $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}
		
		// public function createOrderTransaction($oStatus, $customerID,  $vendorID, $productID){
		// 	try {
		// 		$this->conn->beginTransaction();
		// 		$sqlQuery = "INSERT INTO
		// 					Orders
		// 				SET
		// 					oStatus = :oStatus, 
		// 					customerID = :customerID, 
		// 					vendorID = :vendorID, 
		// 					hubID = 0,
		// 					productID = :productID";

		// 		$stmt = $this->conn->prepare($sqlQuery);

		// 		// sanitize

		// 		$oStatus=htmlspecialchars(strip_tags($oStatus));
		// 		$customerID=htmlspecialchars(strip_tags($customerID));
		// 		$vendorID=htmlspecialchars(strip_tags($vendorID));
		// 		// $hubID=htmlspecialchars(strip_tags($hubID));
		// 		$productID=htmlspecialchars(strip_tags($productID));

		// 		// bind data

		// 		$stmt->bindParam(":oStatus", $oStatus);
		// 		$stmt->bindParam(":customerID", $customerID);
		// 		$stmt->bindParam(":vendorID", $vendorID);
		// 		// $stmt->bindParam(":hubID", $hubID);
		// 		$stmt->bindParam(":productID", $productID);

		// 		$stmt->execute();

		// 		// Select product
		// 		$sqlQueryProduct = "Select * FROM products
		// 				where productID = :productID for share";

		// 		$stmt = $this->conn->prepare($sqlQueryProduct);

		// 		$stmt->bindParam(":productID", $productID);

		// 		$stmt->execute();

		// 		// Sleep here
		// 		$sleepTime = rand(10,30);
		// 		$sqlQuery1 = "Select Sleep($sleepTime)";

		// 		$stmt = $this->conn->prepare($sqlQuery1);

		// 		$stmt->execute();

		// 		$this->conn->commit();
				

		// 		return true;
		// 	} catch (PDOException $e) {
		// 		$this->conn->rollBack();
		// 		$this->err = $e->getMessage();
		// 		return false;
		// 	}
		// }
		
	}
?>

