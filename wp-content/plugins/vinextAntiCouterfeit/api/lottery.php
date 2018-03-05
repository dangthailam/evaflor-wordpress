<?php
	class Lottery {
		private $conn = null;
        private $table_name = "lottery";
		
		private $clientName;
		private $email;
		private $zipCode;
		private $lotteryNumber;
		private $lotteryDate;
		
		//constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
		
		function prepareDataToInsert($AclientName, $AnEmail, $AZipCode, $ALotteryNumber, $ALotteryDate) {
			$this->clientName = $AclientName;
			$this->email = $AnEmail;
			$this->zipCode = $AZipCode;
			$this->lotteryNumber = $ALotteryNumber;
			$this->lotteryDate = $ALotteryDate;
		}
		//insert products
        function saveLotteryInfo(){
 
            // query to insert record
            $query = "INSERT INTO
                        " . $this->table_name . "
                    SET
                    clientName=:clientName, email=:email, zipCode=:zipCode, lotteryNumber=:lotteryNumber, lotteryDate=:lotteryDate";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->clientName=htmlspecialchars(strip_tags($this->clientName));
            $this->email=htmlspecialchars(strip_tags($this->email));
			$this->zipCode=htmlspecialchars(strip_tags($this->zipCode));
			$this->lotteryNumber=htmlspecialchars(strip_tags($this->lotteryNumber));
			$this->lotteryDate=htmlspecialchars(strip_tags($this->lotteryDate));
			
            // bind values
            $stmt->bindParam(":clientName", $this->clientName);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":zipCode", $this->zipCode);
            $stmt->bindParam(":lotteryNumber", $this->lotteryNumber);
			$stmt->bindParam(":lotteryDate", $this->lotteryDate);
            
        
            // execute query
            if($stmt->execute()){
                return 'success';
            }else{
                return 'error';
            }
        }
		
		function loadAllParticipants() {
            $query = "SELECT * FROM lottery";
            
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
                    $data .= '"name":"' . $clientName . '",';
                    $data .= '"email":"' . $email . '",';
                    $data .= '"loto":"' . $lotteryNumber . '",';
                    $data .= '"codepostal":"' . $zipCode . '",';
                    $data .= '"date":"' . $lotteryDate . '"';
                    $data .= '}';

                    $data .= $x<$num ? ',' : '';
                    $x++;
                }
                //json format output
                echo "[{$data}]";
            }
            
            return true;
        }
		
	

	function getWinnerFromLotteryNumber($lotteryNb) {
		$query = "SELECT clientName FROM 
                        " . $this->table_name . "
                    WHERE 
                    lotteryNumber=:lotteryNumber";
		
		// prepare query
        $stmt = $this->conn->prepare($query);
		
		$this->lotteryNumber = $lotteryNb;
		$this->lotteryNumber=htmlspecialchars(strip_tags($this->lotteryNumber));
		$stmt->bindParam(":lotteryNumber", $this->lotteryNumber);
		
		$winnerName = "";
		if($stmt->execute()) {
		} else {
			return 'error';
		}
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$winnerName = $row['clientName'];
		}
		
		return $winnerName;
	}
}
?>