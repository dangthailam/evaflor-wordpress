<?php
include_once './securityKey.php';
include_once './kreyviumCipher.php';

class TestSecurity {

	private $array_UID = array(0x4D, 0x5E, 0x10, 0x11, 0x13, 0x4F, 0x2C);
	
	/*
	 * This month can get from parameter of command, 
	 * don't make any compute in nfc read/write function, it can cause a supplement delay
	 */
	private $currentMonth = 10; 
	
	/*
	 * This function show that for each loop, a new encrypted array will be created
	 * **/
	public function testEncryption1($numberofLoop, $myLastIndexOffset)
	{
		/**
		   * Initializing Secret key...
		   */
		$sk = new SecurityKey($this->currentMonth); 
		
		/**
		  * Initializing Kreyvium Stream Cipher...
		  */
		$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		$tmp_array = array_fill(0, 7, 0x00);
		for($loop = 0; $loop < $numberofLoop; $loop++)
		{
			/*
			 * Duplicating the array_uid**/
			
			for ($i = 0; $i < count($this->array_UID); $i++)
			{
				$tmp_array[$i] = $this->array_UID[$i];
			}
			echo "Starting encrypting ....... <br>";
			echo "Msg before encryption = ";
			$cipher->printArray($this->array_UID);
			echo "<br>";
			/**
		   * Encrypting with Kreyvium Stream Cipher with 8 new tokens taken 
		   * from myLastIndexOffset...
		   */			
		    
			$tmp_array = $cipher->encryptingRequest($this->array_UID);
			echo "<br> Encrypted result:";
			
			$sk->printArray($tmp_array);
			
			echo "<br>";
			//$sk->printArray($this->array_UID);
			echo "<br>";
			echo "After encrypting of loop : $loop <br>";
			for ($i = 0; $i < count($this->array_UID); $i++)
			{
				//echo " " + $tmp_array[$i];
			}
			echo "<br>";
			$msg = $cipher->decryptingRequest($tmp_array);
			echo "Original msg = ";
			$sk->printArray($msg);
			echo "<br>";
			
		}
		/*
		 * This step is important !
		 * in order to continue generate next token
		 * **/
		$myLastIndexOffset = $cipher->getMySaveOffset();
		print "myLastIndexOffset = $myLastIndexOffset \n";
	}
	
	
	
	/*
	 * This function show that for each loop, a new encrypted array will be created
	 * **/
	public function testEncryption2($numberofLoop, $myLastIndexOffset, $array_UID, $currentMonth)
	{
		/**
		   * Initializing Secret key...
		   */
		$sk = new SecurityKey($currentMonth); 
		
		/**
		  * Initializing Kreyvium Stream Cipher...
		  */
		$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		
		$encryptResult = "";
		
		for($loop = 0; $loop < $numberofLoop; $loop++)
		{
			/*
			 * Duplicating the array_uid**/
			$tmp_array = array(7);
			
			$encryptResult = "";
			
			
			for ($i = 0; $i <count($array_UID); $i++)
			{
				$tmp_array[$i] = $array_UID[$i];
			}
			echo "Starting encrypting ....... <br>";
			/**
		   * Encrypting with Kreyvium Stream Cipher with 8 new tokens taken 
		   * from myLastIndexOffset...
		   */			
			$tmp_array = $cipher->encryptingRequest($array_UID);
		
			echo "After encrypting of loop : " + $loop + "<br>";
			for ($i = 0; $i < count($array_UID); $i++)
			{
				echo " " + $tmp_array[$i];
			}
			
			echo "";
			
			
			
			//$encryptResult = hex2str($tmp_array);
			echo "<br>";
			echo "encryptResult : " + $encryptResult;
		}
		/*
		 * This step is important !
		 * in order to continue generate next token
		 * **/
		$myLastIndexOffset = $cipher->getMySaveOffset();
		print "myLastIndexOffset = " + $myLastIndexOffset;
		
		return $encryptResult;
	}
	
	
	
	public function hex2str($hex) {
		$str = "";
		for($i=0;$i<strlen($hex);$i+=2)
			$str .= chr(hexdec(substr($hex,$i,2)));
		
		return $str;
	}
	
	public function getCurrentMonth() {
		return $this->currentMonth;
	}
	
	
	
	public function encryptMsg($msg, $myLastIndexOffset, $month) {
		/**
		   * Initializing Secret key...
		   */
		$sk = new SecurityKey($month); 
		
		/**
		  * Initializing Kreyvium Stream Cipher...
		  */
		$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		
		
		$encryptedRS = $cipher->encryptingRequest($msg);
		
		echo "original msg = ";
		$cipher->printArray($msg);
		
		echo "<br>";
		echo "DEBUG: encrypted msg = ";
		$cipher->printArray($encryptedRS);
		
		return $encryptedRS;
		
	}
	
	public function decryptMsg($encryptedMsg, $myLastIndexOffset, $month) {
		/**
		   * Initializing Secret key...
		   */
		$sk = new SecurityKey($month); 
		
		/**
		  * Initializing Kreyvium Stream Cipher...
		  */
		$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		
		$msgDecrypted = $cipher->encryptingRequest($encryptedMsg);
		echo "<br>";
		echo "DEBUG: Decrypted msg =";
		$cipher->printArray($msgDecrypted);
		
		
	}

	public function testSecurity3($myLastIndexOffset, $month) {
		/**
		   * Initializing Secret key...
		   */
		$sk = new SecurityKey($month); 
		
		/**
		  * Initializing Kreyvium Stream Cipher...
		  */
		$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		$array_uid = array(0x4D, 0x5E, 0x10, 0x11, 0x13, 0x4F, 0x2C);
		$cipher->printArray($array_uid);
		echo "<br>";
		echo "Encrypted msg = ";
		$encryptRS = $cipher->encryptMsg($array_uid, 9);
		$cipher->printArray($encryptRS);
		
		echo "<br>";
		echo "Decrypted msg = ";
		$cipherDec = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$myLastIndexOffset);
		echo "Encrypted RS = ";
		$cipher->printArray($encryptRS);
		$decryptedRS = $cipherDec->decryptMsg($encryptRS, 9);
		$cipherDec->printArray($decryptedRS);
	}
	
	public function printArray($array) {
		for ($i = 0; $i < count($array); $i++) {
			echo $array[$i];
		}
		echo "<br>";
	}
}

$test = new TestSecurity();
$array_UID = array(0x4D, 0x5E, 0x10, 0x11, 0x13, 0x4F, 0x2C);
//echo $test->testEncryption2(1, 10, $array_UID, 10);

$rs = $test->encryptMsg($array_UID, 8, 10);
$test->decryptMsg($rs, 8, 10);
//$test->printArray($array_UID);
//$test->testSecurity3(10, 10);
?>	