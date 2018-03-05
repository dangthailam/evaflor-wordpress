<?php

    class Database{
        //specify the database credentials
        
		
		private $host = "localhost";
        /*
		private $db_name = "wordpress_db";
        private $username = "root";
        private $password = "";
		*/
		
		private $db_name = "wordpress_vac";
		private $username = "wpvacsuser";
		private $password = "vac@!123!@";
		
        public $conn;
        
        //get the database connection
        public function getConnection(){
            
            $this->conn = null;
            
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
            return $this->conn;
        }
		
		public function getMysqlConnection() {
			$this->conn = null;
			$this->conn = mysql_connect($this->host, $this->username, $this->password);
			mysql_select_db($this->db_name, $this->conn) or die(mysql_error());
		}
    }

?>