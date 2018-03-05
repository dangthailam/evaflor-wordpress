<?php
class VisitorGeolocation {
		private $conn = null;

		public $table = "visitor_geolocation";
		public $tmp_table = "temporalLocation";
        public $productId;
		public $isValid;
		public $city;
		public $country;
		public $IP;
		public $latitude;
		public $longitude;
		public $accuracyRadius;
		public $sessionId;
		
		public function __construct($db){
            $this->conn = $db;
        }
		
		function insertVisitorGeolocation($productId, $isValid, $city, $country, $IP, $latitude, $longitude, $accuracyRadius, $sessionID) {
			$query = "INSERT INTO " . $this->table . " (productId, isValid, city, country, IP, latitude, longitude, accuracyRadius, sessionid) VALUES(:productId, :isValid, :city, :country, :IP, :latitude, :longitude, :accuracyRadius, :sid)";
			
			// prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->productId=htmlspecialchars(strip_tags($productId));
            $this->isValid=htmlspecialchars(strip_tags($isValid));
			$this->city=htmlspecialchars(strip_tags($city));
			$this->country=htmlspecialchars(strip_tags($country));
			$this->IP=htmlspecialchars(strip_tags($IP));
			$this->latitude=htmlspecialchars(strip_tags($latitude));
			$this->longitude=htmlspecialchars(strip_tags($longitude));
			$this->accuracyRadius=htmlspecialchars(strip_tags($accuracyRadius));
			$this->sessionId=htmlspecialchars(strip_tags($sessionID));
			
            // bind values
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':isValid', $this->isValid);
            $stmt->bindParam(':city', $this->city);
            $stmt->bindParam(':country', $this->country);
			$stmt->bindParam(':IP', $this->IP);
            $stmt->bindParam(':latitude', $this->latitude);
			$stmt->bindParam(':longitude', $this->longitude);
			$stmt->bindParam(':accuracyRadius', $this->accuracyRadius);
			$stmt->bindParam(':sid', $this->sessionId);
            // execute query
            if($stmt->execute()){
                return 'success';
            }else{
                return 'error';
            }
		}
		
		function getVisitorLocationFromSessionID($sessionID) {
			$query = "SELECT latitude, longitude FROM " . $this->table . " WHERE sessionid=:sid";
			// prepare query
            $stmt = $this->conn->prepare($query);
			$sid = htmlspecialchars(strip_tags($sessionID));
			// bind values
            $stmt->bindParam(':sid', $sid);
            // execute query
            if(!$stmt->execute()){
                return 'error';
            }
		
			$loc = "";
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$loc = $row['latitude'] . ":" . $row['longitude'];
			}
			
			return $loc;
			
		}
		function loadAllVisitorGeolocation() {
			$query = "SELECT * from " .$this->table;
			
			// prepare query
			$stmt = $this->conn->prepare($query);
			
			$stmt->execute();
            
            $num = $stmt->rowCount();

            //check if more than 0 record is found
            if($num>0){
				$data = "";
                $x = 1;

                //retreive table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    //extract row
                    extract($row);

                    $data .= '{';
                    $data .= '"id":"' . $id . '", ';
                    $data .= '"name":"' . $productId . '",';
                    $data .= '"email":"' . $isValid . '",';
                    $data .= '"loto":"' . $city . '",';
                    $data .= '"codepostal":"' . $country . '",';
                    $data .= '"date":"' . $IP . '"';
					$data .= '"date":"' . $latitude . '"';
					$data .= '"date":"' . $longitude . '"';
					$data .= '"date":"' . $accuracyRadius . '"';
                    $data .= '}';

                    $data .= $x<$num ? ',' : '';
                    $x++;
                }
                //json format output
                echo "[{$data}]";
			
			}
		}

		function insertTemporalUserLocation($lat, $lon, $sid) {
			$query = "INSERT INTO " .$this->tmp_table. " (latitude, longitude, sessionid) VALUES(:lat, :lon, :sid)";
			
			// prepare query
            $stmt = $this->conn->prepare($query);
			
			$this->latitude=htmlspecialchars(strip_tags($lat));
			$this->longitude=htmlspecialchars(strip_tags($lon));
			$this->sessionId=htmlspecialchars(strip_tags($sid));
			
			// Bind the values
			$stmt->bindParam(':lat', $this->latitude);
			$stmt->bindParam(':lon', $this->longitude);
			$stmt->bindParam(':sid', $this->sessionId);
			
			// execute query
            if($stmt->execute()){
                return 'success';
            }else{
                return 'error';
            }
		}
		
		function getTemporalUserLocation($sessionID) {
			$query = "SELECT latitude, longitude FROM " . $this->tmp_table. " WHERE sessionid = :sid";
			
			// prepare query
            $stmt = $this->conn->prepare($query);
			
			$sid = htmlspecialchars(strip_tags($sessionID));
			$stmt->bindParam(':sid', $sid);
			if(!$stmt->execute()) {
				return 'error';
			}
			$loc = "";
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$loc = $row['latitude'] . ":" . $row['longitude'];
			}
			
			return $loc;
			
		}
		
		function deleteTemporalUserLocation($sessionID) {
			$query = "DELETE FROM " . $this->tmp_table . " WHERE sessionid =:sid";
			// prepare query
            $stmt = $this->conn->prepare($query);
			
			$sid = htmlspecialchars(strip_tags($sessionID));
			$stmt->bindParam(':sid', $sid);
			if(!$stmt->execute()) {
				return 'error';
			} else {
				return 'success';
			}
		}
		
		function getLastTemporalUserLocation() {
			$getLastIdQuery = "SELECT max(id) from " .$this->tmp_table;
			// prepare query
            $stmt = $this->conn->prepare($getLastIdQuery);
			$stmt->execute();
			$maxId = 1;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$maxId = $row['id'];
			}
			
			$query = "Select latitude, longitude FROM " .$this->tmp_table. " WHERE id = " . $maxId;
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$lat = '';
			$lon = '';
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$lat = $row['latitude'];
				$lon = $row['longitude'];
			}
			
			$data .= '{';
			$data .= '"lat":"' . $lat . '", ';
			$data .= '"lon":"' . $lon . '",';
			$data .= '}';
			
			//json format output
			echo "[{$data}]";
		}
		
		function isLastUserLocationSavedWithSuccess() {
			$getLastIdQuery = "SELECT max(id) from " .$this->tmp_table;
			// prepare query
            $stmt = $this->conn->prepare($getLastIdQuery);
			$stmt->execute();
			$maxId = 1;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$maxId = $row['max(id)'];
			}
			
			$query = "Select latitude, longitude FROM " .$this->tmp_table. " WHERE id = :id";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':id', $maxId);
			
			$stmt->execute();
			$lat = "";
			$lon = "";
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$lat = $row['latitude'];
				$lon = $row['longitude'];
			}
			
			return $lat;
		}
}

?>