<?php
	class Shipper {
		// Connection
		private $conn;

		public $cName;
		public $vName;
		public $hName;
		public $pname;

		public $err;

		// Db connection
		public function __construct($db)
		{
			$this->conn = $db;
		}

		public function getHub($shipperID)
		{
			$sqlQuery = "SELECT 
				hubID
			FROM 
				shippers_hidepass
			WHERE 
				shipperID = :shipperID";

			$shipperID = htmlspecialchars(strip_tags($shipperID));

			try {
				$stmt = $this->conn->prepare($sqlQuery);
				// bind data
				$stmt->bindParam(":shipperID", $shipperID, PDO::PARAM_INT);

				$stmt->execute();
				$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
				return $dataRow;
			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}

		// GET ALL ORDERS

		public function getOrders($shipperID)
		{

			$hubObject = $this->getHub($shipperID);
			$hubID = $hubObject['hubID'];
			
			$sqlQuery = "SELECT
			orderID, 
			oStatus, 
			cName, 
			pName,
			Customers.hubID,
			Orders.customerID, 
			Orders.vendorID, 
			Orders.productID,
			Orders.dateCreated
			FROM
				Orders
			INNER JOIN 
				Customers
			ON 
				Orders.customerID = Customers.customerID
 			INNER JOIN 
 				Products
 			ON 
 				Orders.productID = Products.productID
			WHERE 
				Customers.hubID = :hubID
			ORDER BY
				Orders.dateCreated DESC";

			$hubID = htmlspecialchars(strip_tags($hubID));
			
			try {
				$stmt = $this->conn->prepare($sqlQuery);
				// bind data
				$stmt->bindParam(":hubID", $hubID, PDO::PARAM_INT);

				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}

		//READ SINGLE ORDER
		public function getSingleOrder($orderID)
		{
			$sqlQuery = "SELECT
								orderID, 
								oStatus, 
								cName, 
								vName, 
								hName,
								pName,
								Orders.customerID, 
								Orders.vendorID, 
								Orders.hubID,
								Orders.productID
							FROM
								Orders
							INNER JOIN 
								Customers
							ON 
								Orders.customerID = Customers.customerID
							INNER JOIN 
								Vendors
							ON 
								Orders.vendorID = Vendors.vendorID
							INNER JOIN 
								Hubs
							ON 
								Orders.hubID = Hubs.hubID
							INNER JOIN 
								Products
							ON 
								Orders.productID = Products.productID
							WHERE 
								orderID = :orderID for share";
			$orderID = htmlspecialchars(strip_tags($orderID));


			try {
				$stmt = $this->conn->prepare($sqlQuery);
				// bind data
				$stmt->bindParam(":orderID", $orderID, PDO::PARAM_INT);
				$stmt->execute();
				$dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

				return $dataRow;
			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}

		// UPDATE
		public function updateOrder($oStatus, $orderID) {
			$sql = "CALL update_order(:oStatus, :orderID)";
			$oStatus = htmlspecialchars(strip_tags($oStatus));
			$orderID = htmlspecialchars(strip_tags($orderID));

			try {
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(":oStatus", $oStatus);
				$stmt->bindParam(":orderID", $orderID, PDO::PARAM_INT);	
				$stmt->execute();

			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}
	}
?>