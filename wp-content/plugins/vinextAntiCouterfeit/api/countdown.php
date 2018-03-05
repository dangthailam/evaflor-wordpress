<?php
class Countdown {
		private $conn = null;

		public $table = "countdown";
        public $productId;
		public $dateCountdown;
		
		public function __construct($db){
            $this->conn = $db;
        }
		
		function triggerCountdown($productId) {
			$query = "INSERT INTO " . $this->table . " (productId, dateCountdown) VALUES(:productId, :dateCountdown)";
			
			// prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->productId= htmlspecialchars(strip_tags($productId));
            $currentDate = date('Y-m-d');
			$this->dateCountdown = htmlspecialchars(strip_tags($currentDate));
			
            // bind values
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':dateCountdown', $this->dateCountdown);
			
            // execute query
            if($stmt->execute()){
                return 'success';
            }else{
                return 'error';
            }
		}
		
		function isProductCountdownActivated($productId, $countdownPeriod) {
			$query = "SELECT dateCountdown from " . $this->table . " WHERE productId=:productId";
			
			// prepare query
			$stmt = $this->conn->prepare($query);
			
			// sanitize
			$this->productId= htmlspecialchars(strip_tags($productId));
			
			// bind values
			$stmt->bindParam(':productId', $this->productId);
			
			// execute query
			if($stmt->execute()){
				$triggeredDate = "";
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$triggeredDate = $row['dateCountdown'];
				}
				
				if ($triggeredDate == "" || $triggeredDate == null) {
					return 'inactivated';
				}
				
				$currentDate = date("Y-m-d");
				
				
				$date1 = date_create($triggeredDate);
				$date2 = date_create($currentDate);
				$diff = date_diff($date1, $date2);
				$diff = $diff->format("%a");
				if ($diff < $countdownPeriod) {
					return 'activated';
				} else {
					return 'invalid';
				}
            } else{
                return 'error';
            }
		}
}

?>