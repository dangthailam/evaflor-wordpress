<?php
class KreyviumCipher {

	private $monthOfYear;
	
	//constructor
	public function getInstanceFromMonthOfYear($monthOfYear){
            $this->monthOfYear = $monthOfYear;
	}
	
    public function __construct($my_key, $my_offset)
    {
		// Fill value as 0 by default
        $this->key = array_fill(0, 128, 0);
        $this->iv = array_fill(0, 128, 0);
		for ($i = 0; $i < 128; $i++)
        {
            $this->key[$i]  = $my_key[$i];
        }
        $this->b       = 0;
        $this->iv      = array_fill(0, 288, 0);
        $this->s       = array_fill(0, 288, 0);
        $this->rkey    = array_fill(0, 128, 0);
        $this->riv     = array_fill(0, 128, 0);
        $this->k 		= 0;
        $this->setMySaveOffset($my_offset);
        $this->byteOffset = 0;
        $this->heatUp();
		
		$nbrUsedBit = $this->mySaveOffset;
		$iBitIter = 0;
		
		
		while($iBitIter < $nbrUsedBit){
        		$this->nextKeystreamBit();
    			$iBitIter += 1;
        }
    }	
    
    public function encryptingRequest($request)
    {
		$key_stream_as_string = "";
		
        for($i=0;$i < count($request); $i++) {
            $this->k = $this->nextKeystreamByte();
            echo "DEBUG: Before XOR:  REQUESTi = $request[$i] AND K = $this->k \n";
			$key_stream_as_string += ($this->k + " ");
            $request[$i] ^= $this->k; // Now we'are encrypted...
			
        }
        //echo "DEBUG: key_stream_as_string =  $key_stream_as_string \n";
        return $request;
    }
    
	public function decryptingRequest($request)
    {        
        return $this->encryptingRequest($request);
    }
	
    /**
     * This function is only applied for data of 16 bit
     * */
    public function encryptingRequestFor16BitData($request)
    {
        return encryptingRequestWithBitSize($request, 16);
    }
	
	
    
	//$request: array of int
    public function encryptingRequestWithBitSize($request, $sizeBit)
    {
        $key_stream_as_string="";
        for($i = 0;$i < count($request); $i++) {
            $this->k = $this->nextKeystreamWithSizebits($sizeBit);
			$tmp_key = $this->toString($this->k);
            $key_stream_as_string += strval($tmp_key) + " ";
		    $request[i]^=$this->k; // Now we'are encrypted...
        }
		
        echo "DEBUG: key_stream_as_string = " . $key_stream_as_string . "\n";
        return $request;
    }

    /*
     * Caution ! We take 8bit to encrypt data. 
     * This information based on algorithm written 
     * in FHE form to decide what is kind of Integer8 or 16 will be used
     */
    public function nextKeystreamByte() {
        $b = 0;
		
        for($i = 0;$i < 8;$i++) {
        	
        	/*Warning ! generating a kreyvium token in the order of the most signification bit first 
             * so this formula
             * m_StoreTokenKreyviumChipher[m_LastConsumedTokenOffset] << i  
             * --> will provide error on the server side
             */
            $b |= ($this->nextKeystreamBit() << (8-$i-1) );
        }
        $this->byteOffset++;
        return $b;
    }
    
    public function nextKeystreamWithSizebits($sizebit) {
        $b=0;        
		for($i = 0;$i < $sizebit;$i++) {
        	/*Warning ! generating a kreyvium token in the order of the most signification bit first 
             * so this formula
             * m_StoreTokenKreyviumChipher[m_LastConsumedTokenOffset] << i  
             * --> will provide error on the server side
             */
            $b |= ($this->nextKeystreamBit() << ($sizebit - $i - 1) );
        }
        return $b;
    }

    public function nextKeystreamBit()
    {
        if($this->k==46) {                
            $this->nextIV();
            $this->heatUp();
            $this->k=0;
        }
        $this->k++;
        $t1 = $this->s[65] ^ $this->s[92];
        $t2 = $this->s[161] ^ $this->s[176];
        $t3 = $this->s[242] ^ $this->s[287] ^ $this->rkey[127-0];
        $z = $t1 ^ $t2 ^ $t3;
        $t1 = $t1 ^ $this->s[90] & $this->s[91] ^ $this->s[170] ^ $this->riv[127-0];
        $t2 = $t2 ^ $this->s[174] & $this->s[175] ^ $this->s[263];
        $t3 = $t3 ^ $this->s[285] & $this->s[286] ^ $this->s[68];
        $t4 = $this->rkey[127-0];
        $t5 = $this->riv[127-0];
        for($j = 0;$j < 288-1;$j++)
            $this->s[288-$j-1] = $this->s[288 - $j - 2];
        $this->s[0] = $t3;
        $this->s[93] = $t1;
        $this->s[177] = $t2;
        for($j = 0;$j < 128-1;$j++)
        {
            $this->rkey[128-$j-1] = $this->rkey[128 - $j - 2];
            $this->riv[128-$j-1] = $this->riv[128-$j-2];
        }
        $this->rkey[0] = $t4;
        $this->riv[0] = $t5;
		echo "Z = $z  \n";
        return $z;
    }

    // Strictly speaking, there is NO way we can practically cycle
    // through the 2^128 IV, so we purposedly do not give a damn
    // about loop termination...
    private function nextIV() {
        $j;
        $this->b = 1 - $this->b;
        if($this->b == 1)
            $j = 0;
        else
        {
            $j = 1;
            while($this->iv[$j-1]!=1)
                $j++;
        }
        $this->iv[$j]=1-$this->iv[$j];
    }

    // Reinit internal state and heat it up...
    private function heatUp() {

        // Internal state setup.
        for($i = 0;$i < 288;$i++)
            $this->s[$i]=1;
        // Insert the first 93 key bits @ position 0 (1 in the paper).
        for($i = 0;$i < 93;$i++)
            $this->s[$i] = $this->key[$i];
        // Insert the first 84 iv bits @ position 93 (94 in the paper).
        for($i = 0;$i < 84;$i++)
            $this->s[$i+93] = $this->iv[$i];
        // Insert the remaining iv bits @ position 177 (178 in the paper).
        for($i = 0;$i < 44;$i++)
            $this->s[$i+177] = $this->iv[$i+84];
        // Set pos 287 to 0 (288 in the paper).
        $this->s[287]=0;

        // RKEY register set up.
        for($i = 0;$i < 128;$i++)
            $this->rkey[$i] = $this->key[128-$i-1];
        // RIV register setup.
        for($i = 0;$i < 128;$i++)
            $this->riv[$i] = $this->iv[128-$i-1];

        for($i = 0;$i < 4*288;$i++) {
            $t1 = $this->s[65] ^ $this->s[90] & $this->s[91] ^ $this->s[92] ^ $this->s[170] ^ $this->riv[127-0];
            $t2 = $this->s[161] ^ $this->s[174] & $this->s[175] ^ $this->s[176] ^ $this->s[263];
            $t3=$this->s[242] ^ $this->s[285]& $this->s[286] ^ $this->s[287] ^ $this->s[68] ^ $this->rkey[127-0];
            $t4 = $this->rkey[127-0];
            $t5 = $this->riv[127-0];
            for($j=0;$j<288-1;$j++)
                $this->s[288-$j-1] = $this->s[288-$j-2];
            $this->s[0]=$t3;
            $this->s[93]=$t1;
            $this->s[177]=$t2;
            for($j=0;$j<128-1;$j++)
            {
                $this->rkey[128-$j-1] = $this->rkey[128-$j-2];
                $this->riv[128-$j-1]= $this->riv[128-$j-2];
            }
            $this->rkey[0] = $t4;
            $this->riv[0] = $t5;
        }
    }

    private function _isNeedHeatUpRunning()
    {
        for ($i = 0; $i < 128; $i++){
            if ($this->iv[$i] != 0)
                return false; 
        }
        return true;
    }

    public function setValueIV($index, $value)
    {
        $this->iv[$index] = $value;
    }

    public function getKey() {
		return $this->key;
	}

	function setKey($key) {
		$this->key = $key;
	}

	function getIv() {
		return $this->iv;
	}

	function setIv($iv) {
		$this->iv = $iv;
	}

	function getB() {
		return $this->b;
	}

	function setB($b) {
		$this->b = $b;
	}

	function getByteOffset() {
		return $this->byteOffset;
	}

	function setByteOffset($byteOffset) {
		$this->byteOffset = $byteOffset;
	}


	function getMySaveOffset() {
		return $this->mySaveOffset;
	}

	public function setMySaveOffset($mySaveOffset) {
		$this->mySaveOffset = $mySaveOffset;
	}

	public function print2DemensionArray($tmpArray) {
		for($r=0;$r<count($tmpArray);$r++)
{
			for($c=0;$c<count($tmpArray[$r]);$c++)
			{
				echo $tmpArray[$r][$c]."   ";
			}
			echo "<br>";
		}
	}
	
	public function print1DemensionArray($tmpArray, $index) {
		for($r=0;$r<count($tmpArray[$index]);$r++) {
			echo $tmpArray[$index][$r]."   ";
		}
	}
	
	public function printArray($array) {
		for ($i = 0; $i < count($array); $i++) {
			echo dechex($array[$i]);
		}
		echo "<br>";
	}
	
	public function encryptMsg($msg, $nbLoop) {
		for ($i = 0; $i < $nbLoop; $i++) {
			$this->k = $this->nextKeystreamByte();
		}
		
		for ($j = 0; $j < count($msg); $j++) {
			$this->k = $this->nextKeystreamByte();
            $msg[$j] ^= $this->k; // Now we'are encrypted...
		}
		return $msg;
	}
	
	public function decryptMsg($encryptedMsg, $nbLoop) {
		return $this->encryptMsg($encryptedMsg, $nbLoop);
	}
	
	
	public function encryptMsgInString($msgInString) {
		$msg = $this-> toByteArray($msgInString);
		$encryptedMsg = $this->encryptingRequest($msg);
		$this->printArray($encryptedMsg);
		$encryptedMsgInString = $this->toString($encryptedMsg);
		return $encryptedMsgInString;
	}
	
	public function decryptMsgInString($encryptedMsgInString) {
		$encryptedMsg = $this->toByteArray($encryptedMsgInString);
		$msgInByte = $this->decryptingRequest($encryptedMsg);
		$this->printArray($msgInByte);
		$msg = $this->toString($msgInByte);
		return $msg;
	}

	public function toByteArray($msgInString) {
		$len = strlen($msgInString)/2;
		$tmp = array_fill(0, $len, 0);
		for ($i = 0; $i < $len; $i++) {
			$tmp[$i] = hexdec(substr($msgInString, 2 * $i, 2));
		}
		return $tmp;
	}
	
	public function toString($msgInByte) {
		$msg = "";
		for ($i = 0; $i < count($msgInByte); $i++) {
			$msg = $msg . dechex($msgInByte[$i]);
		}
		$msg = strtoupper($msg);
		return $msg;
	}
	
	private $key; // int[]
	private $iv; // int[]
    private $s; // int[]

    private $rkey;
    private $riv;
    private $b; 
    private $k;
    private $byteOffset;
    private $mySaveOffset; 
}
?>