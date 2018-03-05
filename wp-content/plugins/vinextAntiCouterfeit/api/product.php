<?php

    class Product {

        private $conn = null;

        public $productId;
        public $productDes;
        public $tags = array();

        public $searchTag;
        //constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        //read products
        function searchProduct(){
            
            //select all query
            $query = "Select product.post_content, product.post_name from wp_posts product, wp_terms tag, wp_term_relationships rel
            where tag.name = " . $this->searchTag . "and tag.term_id = rel.term_taxonomy_id and product.id = rel.object_id;";
            
            //prepare query statement
            $stmt = $this->conn->prepare($query);
            
            //execute query
            $stmt->execute();
            return $stmt;
        }

        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        //insert products
        function createProduct(){

           
            //build tag values
            $tagValues = '';

            for( $i = 0; $i < sizeof($this->tags); $i++ ) {
                
                /* $tagValues .= "('" . $this->tags[$i] . "', '" . str_replace(' ', '-', $this->tags[$i]) . "', 0),";  */
                $tagValues = $tagValues . $this->tags[$i] . ', ';
             }

             /* $tagValues .= ';'; */

            //post title
            $postTitle = $this->get_string_between($this->productDes, "<h3>", "</h3>");

            //post name -- this is the product Id
            $postName = str_replace(' ', '-', $this->productId);

            $searchProductId = $this->conn->prepare('SELECT * FROM wp_tags where productId = :productId');
            $searchProductId->bindParam(':productId', $this->productId);
            $searchProductId->execute();
            
            if($searchProductId->rowCount() > 0){
                echo 'product is existing...';
            } else {
                $call1 = $this->conn->prepare('CALL insertProduct(:postName, :productName, :postTitle)');
                $call1->bindParam(':postName', $postName);
                $call1->bindParam(':productName', $this->productDes);
                $call1->bindParam(':postTitle', $postTitle);
                $call1->execute();
            }
                
           /*  $call1 = $this->conn->prepare('CALL insertProduct(:postName, :productName, :postTitle)');
            $call1->bindParam(':postName', $postName);
            $call1->bindParam(':productName', $this->productDes);
            $call1->bindParam(':postTitle', $postTitle);
            $call1->execute(); */

            $sql = array(); 
            for( $i = 0; $i < sizeof($this->tags); $i++ ) {
                
                $sql[] = '("' . $this->productId .'", "' . $this->tags[$i] .'")';
            }

            $insertQ = 'INSERT INTO wp_tags(productId, tag) VALUES ' . implode(',', $sql);

            $stmt = $this->conn->prepare($insertQ);
            if($stmt->execute()){
                return true;
            } else {
                return false;
            }

        }

        function getProduct($tag){

             //select all query
             $query ="select productId from adntest.product_tags where tag=" . $tag;
             
             //prepare query statement
             $stmt = $this->conn->prepare($query);
             
             //execute query
             $stmt->execute();
             return $stmt;

        }
    }

?>