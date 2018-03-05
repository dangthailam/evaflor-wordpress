<?php

    class Tag {

        private $conn = null;
        private $table_name = "product_tags";

        public $productId;
        public $tag;

        //constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        //insert products
        function create(){
 
            // query to insert record
            $query = "INSERT INTO
                        " . $this->table_name . "
                    SET
                    productId=:tag, productDes=:tag";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->productId=htmlspecialchars(strip_tags($this->productId));
            $this->tag=htmlspecialchars(strip_tags($this->tag));
        
            // bind values
            $stmt->bindParam(":productId", $this->productId);
            $stmt->bindParam(":tag", $this->tag);
        
            // execute query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
    }

?>