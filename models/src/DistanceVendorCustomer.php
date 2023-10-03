<?php
	class DistanceVendorCustomer {

		// Connection
		private $conn;
		public $err;

		public function __construct($db) {
			$this->conn = $db;
		}
		
		/**
		 * Calculates the great-circle distance between two points, with
		 * the Vincenty formula.
		 * @param float $latitudeFrom Latitude of start point in [deg decimal]
		 * @param float $longitudeFrom Longitude of start point in [deg decimal]
		 * @param float $latitudeTo Latitude of target point in [deg decimal]
		 * @param float $longitudeTo Longitude of target point in [deg decimal]
		 * @param float $earthRadius Mean earth radius in [km]
		 * @return float Distance between points in [km] (same as earthRadius)
		 */
		public static function vincentyGreatCircleDistance(
			$latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
		{
			// convert from degrees to radians
			$latFrom = deg2rad($latitudeFrom);
			$lonFrom = deg2rad($longitudeFrom);
			$latTo = deg2rad($latitudeTo);
			$lonTo = deg2rad($longitudeTo);
		
			$lonDelta = $lonTo - $lonFrom;
			$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
			$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
		
			$angle = atan2(sqrt($a), $b);
			return $angle * $earthRadius; // Unit: km
		}

		public function getCustomerCoordinates($id){
			$sqlQuery = "SELECT
							cLong, cLat
						FROM
							customers_hidepass
						WHERE 
							customerID = ". $id ;
		
			try {
				$stmt = $this->conn->prepare($sqlQuery);
				$stmt->execute();
				$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $array;
			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return NULL;
			}
		}


		public function getVendorsByDistance($searchRadius, $customerID){
			$cCoordinates = $this->getCustomerCoordinates($customerID);
			$cLong = floatval($cCoordinates[0]['cLong']);
			$cLat = floatval($cCoordinates[0]['cLat']);

			// query vendors
			$sqlQuery = "SELECT
							vendorID, 
							vName, 
							vLong, 
							vLat,
							vAddress
						FROM
							vendors_hidepass";
							
			try {
				$stmt = $this->conn->prepare($sqlQuery);
				$stmt->execute();
				
				// list of vendors
				$array = $stmt->fetchAll(PDO::FETCH_ASSOC); // get all vendors

				$foundVendors = [];
				foreach($array as $vendor){

					$distanceToVendor = $this->vincentyGreatCircleDistance($cLat,$cLong,$vendor['vLat'],$vendor['vLong']);
					if ( $distanceToVendor < $searchRadius) {
						$vendorFound = $vendor;
						$vendorFound['distance'] = $distanceToVendor;
						$foundVendors[] = $vendorFound;
					}
				}
				return array( "vendors" => $foundVendors);
			} catch (PDOException $e) {
				$this->err = $e->getMessage();
				return false;
			}
		}
		
	}
?>

